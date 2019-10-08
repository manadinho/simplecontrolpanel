### Installation

Require via composer:

    composer require wikichua/simplecontrolpanel

Publish install files:

    php artisan vendor:publish --tag="lap.install"

Add the `AdminUser`, `DynamicFillable`, and `UserTimezone` traits to your `User` model:

    use Wikichua\Simplecontrolpanel\Traits\AdminUser;
    use Wikichua\Simplecontrolpanel\Traits\DynamicFillable;
    use Wikichua\Simplecontrolpanel\Traits\UserTimezone;
    
    class User extends Authenticatable
    {
        use Notifiable, AdminUser, DynamicFillable, UserTimezone;

Add this in your controller.php
    use \Wikichua\Simplecontrolpanel\Traits\Controller;

    class Controller extends BaseController
    {
        use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
        use \Wikichua\Simplecontrolpanel\Traits\Controller;

Run the migrations:

    php artisan migrate


### Alternative installation:

Install laravel:

    composer create-project laravel/laravel --prefer-dist appName

Create directories in terminal:

    cd appName; mkdir packages; cd packages; mkdir wikichua; cd wikichua; git clone https://github.com/wikichua/simplecontrolpanel.git

Add this in your composer.json under scripts section:

    "repositories": {
        "wikichua-simplecontrolpanel": {
            "type": "path",
            "url": "/path/to/your/appName/packages/wikichua/simplecontrolpanel"
        }
    }


### Logging In

Visit `(APP_URL)/admin` to access the admin panel.

The default admin login is:

    Email Address: admin@example.com
    Password: admin123

### CRUD Configuration manual

#### Icon

This is simply the FontAwesome 5 icon class to use for the menu item e.g. fa-cogs.

#### Attributes

This is where most of your attention is needed. From this array, you can set all of the attributes the model will have. The default CRUD stubs already contain scaffolding for id, created_at, and updated_at, so you won't need to enter these attributes if using the default package stubs.

Each attribute can be defined in the following format:

    'title' => [
        'primary' => false,
        'migrations' => [
            'string:title|unique',
        ],
        'validations' => [
            'create' => 'required|unique:vehicles',
            'update' => 'required|unique:vehicles,title,{$vehicle->id}',
        ],
        'datatable' => [
            'title' => 'Title',
            'data' => 'title',
        ],
        'input' => [
            'type' => 'text',
        ],
    ],

Notice how the array key is the actual model attribute itself, and the values are its options. Each attribute option can be omitted.

The following options are available for each attribute you specify.

#### primary

Specifies if this attribute should be used as the primary label for the model. This is useful for things like the activity log, where the message contains a searchable phrase for the model.
    'primary' => true,

#### migrations

Specifies the migration methods for the attribute in the format of method|method:param|method:param,param.
    
    'migrations' => [
        'string:title|unique',
    ],

The example above would turn into $table->string('title')->unique();.

#### casts

Specifies the $casts mutator for the attribute.

    'casts' => 'array',

#### relationship

Specifies the relationship for the attribute in the format of 'model_method' => 'method|method:param|method:param,param.

    'relationship' => [
        'user' => 'belongsTo:App\User',
    ],

#### user_timezone

Specifies if the attribute should be converted into the users timezone via Carbon.

    'user_timezone' => true,

#### validation

Specifies the validation rules for the attribute in the same format used by the Laravel Validator. Note that the key for each is which controller method the rules are for.
    
    'validations' => [
        'create' => 'required|unique:vehicles',
        'update' => 'required|unique:vehicles,name,{$vehicle->id}',
    ],

Also note the use of {$vehicle->id} here. In this example, the CRUD config file would be named Vehicle.php, and used to generate CRUD for a Vehicle model. Therefore, we can use the $vehicle variable, which is an instance of Vehicle injected into the controller method.

#### datatable

Specifies the values used in order to generate the datatable column in the model index table.
    
    'datatable' => [
        'title' => 'Title',
        'data' => 'title',
        'orderable' => false,
    ],

Please see the Laravel Datatables Column Docs for information on available columns.

You can also set the data for a relationship by using dot annotation .e.g 'data' => 'user.name'.

#### input

Specifies the form input to use for the attribute.

    'input' => [
        'type' => 'select',
        'options' => ['Red', 'Green', 'Blue'],
    ],

The type can be checkbox, radio, file, select, text, textarea, or any HTML5 input type e.g. date.

You can also specify options using code methods, associative arrays, or sequential arrays.

options using code methods: return as in object
    
    'option_return' => 'object',
    'options' => [
        'app:App\User|orderBy:name|get' => [
            'id' => 'name',
        ],
    ],

options using code methods: return as in array
    
    'option_return' => 'array',
    'options' => [
        'settings:<what_ever_name>' => [
            'key' => 'val',
        ],
    ],

Notice the key in the format of method|method:param|method:param,param. Also, the input options will defined as 'value' => 'label', which represents the attribute for the object returned e.g. $user->id => $user->name.

options using associative arrays:
    
    'options' => [
        'auto' => 'Automatic Transmission',
        '4x4' => '4x4 Drivetrain',
    ],

options using sequential arrays:

    'options' => ['Red', 'Green', 'Blue'],

The same conventions for options apply to checkbox, radio, and select. However, if you want a checkbox with a single option, you should specify the value and label for said checkbox:
    
    'input' => [
        'type' => 'checkbox',
        'value' => true,
        'label' => 'This vehicle is financed',
    ],

Multiple attribute available in input type select, file & checkbox.
    
    'input' => [
        'type' => 'select', // select/checkbox/file
        'multiple' => true
    ],

Mutator

    'mutators' => [
        'get' => 'return \Carbon\Carbon::parse($value);',
        'set' => '$this->attributes[\'testdate\'] = \Carbon\Carbon::parse($value);',
    ]

1 line of return is preferred. If you need multiple lines

    'mutators' => [
        'get' => 'return \Carbon\Carbon::parse($value);',
        'set' => '
            list($start,$end) = explode(\'-\',$value);
            $this->attributes[\'testdaterange_start\'] = \Carbon\Carbon::parse(trim($start));
            $this->attributes[\'testdaterange_end\'] = \Carbon\Carbon::parse(trim($end));
        ',
    ]

Summernote

    'input' => [
        'type' => 'textarea',
        'class' => 'summernote'
    ],

If you need editor. Just add summernote to your class.

Date Picker

    'input' => [
        'type' => 'text',
        'class' => 'datepicker'
    ],
    'casts' => 'datetime:Y-m-d',
    'mutators' => [
        // 'get' => 'return \Carbon\Carbon::parse($value);',
        'set' => '$this->attributes[\'testdate\'] = \Carbon\Carbon::parse($value);',
    ]

Special for date picker, casting is important and mutator just don't include the get

Appends

    'appends' => true,

To append the field name

Sample:

    'content' => [
            'primary' => false,
            'migrations' => [
                'string:content|nullable',
            ],
            'validations' => [
                'create' => 'required',
                'update' => 'required',
            ],
            'datatable' => [
                'title' => 'Content',
                'data' => 'content',
            ],
            'exporttable' => 'content',
            'input' => [
                'type' => 'textarea',
                'class' => 'summernote',
            ],
        ],

        'testdate' => [
            'primary' => false,
            'migrations' => [
                'datetime:testdate|nullable',
            ],
            'validations' => [
                'create' => '',
                'update' => '',
            ],
            'datatable' => [
                'title' => 'date',
                'data' => 'testdate',
            ],
            'exporttable' => 'testdate',
            'input' => [
                'type' => 'text',
                'class' => 'datepicker'
            ],
            'casts' => 'datetime:Y-m-d',
            'mutators' => [
                // 'get' => 'return \Carbon\Carbon::parse($value);',
                'set' => '$this->attributes[\'testdate\'] = \Carbon\Carbon::parse($value);',
            ]
        ],

        'testdaterange_start' => [
            'primary' => false,
            'migrations' => [
                'datetime:testdaterange_start|nullable',
            ],
            'validations' => [
                'create' => '',
                'update' => '',
            ],
            // 'datatable' => [
            //     'title' => 'date',
            //     'data' => 'testdaterange_start',
            // ],
            // 'exporttable' => 'testdaterange_start',
            'input' => [
                'type' => 'text',
                'class' => 'rangedatepicker'
            ],
            'casts' => 'datetime:Y-m-d',
            'mutators' => [
                'get' => 'return $this->attributes[\'testdaterange_start\'] ." - ".$this->attributes[\'testdaterange_end\'];',
                'set' => '
                    list($start,$end) = explode(\' - \',$value);
                    $this->attributes[\'testdaterange_start\'] = \Carbon\Carbon::parse(trim($start));
                    $this->attributes[\'testdaterange_end\'] = \Carbon\Carbon::parse(trim($end));
                ',
            ]
        ],

        'testdaterange_end' => [
            'primary' => false,
            'migrations' => [
                'datetime:testdaterange_end|nullable',
            ],
            // 'validations' => [
            //     'create' => '',
            //     'update' => '',
            // ],
            // 'datatable' => [
            //     'title' => 'date',
            //     'data' => 'testdaterange_end',
            // ],
            // 'exporttable' => 'testdaterange_end',
            // 'input' => [
            //     'type' => 'text',
            //     'class' => 'rangedatepicker'
            // ],
            'casts' => 'datetime:Y-m-d',
            'mutators' => [
                'get' => 'return $this->attributes[\'testdaterange_start\'] ." - ".$this->attributes[\'testdaterange_end\'];',
                'set' => '
                    list($start,$end) = explode(\' - \',$value);
                    $this->attributes[\'testdaterange_start\'] = \Carbon\Carbon::parse(trim($start));
                    $this->attributes[\'testdaterange_end\'] = \Carbon\Carbon::parse(trim($end));
                ',
            ]
        ],

        'testdaterange' => [
            'primary' => false,
            // 'migrations' => [
            //     'datetime:testdaterange_start|nullable',
            //     'datetime:testdaterange_end|nullable',
            // ],
            // 'validations' => [
            //     'create' => '',
            //     'update' => '',
            // ],
            'datatable' => [
                'title' => 'date',
                'data' => 'testdaterange',
            ],
            // 'exporttable' => 'testdaterange',
            // 'input' => [
            //     'type' => 'text',
            //     'class' => 'rangedatepicker'
            // ],
            'appends' => true,
            'mutators' => [
                'get' => 'return $this->attributes[\'testdaterange_start\'] ." - ".$this->attributes[\'testdaterange_end\'];',
                
            ]
        ],

## Optional steps; This is for my own usage.

### exception

In case using API, just add this into Exceptions/Handler.php,

    public function render($request, Exception $exception)
    {
        if ($request->route() && $request->route()->getPrefix() == 'api') {
            if ($exception) {
                return response($exception, 400);
            }
            return response()->json(['status' => 'failed', 'error' => 'Intruder detected!']);
        }
        return parent::render($request, $exception);
    }

### always https

Add this into boot method in Providers/AppServiceProvider.php

    if (env('APP_ENV') == 'production') {
        \URL::forceScheme('https');
    }

### extend ApiController.php

This is optional. Bootstrapped for my own usage.

    YourAPIControllerName extends \Wikichua\Simplecontrolpanel\Controllers\ApiController {

    }