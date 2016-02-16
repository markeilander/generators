<?php

namespace Eilander\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Composer;
use Eilander\Generators\Migrations\NameParser;

abstract class BaseCommand extends Command
{
    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var NameParser
     */
    protected $nameParser;
    
    /**
     * List with parsed names.
     *
     * @var array
     */
    protected $names;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @param Composer   $composer
     */
    public function __construct(Composer $composer)
    {
        parent::__construct();
        $this->nameParser = null;
        $this->composer = $composer;
        $this->names = null;
    }


    protected function getNameParser()
    {
        if (!$this->nameParser) {
           $this->nameParser = new NameParser($this->argument('name'));
        }
        return $this->nameParser;
    }

    /**
     * Generate names.
     *
     * @param string $config
     * @return mixed
     */
    protected function getNames($config = 'Name')
    {
        return $this->getNameParser()->getNames($config);
    }
}