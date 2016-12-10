<?php

namespace Eilander\Generators\Migrations;

use Eilander\Generators\GeneratorException;
use Eilander\Generators\Traits\BuilderTrait;

/**
 * Class SyntaxBuilder with modifications by Mark.
 *
 * @author Jeffrey Way <jeffrey@jeffrey-way.com>
 */
class SyntaxBuilder
{
    use BuilderTrait;

    /**
     * Create the PHP syntax for the given schema.
     *
     * @param array  $schema
     * @param array  $meta
     * @param string $type
     *
     * @throws GeneratorException
     *
     * @return string
     */
    public function create($schema, $meta, $type = 'migration')
    {
        if ($type == 'model') {
            $fieldsc = $this->createSchemaForModel($schema);

            return $fieldsc;
        } elseif ($type == 'controller') {
            $fieldsc = $this->createSchemaForControllerMethod($schema, $meta);

            return $fieldsc;
        } elseif ($type == 'view-index-header') {
            $fieldsc = $this->createSchemaForViewMethod($schema, $meta, 'index-header');

            return $fieldsc;
        } elseif ($type == 'view-index-content') {
            $fieldsc = $this->createSchemaForViewMethod($schema, $meta, 'index-content');

            return $fieldsc;
        } elseif ($type == 'view-show-content') {
            $fieldsc = $this->createSchemaForViewMethod($schema, $meta, 'show-content');

            return $fieldsc;
        } elseif ($type == 'view-edit-content') {
            $fieldsc = $this->createSchemaForViewMethod($schema, $meta, 'edit-content');

            return $fieldsc;
        } elseif ($type == 'view-create-content') {
            $fieldsc = $this->createSchemaForViewMethod($schema, $meta, 'create-content');

            return $fieldsc;
        } else {
            throw new \Exception('Type not found in syntaxBuilder');
        }
    }

    /**
     * Construct the syntax to add a column.
     *
     * @param string $field
     * @param string $type
     * @param $meta
     *
     * @return string
     */
    private function addColumn($field, $type = 'migration', $meta = '')
    {
        if ($type == 'view-index-header') {
            $syntax = $this->insert($field['name'])->into($this->wrapper('view', '/index/header.stub'), 'field');
        } elseif ($type == 'view-index-content') {
            $syntax = $this->wrapper('view', '/index/content.stub');
            $syntax = $this->insert($meta['var_name'])->into($syntax, 'class');
            $syntax = $this->insert(strtolower($field['name']))->into($syntax, 'field');
        } elseif ($type == 'view-show-content') {
            $syntax = $this->wrapper('view', '/show/content.stub');
            $syntax = $this->insert($meta['var_name'])->into($syntax, 'class');
            $syntax = $this->insert(strtolower($field['name']))->into($syntax, 'field');
            // Fields to show view
        } elseif ($type == 'view-edit-content') {
            $syntax = $this->wrapper('view', '/edit/content.stub');
            $syntax = $this->insert($meta['var_name'])->into($syntax, 'class');
            $syntax = $this->insert(strtolower($field['name']))->into($syntax, 'field');
            // Fields to show view
        } elseif ($type == 'view-create-content') {
            $syntax = $this->wrapper('view', '/create/content.stub');
            $syntax = $this->insert($meta['var_name'])->into($syntax, 'class');
            $syntax = $this->insert(strtolower($field['name']))->into($syntax, 'field');
            // Fields to show view
        } else {
            // Fields to controller
            $syntax = sprintf('$%s->%s = $request->input("%s', $meta['var_name'], $field['name'], $field['name']);
            $syntax .= '");';
        }

        return $syntax;
    }

    /**
     * Construct the controller fields.
     *
     * @param $schema
     * @param $meta
     *
     * @return string
     */
    private function createSchemaForControllerMethod($schema, $meta)
    {
        if (!$schema) {
            return '';
        }

        $fields = array_map(function ($field) use ($meta) {
            return $this->AddColumn($field, 'controller', $meta);
        }, $schema);

        return implode("\n".str_repeat(' ', 8), $fields);
    }

    /**
     * Construct the controller fields.
     *
     * @param $schema
     * @param $meta
     *
     * @return string
     */
    private function createSchemaForModel($schema)
    {
        if (!$schema) {
            return '';
        }

        $fields = [];

        foreach ($schema as $field) {
            $fields[] = $field['name'];
        }

        return implode("', '", $fields);
    }

    /**
     * Construct the view fields.
     *
     * @param $schema
     * @param $meta
     * @param string $type Params 'header' or 'content'
     *
     * @return string
     */
    private function createSchemaForViewMethod($schema, $meta, $type = 'index-header')
    {
        if (!$schema) {
            return '';
        }

        $fields = array_map(function ($field) use ($meta, $type) {
            return $this->AddColumn($field, 'view-'.$type, $meta);
        }, $schema);

        // Format code
        if ($type == 'index-header') {
            return implode("\n".str_repeat(' ', 24), $fields);
        } else {
            // index-content
            return implode("\n".str_repeat(' ', 20), $fields);
        }
    }
}
