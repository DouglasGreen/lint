<?php

namespace Lint;

/** Check return statement. */
class PhpReturnChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/^\\s*return\\s+(.*)/', $line, $match)) {
                $value = $match[1];
                if (!preg_match('/^\\S+;$/', $value)) {
                    $this->printError('Complex return value', $value, $index + 1);
                }
            }
        }
    }
}
