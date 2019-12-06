<?php

namespace Wikichua\Simplecontrolpanel\Commands;

use Illuminate\Console\Command;
use File;
use L5Swagger\Exceptions\L5SwaggerException;
use Symfony\Component\Yaml\Dumper as YamlDumper;
use OpenApi\Annotations\OpenApi;
use Symfony\Component\Finder\Finder;

class CrudSwagger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:swagger 
                            {fileController : your specific file controller which you want to generate json file e.g ApiController}
                            {jsonFile : your specific json file name e.g api-docs.json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate docs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Custom Generate Json File');
        $fileController = trim($this->argument('fileController'));
        $jsonFile = trim($this->argument('jsonFile'));
        (new Generator($fileController, $jsonFile))->generateDocs2();
    }
}

class Generator extends \L5Swagger\Generator
{
    /**
     * @var string|array
     */
    protected $annotationsDir;

    /**
     * @var string
     */
    protected $docDir;

    /**
     * @var string
     */
    protected $docsFile;

    /**
     * @var string
     */
    protected $yamlDocsFile;

    /**
     * @var array
     */
    protected $excludedDirs;

    /**
     * @var array
     */
    protected $constants;

    /**
     * @var \OpenApi\Annotations\OpenApi
     */
    protected $swagger;

    /**
     * @var bool
     */
    protected $yamlCopyRequired;

    public function __construct($fileController, $jsonFile)
    {
        parent::__construct();
        $this->docsFile = $this->docDir.'/'.$jsonFile;
        $this->fileController = $fileController;
    }

    public function generateDocs2()
    {
        $this->prepareDirectory()
            ->defineConstants()
            ->scanFilesForDocumentation()
            ->populateServers()
            ->saveJson()
            ->makeYamlCopy();
    }

    /**
     * Check directory structure and permissions.
     *
     * @return Generator
     */
    protected function prepareDirectory()
    {
        if (File::exists($this->docDir) && ! is_writable($this->docDir)) {
            throw new L5SwaggerException('Documentation storage directory is not writable');
        }

        if (File::exists($this->docDir)) {
            // File::deleteDirectory($this->docDir);
            File::delete($this->docsFile);
        } else {
            File::makeDirectory($this->docDir);
        }

        return $this;
    }
}

function scan($directory, $fileController, $options = [])
{
    $analyser = array_key_exists('analyser', $options) ? $options['analyser'] : new \OpenApi\StaticAnalyser();
    $analysis = array_key_exists('analysis', $options) ? $options['analysis'] : new \OpenApi\Analysis();
    $processors = array_key_exists('processors', $options) ? $options['processors'] : \OpenApi\Analysis::processors();
    $exclude = array_key_exists('exclude', $options) ? $options['exclude'] : null;
    $pattern = array_key_exists('pattern', $options) ? $options['pattern'] : null;

    // Crawl directory and parse all files
    $finder = \OpenApi\Util::finder($directory, $exclude, $pattern);
    foreach ($finder as $file) {
        if (basename($file) == $fileController) {
            $analysis->addAnalysis($analyser->fromFile($file->getPathname()));
        }
    }
    // Post processing
    $analysis->process($processors);
    // Validation (Generate notices & warnings)
    $analysis->validate();
    return $analysis->openapi;
}