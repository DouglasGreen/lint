<?php

namespace Lint;

/** Check complexity of test conditions. */
class PhpTestConditionChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $source = $this->parser->getSource();
        if (preg_match_all('/\\b(if|while)\\s*\\((.*?)\\)\\s*{/is', $source, $matches)) {
            foreach ($matches[2] as $index => $condition) {
                $code = $matches[0][$index];
                $lineNum = $this->parser->getLineNumber($code);
                $statement = $matches[1][$index];
                $opCount = substr_count($condition, '&&');
                $opCount += substr_count($condition, '||');
                if ($opCount > 2) {
                    $this->printError('Use boolean variables or function calls to simplify complex conditionals', $condition, $lineNum);
                }
                if ($statement == 'if') {
                    $test = preg_replace('/[!<>=]=+/', '', $condition);
                    if (preg_match('/=/', $test)) {
                        $message = 'Move = statements out of if conditions to avoid confusion with ==';
                        $this->printError($message, $code, $lineNum);
                    }
                }
            }
        }
    }
}
