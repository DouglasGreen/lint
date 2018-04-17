<?php

namespace Lint;

/** Check with PHPCPD. */
class PhpCopyPasteChecker extends PhpDirChecker
{
    /** Run the check. */
    protected function runCheck()
    {
        $phpcpd = $this->config->getBinaryPath() . '/phpcpd';
        $command = sprintf('%s %s', $phpcpd, escapeshellarg($this->config->getSourcePath()));
        $output = [];
        exec($command, $output);
        $nextLine = null;
        foreach ($output as $index => $line) {
            $nextLine = isset($output[$index + 1]) ? $output[$index + 1] : null;
            if (preg_match('/^\\s*-\\s*(.*):(\\d+-\\d+)/', $line, $match)) {
                $sourceFile = $match[1];
                $sourceRange = $match[2];
                $copiedFrom = $sourceFile . ':' . $sourceRange;
                if (preg_match('/^\\s*(.*):(\\d+-\\d+)/', $nextLine, $match)) {
                    $targetFile = $match[1];
                    $targetRange = $match[2];
                    $copiedTo = '';
                    if ($sourceFile != $targetFile) {
                        $copiedTo = $targetFile . ':';
                    }
                    $copiedTo .= $targetRange;
                    printf("PHPCPD: copied from %s to %s\n", $copiedFrom, $copiedTo);
                }
            }
        }
    }
}
