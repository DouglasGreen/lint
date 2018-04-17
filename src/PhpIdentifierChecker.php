<?php

namespace Lint;

/** Check identifiers. */
class PhpIdentifierChecker extends PhpFileChecker
{
    /** @var int Minimum variable name length */
    const MIN_LEN = 4;

    /** @var int Maximum variable name length */
    const MAX_LEN = 20;

    /**
     * Run the check.
     *
     * @return array
     */
    public function runCheck()
    {
        $results = [];
        $results['names'] = $this->getCodeNames();
        $results['references'] = $this->getCodeReferences();
        return $results;
    }

    /**
     * Get class and function declarations.
     *
     * @return array
     */
    protected function getCodeNames()
    {
        $names = [];

        // Get class declarations.
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/^\\s*(class|trait|interface)\\s+(\\w+)/i', $line, $match)) {
                $type = strtolower($match[1]);
                $name = $match[2];
                $names[$name] = $type;
                if (strlen($name) < self::MIN_LEN || strlen($name) > self::MAX_LEN) {
                    $message = sprintf('Class names should be between %d and %d characters', self::MIN_LEN, self::MAX_LEN);
                    $this->printError($message, $line, $index + 1);
                }
            }
        }

        // Get function declarations.
        $funcLines = $this->parser->getFunctionLines();
        foreach ($funcLines as $index => $line) {
            if (preg_match('/\\bfunction\\s+(\\w+)/i', $line, $match)) {
                $name = $match[1];
                $names[$name] = 'function';
                if (strlen($name) < self::MIN_LEN || strlen($name) > self::MAX_LEN) {
                    $message = sprintf('Function names should be between %d and %d characters', self::MIN_LEN, self::MAX_LEN);
                    $this->printError($message, $line, $index + 1);
                }
            }
        }
        return $names;
    }

    /**
     * Get class and function references.
     *
     * @return array
     */
    protected function getCodeReferences()
    {
        $refs = [];

        // Get class definitions.
        $lines = $this->parser->getLines();
        foreach ($lines as $line) {
            if (preg_match('/^\\s*class.*\\bextends\\s+(\\w+)/i', $line, $match)) {
                $ref = $match[1];
                $refs[$ref] = isset($refs[$ref]) ? $refs[$ref] + 1 : 1;
            }
            if (preg_match('/^\\s*class.*\\bimplements\\s+(.*)/i', $line, $match)) {
                preg_match_all('/\\w+/', $match[1], $matches);
                foreach ($matches[0] as $ref) {
                    $refs[$ref] = isset($refs[$ref]) ? $refs[$ref] + 1 : 1;
                }
            }
            if (preg_match_all('/new\\s+(\\w+)\\s*\\(/i', $line, $matches)) {
                foreach ($matches[1] as $ref) {
                    $refs[$ref] = isset($refs[$ref]) ? $refs[$ref] + 1 : 1;
                }
            }
            if (preg_match_all('/(\\w+)::\\w+\\s*\\(/i', $line, $matches)) {
                foreach ($matches[1] as $ref) {
                    $refs[$ref] = isset($refs[$ref]) ? $refs[$ref] + 1 : 1;
                }
            }
            $line = preg_replace('/\\bfunction\\s+(\\w+)/i', '', $line);
            if (preg_match_all('/(\\w+)\\s*\\(/i', $line, $matches)) {
                foreach ($matches[1] as $ref) {
                    $refs[$ref] = isset($refs[$ref]) ? $refs[$ref] + 1 : 1;
                }
            }
        }
        return $refs;
    }
}
