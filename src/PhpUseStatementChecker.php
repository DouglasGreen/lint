<?php
namespace Lint;

/** Check use statements. */
class PhpUseStatementChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        $source = $this->parser->getSource();
        foreach ($lines as $index => $line) {
            if (preg_match('/^\\s*use\\s+(.*\\w)\\s*;/', $line, $match)) {
                $useStmt = preg_replace('~.*\\\\~', '', $match[1]);
                $useStmt = preg_replace('/.* as /i', '', $useStmt);
                $source = str_replace($match[0], '', $source);
                if (!preg_match('/\\b' . $useStmt . '\\b/', $source)) {
                    $this->printError(
                        'Identifier declared in use statement and not used',
                        $line,
                        $index + 1
                    );
                }
            }
        }
    }
}
