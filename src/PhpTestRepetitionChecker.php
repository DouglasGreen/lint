<?php
namespace Lint;

/** Check repetition of test conditions. */
class PhpTestRepetitionChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        $counts = [];
        foreach ($lines as $line) {
            if (preg_match('/\\b(if|while)\\s*\\((.*?)\\)\\s*{/i', $line, $match)) {
                $condition = $match[2];
                if (preg_match('/[!<>=]=/', $condition)) {
                    $counts[$condition] = isset($counts[$condition]) ? $counts[$condition] + 1 : 1;
                }
            }
        }
        foreach ($counts as $condition => $count) {
            if ($count >= 3) {
                $message = 'Refactor repeated conditionals into functions (' . $count .
                ' repetitions)';
                $this->printError($message, $condition);
            }
        }
    }
}
