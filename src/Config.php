<?php
namespace Lint;

/** Manage program configuration. */
class Config
{
    /** @var string Path to source file or directory */
    protected $sourcePath;

    /** @var array Verbs that are OK to use in functions. */
    protected $verbs = [];

    /**
     * Set the source path.
     *
     * @param string $sourcePath
     */
    public function __construct($sourcePath)
    {
        $this->sourcePath = $sourcePath;
        $this->loadVerbs();
    }

    /**
     * Get the path to the binary files.
     *
     * @throws Exception
     *
     * @return string
     */
    public function getBinaryPath()
    {
        $path = realpath(__DIR__ . '/../vendor/bin');
        if (!$path) {
            throw new \Exception('Path to binary files not found. Did you run "composer install"?');
        }
        return $path;
    }

    /**
     * Get the path to the data files.
     *
     * @throws Exception
     *
     * @return string
     */
    public function getDataPath()
    {
        $path = realpath(__DIR__ . '/../data');
        if (!$path) {
            throw new \Exception('Data path not found.');
        }
        return $path;
    }

    /**
     * Get the path to the source files.
     *
     * @throws Exception
     *
     * @return string
     */
    public function getSourcePath()
    {
        $path = realpath($this->sourcePath);
        if (!$path) {
            throw new \Exception('Source path not found.');
        }
        return $path;
    }

    /**
     * Get the verbs.
     *
     * @return array
     */
    public function getVerbs()
    {
        return $this->verbs;
    }

    /**
     * Load the verbs.
     *
     * @return array
     */
    protected function loadVerbs()
    {
        $handle = fopen($this->getDataPath() . '/verbs.csv', 'r');
        while ($line = fgets($handle, 32)) {
            $word = trim($line);
            if ($word) {
                $this->verbs[$word] = true;
            }
        }
        return $this->verbs;
    }
}
