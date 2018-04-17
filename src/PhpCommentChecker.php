<?php
namespace Lint;

/** Check comments. */
class PhpCommentChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $prevLine = null;
        $allLines = $this->parser->getAllLines();
        $lastComment = null;
        $codeLineCount = 0;
        foreach ($allLines as $index => $line) {
            $hasComment = preg_match('~^\\s*/[/*]~', $line);
            $hasDecision = preg_match('/^\s*(if|for|foreach|switch|while)\s*\(/i', $line);
            if ($hasComment && $hasDecision) {
                if ($codeLineCount > 10) {
                    $expr = sprintf('lines %d-%d', $lastComment + 1, $index + 1);
                    $this->printError('Break up long blocks of code with comments at decision points', $expr);
                }
                $codeLineCount = 0;
                $lastComment = $index;
            }
            if (preg_match('/;\\s*$/', $line)) {
                $codeLineCount++;
            }
            if (!preg_match('~\\*/~', $prevLine)) {
                if (PhpText::isClassLine($line)) {
                    $this->printError(
                        'Classes, interfaces, and traits need docblock comments',
                        $line,
                        $index + 1
                    );
                }
                if (PhpText::isFunctionLine($line)) {
                    $this->printError('Functions need docblock comments', $line, $index + 1);
                }
                if (PhpText::isPropertyLine($line)) {
                    $this->printError('Class properties need docblock comments', $line, $index + 1);
                }
                if (PhpText::isConstLine($line)) {
                    $this->printError('Class constants need docblock comments', $line, $index + 1);
                }
            }
            $prevLine = $line;
        }
    }
}
