<?php

namespace Eilander\Generators\Traits;

trait OptionTrait
{
    /**
     * Get options.
     *
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Determinte whether the given key exist in options array.
     *
     * @param string $key
     *
     * @return bool
     */
    protected function hasOption($key)
    {
        return array_key_exists($key, $this->options);
    }

    /**
     * Get value from options by given key.
     *
     * @param string      $key
     * @param string|null $default
     *
     * @return string
     */
    protected function getOption($key, $default = null)
    {
        if (!$this->hasOption($key)) {
            return $default;
        }

        return $this->options[$key] ?: $default;
    }

    /**
     * Helper method for "getOption".
     *
     * @param string      $key
     * @param string|null $default
     *
     * @return string
     */
    protected function option($key, $default = null)
    {
        return $this->getOption($key, $default);
    }
}
