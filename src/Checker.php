<?php

namespace Lint;

/** Base checker class */
class Checker
{
    /** @var Config Program configuration */
    protected $config;

    /** @var Parser|null Parser of source code */
    protected $parser;

    /**
     * Set the program configuration.
     *
     * @param Config $config
     * @param Parser|null $parser
     */
    public function __construct(Config $config, Parser $parser = null)
    {
        $this->config = $config;
        $this->parser = $parser;
    }

    /**
     * Format code for output.
     *
     * @param string $code
     *
     * @return string
     */
    protected static function formatCode($code)
    {
        if ($code) {
            $neat = trim(preg_replace('/[ \\t]+/', ' ', $code));
            $neat = str_replace("\n", '\\n', $neat);
            if (strlen($neat) > 120) {
                $neat = substr($neat, 0, 100) . ' ...';
            }
            $output = ' - ' . $neat;
        } else {
            $output = '';
        }
        return $output;
    }

    /**
     * Print an error message.
     *
     * @param string $message
     * @param string|null $code
     * @param int|null $lineNum
     */
    protected function printError($message, $code = null, $lineNum = null)
    {
        $output = self::formatCode($code);
        printf("%s:%s %s%s\n", $this->config->getSourcePath(), $lineNum, $message, $output);
    }

    /**
     * Print an error message for a specified file.
     *
     * @param string $file
     * @param string $message
     * @param string|null $code
     * @param int|null $lineNum
     */
    protected function printFileError($file, $message, $code = null, $lineNum = null)
    {
        $output = self::formatCode($code);
        printf("%s:%s %s%s\n", $file, $lineNum, $message, $output);
    }
}
