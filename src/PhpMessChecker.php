<?php

namespace Lint;

/** Check with PHPMD. */
class PhpMessChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $phpmd = $this->config->getBinaryPath() . '/phpmd';
        $command = sprintf('%s %s text cleancode,codesize,controversial,design,naming,unusedcode', $phpmd, escapeshellarg($this->config->getSourcePath()));
        $output = [];
        exec($command, $output);
        foreach ($output as $line) {
            if (strpos($line, 'Else is never necessary') !== false) {
                continue;
            }
            if (preg_match('/accesses the super-global variable .*(SERVER|SESSION)/', $line)) {
                continue;
            }
            if (preg_match('/Avoid using static access to class/', $line)) {
                continue;
            }
            echo 'PHPMD: ' . $line . "\n";
        }
    }
}
