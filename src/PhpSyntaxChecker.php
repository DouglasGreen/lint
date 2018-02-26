<?php
namespace Lint;

/** Check syntax for errors. */
class PhpSyntaxChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $command = sprintf('php -l %s 2> /dev/null', escapeshellarg($this->config->getSourcePath()));
        $output = [];
        exec($command, $output);
        foreach ($output as $line) {
            if (preg_match('/Parse error/', $line)) {
                $this->printError($line);
            }
        }
    }
}
