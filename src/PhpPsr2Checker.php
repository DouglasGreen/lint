<?php

namespace Lint;

/** Check PSR2 with PHP_CodeSniffer. */
class PhpPsr2Checker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $phpcs = $this->config->getBinaryPath() . '/phpcs';
        $command = sprintf('%s --standard=PSR2 %s', $phpcs, escapeshellarg($this->config->getSourcePath()));
        $output = [];
        exec($command, $output);
        foreach ($output as $line) {
            if (preg_match('/^\\s*(.*) \\| (WARNING|ERROR) \\| \\[.\\] (.*)/', $line, $match)) {
                $lineNum = $match[1];
                $type = $match[2];
                $warning = $match[3];
                printf("PSR2: %s:%s %s = %s\n", $this->config->getSourcePath(), $lineNum, $type, $warning);
            }
        }
    }
}
