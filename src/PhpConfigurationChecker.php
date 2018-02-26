<?php
namespace Lint;

/** Check dynamic configuration change. */
class PhpConfigurationChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/\\bini_set\\s*\\(/', $line)) {
                $this->printError('Do not change PHP configuration dynamically', $line, $index + 1);
            }
        }
    }
}
