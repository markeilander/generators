<?php namespace Eilander\Generators\Migrations;
/**
 * Class NameParser
 * @package Eilander\Repository\Generators\Migrations
 */
class NameParser {
    /**
     * The migration name.
     *
     * @var string
     */
    protected $name;

    /**
     * List with parsed names.
     *
     * @var array
     */
    protected $names;

    /**
     * The constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->names = null;
    }
    /**
     * Get original migration name.
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->name;
    }

    /**
     * Generate names.
     *
     * @param string $config
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getNames($config = 'Name')
    {
        if (!$this->names) {
            // Name[0] = Tweet
            $this->names['Name'] = ucfirst($this->name);
            // Name[3] = tweet
            $this->names['name'] = strtolower($this->name);
        }

        if (!isset($this->names[$config])) {
            throw new \Exception('Position name is not found');
        };

        return $this->names[$config];
    }
  }
