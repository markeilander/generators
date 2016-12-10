<?php

namespace Eilander\Generators\Migrations;

class TableParser
{
    /**
     * The parsed schema table.
     *
     * @var array
     */
    private $table = '';
    /**
     * The parsed schema.
     *
     * @var array
     */
    private $schema = [];

    /**
     * List of reserved fields.
     *
     * @var array
     */
    private $reservedFields = ['id', 'updated_at', 'created_at'];

    /**
     * Parse the command line table.
     *
     * @param string $table
     *
     * @return array
     */
    public function parse($table)
    {
        if (empty($this->schema)) {
            $fields = \DB::select('SHOW COLUMNS FROM `'.$table.'`');
            foreach ($fields as $field) {
                $segments = $this->parseSegments($field);
                if (is_array($segments)) {
                    $this->addField($segments);
                }
            }
        }

        return $this->schema;
    }

    /**
     * Check if table exists.
     *
     * @param string $table
     *
     * @return bool
     */
    public function exists($table)
    {
        if (\Schema::hasTable($table)) {
            return true;
        }

        return false;
    }

    /**
     * Add a field to the schema array.
     *
     * @param array $field
     *
     * @return $this
     */
    private function addField($field)
    {
        $this->schema[] = $field;

        return $this;
    }

    /**
     * Get the segments of the schema field.
     *
     * @param string $field
     *
     * @return array
     */
    private function parseSegments($field)
    {
        $name = $field->Field;
        $type = $this->parseType($field->Type);

        if ($this->reservedField($name)) {
            return;
        }

        return compact('name', 'type');
    }

    private function parseType($type)
    {
        $type = preg_replace('/[^a-zA-Z]/', '', $type);

        return $type;
    }

    private function reservedField($field)
    {
        if (in_array($field, $this->reservedFields)) {
            return true;
        }

        return false;
    }
}
