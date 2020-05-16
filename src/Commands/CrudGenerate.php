<?php

namespace Wikichua\Simplecontrolpanel\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class CrudGenerate extends Command
{
    protected $signature = 'crud:generate {model} {--force}';
    protected $description = 'Generate CRUD using config file.';
    protected $files;
    protected $config;
    protected $replaces = [];
    protected $controller_request_creates = [];
    protected $controller_request_updates = [];

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle()
    {
        // ensure config file exists
        $config_file = 'config/crud/' . $this->argument('model') . '.php';
        if (!$this->files->exists($config_file)) {
            $this->error('Config file not found: <info>' . $config_file . '</info>');
            return;
        }

        // set class values
        $this->config = include $config_file;
        $this->lap = config('lap.crud_paths');
        $this->setSimpleReplaces();
        $this->setAttributeReplaces();

        // generate crud
        $this->line('Generating <info>' . $this->argument('model') . '</info> CRUD...');
        $this->makeDirectories();
        $this->createControllerFile();
        $this->createModelFile();
        $this->createMigrationFile();
        $this->createViewFiles();
        $this->insertMenuItem();
        $this->insertRoutes();
        $this->line('CRUD generation for <info>' . $this->argument('model') . '</info> complete!');

        // ask to migrate
        // if ($this->confirm('Migrate now?')) {
        //     Artisan::call('migrate', ['--path' => $this->lap['migrations']]);
        //     $this->info('Migration complete!');
        // }
    }

    public function setSimpleReplaces()
    {
        // set simple replacement searches for stubs
        $this->replaces = [
            '{controller_namespace}' => $controller_namespace = ucfirst(str_replace('/', '\\', $this->lap['controller'])),
            '{controller_route}' => ltrim(str_replace('App\\Http\\Controllers', '', $controller_namespace) . '\\', '\\'),
            '{model_namespace}' => $model_namespace = ucfirst(str_replace('/', '\\', $this->lap['model'])),
            '{model_class}' => $model_class = $this->argument('model'),
            '{model_string}' => $model_string = trim(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $model_class)),
            '{model_strings}' => $model_strings = str_plural($model_string),
            '{model_variable}' => $model_variable = strtolower(str_replace(' ', '_', $model_string)),
            '{model_variables}' => $model_variables = strtolower(str_replace(' ', '_', $model_strings)),
            '{l_model_string}' => "__l('" . $model_variable . "', '" . $model_string . "')",
            '{l_model_strings}' => "__l('" . $model_variable . "', '" . $model_strings . "')",
            '{model_primary_attribute}' => 'id',
            '{model_icon}' => isset($this->config['icon']) ? $this->config['icon'] : 'fa-link',
            '{view_prefix_url}' => $view_prefix_url = ltrim(str_replace('resources/views', '', $this->lap['views']) . '/', '/'),
            '{view_prefix_name}' => $view_prefix_name = str_replace('/', '.', $view_prefix_url),
            '{seo_action}' => isset($this->config['need_seo']) && $this->config['need_seo'] ? "@include('{$view_prefix_name}{$model_variables}.datatable.seo_action')" : '',
            '{seo_init}' => isset($this->config['need_seo']) && $this->config['need_seo'] ? '$this->initSeo(\'' . $model_namespace . '\\' . $model_class . '\', $' . $model_variable . '->id);' : '',
            '{seo_action_compact}' => isset($this->config['need_seo']) && $this->config['need_seo'] ? "app(config('lap.models.seotool'))->query()->where('model','$model_namespace\\$model_class')->where('model_id',$".$model_variable."->id)->first()" : "''",
            '{batch_delete_action}' => isset($this->config['batch_delete_action']) && $this->config['batch_delete_action']? 'true':'false',
            '{batch_delete_datatable_parameter}' => isset($this->config['batch_delete_action']) && $this->config['batch_delete_action']? "'select' => ['style' => 'multi'],\n\t\t\t'dom' => '<\"toolbar\">lBfrtip',\n\t\t\t\t'buttons' => [\n\t\t\t\t\t'selectAll',\n\t\t\t\t\t'selectNone'\n\t\t\t\t],":"",
        ];
    }

    public function setAttributeReplaces()
    {
        // set replacement searches using attribute values
        $attributes = isset($this->config['attributes']) ? $this->config['attributes'] : [];
        $model_casts = [];
        $model_appends = [];
        $relationships = [];
        $relationships_query = [];
        $user_timezones = [];
        $mutators = [];
        $migrations = [];
        $validations = [];
        $datatable = [];
        $fillable_array = [];
        $read_attributes = [];
        $form_enctype = '';
        $inputs_create = [];
        $inputs_update = [];
        $inputs_filter = [];

        foreach ($attributes as $attribute => $values) {
            // fillable
            $fillable_array[] = "'$attribute'";
            
            // model primary attribute
            if (!empty($values['primary'])) {
                $this->replaces['{model_primary_attribute}'] = $attribute;
            }

            // model casts attribute
            if (!empty($values['casts'])) {
                $model_casts[] = "'$attribute' => '" . $values['casts'] . "'";
            }

            $mutator_name = $attribute;
            if (isset($values['appends']) && $values['appends']) {
                if ($values['appends'] === true) {
                    $model_appends[] = "'$attribute'";
                } else {
                    $model_appends[] = "'" . trim($values['appends']) . "'";
                    $mutator_name = trim($values['appends']);
                }
            }

            // relationships
            if (!empty($values['relationship'])) {
                $relationships[] = $this->indent() . 'public function ' . array_keys($values['relationship'])[0] . '()';
                $relationships[] = $this->indent() . '{';
                $relationships[] = $this->indent() . '    return $this->' . $this->putInChains(array_values($values['relationship'])[0]) . ';';
                $relationships[] = $this->indent() . '}' . PHP_EOL;
                $relationships_query[] = array_keys($values['relationship'])[0];
            }

            // user timezones
            if (!empty($values['user_timezone'])) {
                $user_timezones[] = $this->indent() . 'public function get' . studly_case($attribute) . 'Attribute($value)';
                $user_timezones[] = $this->indent() . '{';
                $user_timezones[] = $this->indent() . '    return $this->inUserTimezone($value);';
                $user_timezones[] = $this->indent() . '}' . PHP_EOL;
            }

            // mutators
            if (!empty($values['mutators']['get'])) {
                $mutators[] = $this->indent() . 'public function get' . studly_case($mutator_name) . 'Attribute($value)';
                $mutators[] = $this->indent() . '{';
                $lines = explode("\n", trim($values['mutators']['get']));
                foreach ($lines as $line) {
                    $mutators[] = $this->indent() . '    ' . trim($line);
                }
                $mutators[] = $this->indent() . '}' . PHP_EOL;
            }
            if (!empty($values['mutators']['set'])) {
                $mutators[] = $this->indent() . 'public function set' . studly_case($mutator_name) . 'Attribute($value)';
                $mutators[] = $this->indent() . '{';
                $lines = explode("\n", trim($values['mutators']['set']));
                foreach ($lines as $line) {
                    $mutators[] = $this->indent() . '    ' . trim($line);
                }
                $mutators[] = $this->indent() . '}' . PHP_EOL;
            }

            // migrations
            if (!empty($values['migrations'])) {
                foreach ($values['migrations'] as $migration) {
                    $migrations[] = $this->indent(3) . '$table->' . $this->putInChains($migration) . ';';
                }
            }

            // validations (create & update)
            if (!empty($values['validations'])) {
                foreach ($values['validations'] as $method => $rules) {
                    if (isset($values['input']['type']) && $values['input']['type'] == 'file')
                        $validations[$method][] = $this->indent(3) . '"' . $attribute . '_file" => "' . $rules . '",';
                    else
                        $validations[$method][] = $this->indent(3) . '"' . $attribute . '" => "' . $rules . '",';
                }
            }

            // datatable
            if (!empty($values['datatable'])) {
                $datatable[] = $this->indent(3) . $this->flattenArray($values['datatable']) . ',';
            }

            // exporttable
            if (!empty($values['exporttable'])) {
                $exporttable[] = $this->indent(3) . '\'' . $values['exporttable'] . '\'' . ',';
            }

            // read attributes
            $attribute_label = ucwords(str_replace('_', ' ', $attribute));
            $attribute_value = '$' . $this->replaces['{model_variable}'] . '->' . $attribute;
            $read_stub = $this->files->get($this->lap['stubs'] . '/views/layouts/read.stub');
            $read_stub = str_replace('{attribute_label}', "__l('{$attribute}','{$attribute_label}')", $read_stub);

            $read_stub = str_replace('{attribute_value}', '{{ ' . (isset($values['casts']) && $values['casts'] == 'array' ? "is_array($attribute_value)? implode(', ', $attribute_value):''" : $attribute_value) . ' }}', $read_stub);

            $read_attributes[] = $read_stub . PHP_EOL;

            // form inputs
            if (!empty($values['input'])) {
                $input_stub = $this->files->get($this->lap['stubs'] . '/views/layouts/input.stub');
                $input_stub = str_replace('{attribute}', $attribute, $input_stub);
                $input_stub = str_replace('{attribute_label}', "__l('{$attribute}','{$attribute_label}')", $input_stub);

                $inputs_create[] = str_replace('{attribute_input}', $this->inputContent($values['input'], 'create', $attribute, $form_enctype), $input_stub) . PHP_EOL;
                $inputs_update[] = str_replace('{attribute_input}', $this->inputContent($values['input'], 'update', $attribute, $form_enctype), $input_stub) . PHP_EOL;
            }
            if (!empty($values['filter'])) {
                $inputs_filter[] = $this->indent(5) . $this->filterContent($values['filter'], $values['input'], $attribute) . PHP_EOL;
            }
        }

        $this->replaces['{model_casts}'] = $model_casts ? 'protected $casts = [' . implode(', ', $model_casts) . '];' : '';
        $this->replaces['{model_appends}'] = $model_appends ? 'protected $appends = [' . implode(', ', $model_appends) . '];' : '';
        $this->replaces['{relationships}'] = $relationships ? trim(implode(PHP_EOL, $relationships)) : '';
        $this->replaces['{relationships_query}'] = $relationships_query ? "->with('" . implode("', '", $relationships_query) . "')" : '';
        $this->replaces['{user_timezones}'] = $user_timezones ? trim(implode(PHP_EOL, $user_timezones)) : '';
        $this->replaces['{mutators}'] = str_replace(array_keys($this->replaces), $this->replaces, ($mutators ? trim(implode(PHP_EOL, $mutators)) : ''));
        $this->replaces['{migrations}'] = $validations ? trim(implode(PHP_EOL, $migrations)) : '';
        $this->replaces['{validations_create}'] = isset($validations['create']) ? trim(implode(PHP_EOL, $validations['create'])) : '';
        $this->replaces['{validations_update}'] = isset($validations['update']) ? trim(implode(PHP_EOL, $validations['update'])) : '';
        $this->replaces['{datatable}'] = $datatable ? trim(implode(PHP_EOL, $datatable)) : '';
        $this->replaces['{fillable_array}'] = $fillable_array ? trim(implode(', ', $fillable_array)) : '';
        $this->replaces['{exporttable}'] = $exporttable ? trim(implode(PHP_EOL, $exporttable)) : '';
        $this->replaces['{read_attributes}'] = $read_attributes ? trim(implode(PHP_EOL, $read_attributes)) : '';
        $this->replaces['{form_enctype}'] = $form_enctype;
        $this->replaces['{inputs_create}'] = $inputs_create ? trim(implode(PHP_EOL, $inputs_create)) : '';
        $this->replaces['{inputs_update}'] = $inputs_update ? trim(implode(PHP_EOL, $inputs_update)) : '';
        $this->replaces['{inputs_filter}'] = $inputs_filter ? trim(implode(PHP_EOL, $inputs_filter)) : '';
        $this->replaces['{controller_request_creates}'] = isset($this->controller_request_creates) && is_array($this->controller_request_creates) ? trim(implode(PHP_EOL, array_unique($this->controller_request_creates))) : '';
        $this->replaces['{controller_request_updates}'] = isset($this->controller_request_updates) && is_array($this->controller_request_updates) ? trim(implode(PHP_EOL, array_unique($this->controller_request_updates))) : '';
    }

    public function filterContent($filter, $input, $attribute)
    {
        $replaces = [];
        $replaces['{input_label}'] = ucwords(str_replace('_', ' ', $attribute));
        if (in_array($filter['type'], ['text', 'date', 'date_range'])) {
            $stub = $this->files->get($this->lap['stubs'] . '/views/filters/' . trim(strtolower($filter['type'])) . '.stub');
            $replaces['{input_name}'] = $attribute;
            $replaces['{input_id}'] = $attribute;
        } elseif ($filter['type'] == 'select') {
            $stub = $this->files->get($this->lap['stubs'] . '/views/filters/select.stub');
            if (!empty($input['multiple'])) {
                $replaces['{input_name_sign}'] = '[]';
            }
            $replaces['{input_name}'] = $attribute;
            $replaces['{input_id}'] = $attribute;
            $replaces = $this->inputSelectOptions($attribute, $input, 'create', $replaces);
        }

        $stub = str_replace(array_keys($this->replaces), $this->replaces, str_replace(array_keys($replaces), $replaces, $stub));

        return trim($stub);
    }

    public function inputContent($input, $method, $attribute, &$form_enctype)
    {
        $replaces = [];

        if (in_array($input['type'], ['checkbox', 'radio'])) {
            $stub = $this->files->get($this->lap['stubs'] . '/views/inputs/checkbox_radio.stub');
            $replaces['{input_type}'] = $input['type'];
            $replaces['{input_name}'] = $attribute . ($input['type'] == 'checkbox' && !empty($input['options']) ? '[]' : '');
            $replaces['{input_id}'] = $attribute . '_{{ $loop->index }}';
            $replaces = $this->inputCheckOptions($attribute, $input, $method, $replaces);
        } else if ($input['type'] == 'file') {
            $form_enctype = ' enctype="multipart/form-data"';
            if ($method == 'update') {
                $stub = $this->files->get($this->lap['stubs'] . '/views/inputs/file_update_single.stub');
                if (!empty($input['multiple'])) {
                    $stub = $this->files->get($this->lap['stubs'] . '/views/inputs/file_update_multiple.stub');
                }
            } else {
                $stub = $this->files->get($this->lap['stubs'] . '/views/inputs/file_create_single.stub');
                if (!empty($input['multiple'])) {
                    $stub = $this->files->get($this->lap['stubs'] . '/views/inputs/file_create_multiple.stub');
                }
            }
            $replaces['{input_name}'] = $attribute;
            $replaces['{input_id}'] = $attribute;
            $replaces['{input_multiple}'] = !empty($input['multiple']) ? ' multiple' : '';
            $replaces['{input_class}'] = isset($input['class']) && $input['class'] != '' ? ' ' . $input['class'] : '';
            $replaces['{input_value}'] = $method == 'update' ? '$' . $this->replaces['{model_variable}'] . '->' . $attribute . '' : '';
            $attribute_file = $attribute . '_file';
            $model_variables = $this->replaces['{model_variables}'];

            if (empty($input['multiple'])) {
                $this->controller_request_updates[] = $this->controller_request_creates[] =
                    <<<EOT
        if (request()->hasFile('$attribute_file')) {
            request()->merge([
                '$attribute' => str_replace('public', 'storage', request()->file('$attribute_file')->store('public/$model_variables')),
            ]);
        }
EOT;
            } else {
                $this->controller_request_updates[] = $this->controller_request_creates[] =
                    <<<EOT
        \$uploaded_files = [];
        if (request()->hasFile('$attribute_file')) {
            foreach(request()->file('$attribute_file') as \$key => \$file)
            {
                \$uploaded_files[] = str_replace('public', 'storage', request()->file('$attribute_file.'.\$key)->store('public/$model_variables'));
            }
            request()->merge([
                '$attribute' => \$uploaded_files,
            ]);
        }
EOT;
            }

        } else if ($input['type'] == 'select') {
            $stub = $this->files->get($this->lap['stubs'] . '/views/inputs/select.stub');
            if (!empty($input['multiple'])) {
                $replaces['{input_name_sign}'] = '[]';
            }
            $replaces['{input_name}'] = $attribute;
            $replaces['{input_id}'] = $attribute;
            $replaces = $this->inputSelectOptions($attribute, $input, $method, $replaces);
        } else if ($input['type'] == 'textarea') {
            $stub = $this->files->get($this->lap['stubs'] . '/views/inputs/textarea.stub');
            $replaces['{input_name}'] = $attribute;
            $replaces['{input_id}'] = $attribute;
            $replaces['{input_value}'] = $method == 'update' ? '{{ $' . $this->replaces['{model_variable}'] . '->' . $attribute . ' }}' : '';
            $replaces['{input_class}'] = isset($input['class']) && $input['class'] != '' ? ' ' . $input['class'] : '';
        } else {
            $stub = $this->files->get($this->lap['stubs'] . '/views/inputs/text.stub');
            if (isset($input['tags']) && $input['tags']) {
                $stub = $this->files->get($this->lap['stubs'] . '/views/inputs/tags.stub');
            }
            $replaces['{input_type}'] = $input['type'];
            $replaces['{input_name}'] = $attribute;
            $replaces['{input_id}'] = $attribute;
            $replaces['{input_class}'] = isset($input['class']) && $input['class'] != '' ? ' ' . $input['class'] : '';
            $model_preinput = '$' . $this->replaces['{model_variable}'] . '->' . $attribute;
            $replaces['{input_value}'] = $method == 'update' ? ' value="{{ ' . $model_preinput . ' }}"' : '';
            if (isset($input['tags']) && $input['tags']) {
                $replaces['{input_value}'] = $method == 'update' ? ' value="{{ implode(\',\',' . $model_preinput . ') }}"' : '';
            }
        }

        $stub = str_replace(array_keys($this->replaces), $this->replaces, str_replace(array_keys($replaces), $replaces, $stub));

        return trim($stub);
    }

    public function inputCheckOptions($attribute, $input, $method, $replaces)
    {
        if (empty($input['options'])) {
            // single check
            $replaces['{input_options}'] = '[' . $this->quoteVar($input['value']) . ']';
            $replaces['{input_option}'] = '$option';
            $replaces['{input_option_value}'] = '{{ $option }}';
            $replaces['{input_option_label}'] = !empty($input['label']) ? $input['label'] : ucwords(str_replace('_', ' ', $attribute));
            $replaces['{input_option_checked}'] = $this->inputOptionChecked($method, $input, $attribute, '$option');
        } else if (is_string($input['options'])) {
            // relationship checks
            $replaces['{input_options}'] = $input['options'];
            $replaces['{input_option}'] = '$key => $val';
            $replaces['{input_option_value}'] = '{{ $key }}';
            $replaces['{input_option_label}'] = '{{ __l(strtolower($val), $val) }}';
            $replaces['{input_option_checked}'] = $this->inputOptionChecked($method, $input, $attribute, '$key');
        } else if (is_array(array_values($input['options'])[0])) {
            // relationship checks
            $key = array_keys($input['options'])[0];
            $value = array_keys($input['options'][$key])[0];
            $label = array_values($input['options'][$key])[0];

            $replaces['{input_options}'] = $this->putInChains($key);
            $replaces['{input_option}'] = '$model';
            $replaces['{input_option_value}'] = '{{ $model->' . $value . ' }}';
            $replaces['{input_option_label}'] = '{{ $model->' . $label . ' }}';
            $replaces['{input_option_checked}'] = $this->inputOptionChecked($method, $input, $attribute, '$model->' . $value);
        } else if (array_keys($input['options']) !== range(0, count($input['options']) - 1)) {
            // checks are associative array (key is defined)
            $replaces['{input_options}'] = $this->flattenArray($input['options']);
            $replaces['{input_option}'] = '$value => $label';
            $replaces['{input_option_value}'] = '{{ $value }}';
            $replaces['{input_option_label}'] = '{{ $label }}';
            $replaces['{input_option_checked}'] = $this->inputOptionChecked($method, $input, $attribute, '$value');
        } else {
            // checks are sequential array (key = 0, 1, 2, 3)
            $replaces['{input_options}'] = "['" . implode("', '", $input['options']) . "']";
            $replaces['{input_option}'] = '$option';
            $replaces['{input_option_value}'] = '{{ $option }}';
            $replaces['{input_option_label}'] = '{{ $option }}';
            $replaces['{input_option_checked}'] = $this->inputOptionChecked($method, $input, $attribute, '$option');
        }

        return $replaces;
    }

    public function inputOptionChecked($method, $input, $attribute, $value)
    {
        if ($method == 'update') {
            if (empty($input['options']) || $input['type'] == 'radio') {
                return '{{ ' . $value . ' == $' . $this->replaces['{model_variable}'] . '->' . $attribute . " ? ' checked' : '' }}";
            } else {
                return '{{ !empty($' . $this->replaces['{model_variable}'] . '->' . $attribute . ') && in_array(' . $value . ', $' . $this->replaces['{model_variable}']
                    . '->' . $attribute . ") ? ' checked' : '' }}";
            }
        } else {
            return '';
        }
    }

    public function inputSelectOptions($attribute, $input, $method, $replaces)
    {
        if (is_string($input['options'])) {
            // relationship checks
            $replaces['{input_options}'] = $input['options'];
            $replaces['{input_option}'] = '$value => $label';
            $replaces['{input_option_value}'] = '{{ $value }}';
            $replaces['{input_option_label}'] = '{{ $label }}';
            $replaces['{input_option_selected}'] = $method == 'update' ? '{{ $value == $' . $this->replaces['{model_variable}'] . '->' . $attribute . " ? ' selected' : '' }}" : '';

        } else if (is_array(array_values($input['options'])[0])) {
            // relationship options
            $key = array_keys($input['options'])[0];
            $value = array_keys($input['options'][$key])[0];
            $label = array_values($input['options'][$key])[0];

            $replaces['{input_options}'] = $this->putInChains($key);
            if ($input['option_return'] == 'array') {
                $replaces['{input_option}'] = '$' . $value . ' => $' . $label;
                $replaces['{input_option_value}'] = '{{ $' . $value . ' }}';
                $replaces['{input_option_label}'] = '{{ $' . $label . ' }}';
                $replaces['{input_option_selected}'] = $method == 'update' ? '{{ $' . $value . ' == $' . $this->replaces['{model_variable}'] . '->' . $attribute . " ? ' selected' : '' }}" : '';
            } else {
                $replaces['{input_option}'] = '$model';
                $replaces['{input_option_value}'] = '{{ $model->' . $value . ' }}';
                $replaces['{input_option_label}'] = '{{ $model->' . $label . ' }}';
                $replaces['{input_option_selected}'] = $method == 'update' ? '{{ $model->' . $value . ' == $' . $this->replaces['{model_variable}'] . '->' . $attribute . " ? ' selected' : '' }}" : '';
            }

        } else if (array_keys($input['options']) !== range(0, count($input['options']) - 1)) {
            // options are associative array (key is defined)
            $replaces['{input_options}'] = $this->flattenArray($input['options']);
            $replaces['{input_option}'] = '$value => $label';
            $replaces['{input_option_value}'] = '{{ $value }}';
            $replaces['{input_option_label}'] = '{{ $label }}';
            $replaces['{input_option_selected}'] = $method == 'update' ? '{{ $value == $' . $this->replaces['{model_variable}'] . '->' . $attribute . " ? ' selected' : '' }}" : '';
        } else {
            // options are sequential array (key = 0, 1, 2, 3)
            $replaces['{input_options}'] = "['" . implode("', '", $input['options']) . "']";
            $replaces['{input_option}'] = '$option';
            $replaces['{input_option_value}'] = '{{ $option }}';
            $replaces['{input_option_label}'] = '{{ $option }}';
            $replaces['{input_option_selected}'] = $method == 'update' ? '{{ $option == $' . $this->replaces['{model_variable}'] . '->' . $attribute . " ? ' selected' : '' }}" : '';
        }
        $replaces['{input_multiple}'] = !empty($input['multiple']) ? ' multiple' : '';
        $replaces['{input_class}'] = isset($input['class']) && $input['class'] != '' ? ' ' . $input['class'] : '';
        $replaces['{live_search}'] = isset($input['live_search']) && $input['live_search'] ? 'true' : 'false';

        return $replaces;
    }

    public function replace($content)
    {
        // replace all occurrences with $this->replaces
        foreach ($this->replaces as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        return $content;
    }

    public function makeDirectories()
    {
        // create directories recursively if they don't already exist
        $directories = [
            $this->lap['controller'],
            $this->lap['model'],
            $this->lap['migrations'],
            $this->lap['menu'],
            $this->lap['route'],
            $this->lap['views'] . '/' . $this->replaces['{model_variables}'] . '/datatable',
        ];

        foreach ($directories as $directory) {
            if (!$this->files->exists($directory)) {
                $this->files->makeDirectory($directory, 0755, true);
            }
        }
    }

    public function createControllerFile()
    {
        // create controller file
        $controller_file = $this->lap['controller'] . '/' . $this->replaces['{model_class}'] . 'Controller.php';
        if ($this->prompt($controller_file)) {
            $controller_stub = $this->files->get($this->lap['stubs'] . '/controller.stub');
            $this->files->put($controller_file, $this->replace($controller_stub));
            $this->line('Controller file created: <info>' . $controller_file . '</info>');
        }
    }

    public function createModelFile()
    {
        // create model file
        $model_file = $this->lap['model'] . '/' . $this->replaces['{model_class}'] . '.php';
        if ($this->prompt($model_file)) {
            $model_stub = $this->files->get($this->lap['stubs'] . '/model.stub');
            $this->files->put($model_file, $this->replace($model_stub));
            $this->line('Model file created: <info>' . $model_file . '</info>');
        }
    }

    public function createMigrationFile()
    {
        // create migration file
        $migrations_file = $this->lap['migrations'] . '/' . date('Y_00_00_000000') . '_create_' . $this->replaces['{model_variable}'] . '_table.php';
        if ($this->prompt($migrations_file)) {
            $migrations_stub = $this->files->get($this->lap['stubs'] . '/migrations.stub');
            $this->files->put($migrations_file, $this->replace($migrations_stub));
            $this->line('Migration file created: <info>' . $migrations_file . '</info>');
        }
    }

    public function createViewFiles()
    {
        // create view files
        $view_path = $this->lap['views'] . '/' . $this->replaces['{model_variables}'];
        foreach ($this->files->allFiles($this->lap['stubs'] . '/views/models') as $file) {
            if ($file->getFilename() != 'widget.stub') {
                $new_file = $view_path . '/' . ltrim($file->getRelativePath() . '/' . str_replace('.stub', '.blade.php', $file->getFilename()), '/');
                if ($file->getFilename() == 'seo_action.stub') {
                    if ($this->config['need_seo']) {
                        if ($this->prompt($new_file)) {
                            $this->files->put($new_file, $this->replace($file->getContents()));
                            $this->line('View files created: <info>' . $new_file . '</info>');
                        }
                    }
                } else {
                    if ($this->prompt($new_file)) {
                        $this->files->put($new_file, $this->replace($file->getContents()));
                        $this->line('View files created: <info>' . $new_file . '</info>');
                    }
                }
            }
        }
    }

    public function insertMenuItem()
    {
        // create menu item file
        $menu_file = $this->lap['menu'] . '/' . $this->replaces['{model_variable}'] . '.blade.php';
        if ($this->prompt($menu_file)) {
            $menu_stub = $this->files->get($this->lap['stubs'] . '/views/layouts/menu.stub');
            $this->files->put($menu_file, $this->replace($menu_stub));
            $this->line('Menu item file created: <info>' . $menu_file . '</info>');

            $layout_menu = $this->files->get($this->lap['layout_menu']);
            $menu_content = PHP_EOL . '@include(\'lap::layouts.menu.' . $this->replaces['{model_variable}'] . '\')';
            if (strpos($layout_menu, $menu_content) === false) {
                $search = '{{-- menu inject start --}}';
                $index = strpos($layout_menu, $search);
                $this->files->put($this->lap['layout_menu'], substr_replace($layout_menu, $search . $menu_content, $index, strlen($search)));
                $this->line('Menu item included: <info>' . $this->lap['layout_menu'] . '</info>');
            }
        }

    }

    public function insertRoutes()
    {
        // create menu item file
        $route_file = $this->lap['route'] . '/' . $this->replaces['{model_variable}'] . '.php';
        if ($this->prompt($route_file)) {
            $routes_stub = $this->files->get($this->lap['stubs'] . '/routes.stub');
            $this->files->put($route_file, $this->replace($routes_stub));
            $this->line('Route file created: <info>' . $route_file . '</info>');

            $routes = $this->files->get($this->lap['routes']);
            $route_content = PHP_EOL . "include_once(resource_path('../{$route_file}'));";
            if (strpos($routes, $route_content) === false) {
                $this->files->append($this->lap['routes'], $route_content);
                $this->line('Route included: <info>' . $this->lap['routes'] . '</info>');
            }
        }

    }

    public function indent($multiplier = 1)
    {
        // add indents to line
        return str_repeat('    ', $multiplier);
    }

    public function putInChains($value)
    {
        // convert string to chains using methods and parameters
        $chains = [];

        foreach (explode('|', $value) as $chain) {
            $method_params = explode(':', $chain);
            $method = $method_params[0];
            $params_typed = [];

            // add quotes to parameter if not boolean or numeric
            if (isset($method_params[1])) {
                foreach (explode(',', $method_params[1]) as $param) {
                    $params_typed[] = (in_array($param, ['true', 'false']) || is_numeric($param)) ? $param : "'$param'";
                }
            }

            $chains[] = $method . '(' . implode(', ', $params_typed) . ')';
        }

        return implode('->', $chains);
    }

    public function flattenArray($array)
    {
        $flat = [];

        foreach ($array as $key => $value) {
            $flat[] = "'$key' => " . $this->quoteVar($value);
        }

        return '[' . implode(', ', $flat) . ']';
    }

    public function quoteVar($value)
    {
        return is_bool($value) || is_numeric($value) ? var_export($value, true) : "'$value'";
    }

    protected function prompt($file)
    {
        if ($this->option('force')) {
            return true;
        }
        $this->info($file);
        if (file_exists($file)) {
            if (!$this->confirm('Overwrite? At your own RISK!', false)) {
                return false;
            }
        } else {
            if (!$this->confirm('Create?', true)) {
                return false;
            }
        }
        return true;
    }
}
