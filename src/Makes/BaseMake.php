<?php

namespace Eilander\Generators\Makes;

use Eilander\Generators\Contracts\Make;
use Eilander\Generators\Migrations\NameParser;
use Eilander\Generators\Migrations\TableParser;
use Eilander\Generators\Stub;
use Eilander\Generators\Traits\OptionTrait;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Filesystem\Filesystem;

abstract class BaseMake implements Make
{
    use OptionTrait, AppNamespaceDetectorTrait;
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;
    /**
     * The nameparser instance.
     *
     * @var \Eilander\Migrations\NameParser
     */
    protected $nameParser;
    /**
     * The array of options.
     *
     * @var array
     */
    protected $options;
    /**
     * Schema string from command.
     *
     * @var string
     */
    protected $schema;
    /**
     * Set output path.
     *
     * @var string
     */
    protected $path;
    /**
     * Set stub path.
     *
     * @var string
     */
    protected $stubPath;
    /**
     * replacements.
     *
     * @var string
     */
    protected $replacements;
    /**
     * replacement start string.
     *
     * @var string
     */
    protected $replacementStart;
    /**
     * replacement end string.
     *
     * @var string
     */
    protected $replacementEnd;

    /**
     * name of the class to be build.
     *
     * @var string
     */
    protected $name;

    public function __construct(array $options = [])
    {
        $this->files = new Filesystem();
        $this->schema = null;
        $this->replacements = [];
        $this->path = null;
        $this->stubPath = null;
        $this->options = $options;
        $this->nameParser = $this->getNameParser();
        $this->replacementStart = '{{';
        $this->replacementEnd = '}}';
    }

    public function render()
    {
    }

    protected function getSchema()
    {
        if ($this->schema === null) {
            if ($this->option('table')) {
                $tableParser = new TableParser();
                $this->schema = $tableParser->parse($this->option('table'));
            }
        }

        return $this->schema;
    }

    protected function getNameParser()
    {
        if (!$this->nameParser) {
            $this->nameParser = new NameParser($this->option('name'));
        }

        return $this->nameParser;
    }

    /**
     * Get stub template for generated file.
     *
     * @return string
     */
    public function getStub()
    {
        return (new Stub(
            $this->stubPath,
            $this->replacements,
            $this->replacementStart,
            $this->replacementEnd
        )
        )->render();
    }

    /**
     * Run generator.
     *
     * @return int
     */
    public function run()
    {
        if (!$this->files->isDirectory($dir = dirname($this->path))) {
            $this->files->makeDirectory($dir, 0777, true, true);
        }

        return $this->files->put($this->path, $this->getStub());
    }

     /**
      * Check if files exists.
      *
      * @return bool
      */
     public function exists()
     {
         if ($this->files->exists($this->path)) {
             return true;
         }

         return false;
     }

    /**
     * Get template replacements.
     *
     * @return array
     */
    protected function getReplacements()
    {
        return [
            'table'     => $this->option('table'),
            'class'     => $this->name,
        ];
    }

    /**
     * Get root namespace.
     *
     * @return string
     */
    protected function getRootNamespace()
    {
        return config($this->configName().'.generator.rootNamespace', $this->getAppNamespace());
    }

    /**
     * Get root path.
     *
     * @return string
     */
    protected function getRootPath()
    {
        return config($this->configName().'.generator.rootPath', '');
    }

    /**
     * Get base path.
     *
     * @return string
     */
    protected function getBasePath()
    {
        return base_path();
    }
}
