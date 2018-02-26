<?php
namespace Lint;

/** Check include and require statements. */
class PhpIncludeChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/^\\s*(include|include_once|require)\\s*[\'"(]/', $line)) {
                $this->printError(
                    'Use require_once instead of include, include_once, or require',
                    $line,
                    $index + 1
                );
            }
        }
    }
}
