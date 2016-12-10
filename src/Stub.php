<?php

namespace Eilander\Generators;

/**
 * Class Stub.
 */
class Stub
{
    /**
     * The stub path.
     *
     * @var string
     */
    protected $path;
    /**
     * The replacements array.
     *
     * @var array
     */
    protected $replaces = [];
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
     * The contructor.
     *
     * @param string $path
     * @param array  $replaces
     */
    public function __construct($path, array $replaces = [], $replacementStart = '{{', $replacementEnd = '}}')
    {
        $this->path = $path;
        $this->replaces = $replaces;
        $this->replacementStart = $replacementStart;
        $this->replacementEnd = $replacementEnd;
    }

    /**
     * Get stub contents.
     *
     * @return mixed|string
     */
    public function getContents()
    {
        $contents = file_get_contents($this->path);
        foreach ($this->replaces as $search => $replace) {
            $contents = str_replace($this->replacementStart.$search.$this->replacementEnd, $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get stub contents.
     *
     * @return string
     */
    public function render()
    {
        return $this->getContents();
    }
}
