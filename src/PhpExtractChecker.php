<?php
namespace Lint;

/** Check use of extract. */
class PhpExtractChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/\\bextract\\s*\\((.*?)\\)/', $line, $match)) {
                $argList = $match[1];
                $argCount = count(explode(',', $argList));
                if ($argCount < 3 || !preg_match('/EXTR_PREFIX_(SAME|ALL)/', $argList)) {
                    $this->printError(
                        'Extract must use prefix with EXTR_PREFIX_(SAME|ALL)',
                        $line,
                        $index + 1
                    );
                }
                if (preg_match('/\\$(_[A-Z]+|GLOBALS)/', $argList)) {
                    $this->printError('Extract must not be used on superglobals', $line, $index + 1);
                }
            }
        }
    }
}
