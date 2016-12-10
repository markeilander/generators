<?php

namespace Eilander\Generators\Traits;

use Eilander\Generators\GeneratorException;

trait BuilderTrait
{
    protected $template;

    /**
     * Store the given template, to be inserted somewhere.
     *
     * @param string $template
     *
     * @return $this
     */
    protected function insert($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get the stored template, and insert into the given wrapper.
     *
     * @param string $wrapper
     * @param string $placeholder
     *
     * @return mixed
     */
    protected function into($wrapper, $placeholder = 'schema_up')
    {
        return str_replace('{{'.$placeholder.'}}', $this->template, $wrapper);
    }

    /**
     * Get the correct stub file.
     *
     * @param string $path
     * @param string $package
     *
     * @return string
     */
    protected function stub($type, $path)
    {
        if (trim($this->getPackagePath()) != '') {
            $stubPath = null;
            $resourcePath = base_path('resources/stubs/vendor/generators/').$path;
            // Get stubPath from given path and basePath
            switch ($type) {
                case 'view':
                    if (file_exists($resourcePath)) {
                        $stubPath = $resourcePath;
                    } else {
                        $stubPath = $this->getPackagePath().'/Stubs/html/'.$path;
                    }
                    break;
                case 'package':
                    $stubPath = $this->getPackagePath().'/Stubs/'.$path;
                break;
            }
            // check if file exists en retrieve contents
            if ($stubPath && file_exists($stubPath)) {
                return $stubPath;
            }
        } else {
            throw new GeneratorException('A packagepath must be defined!');
        }
    }

    /**
     * Get the correct stub file.
     *
     * @param string $path
     *
     * @return string
     */
    protected function wrapper($type, $path)
    {
        $stubPath = $this->stub($type, $path);
        // check if file exists en retrieve contents
        if ($stubPath && file_exists($stubPath)) {
            return file_get_contents($stubPath);
        }
    }

    /**
     * Get correct package path for stubs eo.
     */
    protected function getPackagePath()
    {
        return realpath(__DIR__.'/../');
    }
}
