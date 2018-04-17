<?php

namespace Lint;

/** Check local variables. */
class PhpLocalVariableChecker extends PhpFileChecker
{
    /** @var int Minimum variable name length */
    const MIN_LEN = 3;

    /** @var int Maximum variable name length */
    const MAX_LEN = 20;

    /** Run the check. */
    public function runCheck()
    {
        $functions = $this->parser->getFunctions();
        $propertyNames = $this->parser->getPropertyNames();
        foreach ($functions as $index => $funcLines) {
            $varNames = [];
            $funcName = null;
            foreach ($funcLines as $line) {
                if (!$funcName && PhpText::isFunctionLine($line)) {
                    preg_match('/\\bfunction\\s+(\\w+)/i', $line, $match);
                    $funcName = $match[1];
                }
                if (preg_match_all('/\\$(\\w+)/', $line, $matches)) {
                    foreach ($matches[1] as $varName) {
                        $short = $this->makeShort($varName);
                        $varNames[$varName] = $short;
                    }
                }
            }
            foreach ($varNames as $varName => $short) {
                if (strlen($varName) < self::MIN_LEN || strlen($varName) > self::MAX_LEN) {
                    $message = sprintf('Variable names should be between %d and %d characters', self::MIN_LEN, self::MAX_LEN);
                    $this->printError($message, $varName, $index + 1);
                }
                $isUsed = isset($varNames[$short]);
                $isAmbiguous = count(array_keys($varNames, $short)) > 1;
                $tooShort = strlen($short) < self::MIN_LEN;
                $isKeyword = PhpText::isKeyword($short);
                $isPropertyName = in_array($varName, $propertyNames);
                $isGlobal = preg_match('/^_[A-Z]+|GLOBALS/', $varName);
                if (!$isUsed && !$isAmbiguous && !$tooShort && !$isKeyword && !$isPropertyName && !$isGlobal) {
                    $message = sprintf('Can shorten $%s to $%s in function %s()', $varName, $short, $funcName);
                    $this->printError($message, '', $index + 1);
                }
            }
        }
    }

    /**
     * Make the shorter version of a variable name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function makeShort($name)
    {
        $parts = array_map('strtolower', PhpText::splitIdentifier($name));
        $count = count($parts);
        if ($count == 1) {
            return $parts[0];
        }
        $first = $parts[0];
        $last = $parts[$count - 1];

        // Allow 'is' or 'has' to start a name.
        if ($first == 'is' || $first == 'has') {
            $short = $first . ucfirst($last);
            return $short;
        }
        return $last;
    }
}
