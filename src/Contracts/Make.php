<?php

namespace Eilander\Generators\Contracts;

/**
 * Interface CriteriaInterface.
 */
interface Make
{
    /**
     * Render maker.
     *
     * @return mixed
     */
    public function run();

    /**
     * Render maker.
     *
     * @return mixed
     */
    public function render();
}
