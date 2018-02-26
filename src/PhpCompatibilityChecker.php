<?php
namespace Lint;

/** Check compatibility with PHP_CodeSniffer. */
class PhpCompatibilityChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $phpcs = $this->config->getBinaryPath() . '/phpcs';
        $command = sprintf(
            '%s --standard=PHPCompatibility --runtime-set testVersion %s %s',
            $phpcs,
            self::VERSION,
            escapeshellarg($this->config->getSourcePath())
        );
        $output = [];
        exec($command, $output);
        foreach ($output as $line) {
            if (preg_match('/^\\s*(.*) \\| (WARNING|ERROR) \\| (.*)/', $line, $match)) {
                $lineNum = $match[1];
                $type = $match[2];
                $warning = $match[3];
                printf(
                    "PHP %s: %s:%s %s = %s\n",
                    self::VERSION,
                    $this->config->getSourcePath(),
                    $lineNum,
                    $type,
                    $warning
                );
            }
        }
    }
}
