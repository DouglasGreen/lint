<?php

namespace Lint;

/** Check exceptions. */
class PhpExceptionChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $source = $this->parser->getSource();
        if (preg_match_all('/\\bcatch\\s*\\(.*?\\)\\s*{\\s*}/s', $source)) {
            $this->printError('Avoid empty catch blocks');
        }
    }
}
