<?php

namespace Lint;

/** Check indentation of blocks and arrays. */
class PhpIndentChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        $count = count($lines);
        for ($index = 0; $index < $count - 1; $index++) {
            $line = $lines[$index];
            $nextIndex = $index + 1;
            $nextLine = $lines[$nextIndex];
            if (preg_match('/[({:]\\s*$/', $line) && !preg_match('/:\\s*$/', $nextLine)) {
                preg_match('/^\\s*/', $line, $match);
                $indent = $match[0];
                if ($index < $count) {
                    preg_match('/^\\s*/', $nextLine, $match);
                    $nextIndent = $match[0];
                    if ($nextIndent != $indent . '    ') {
                        $this->printError('Bad indentation', $nextLine, $nextIndex + 1);
                    }
                }
            }
        }
    }
}
