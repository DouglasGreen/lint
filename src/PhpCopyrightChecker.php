<?php
namespace Lint;

/** Check for copyright. */
class PhpCopyrightChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $comments = $this->parser->getComments();
        $end = null;
        if ($comments) {
            $firstComment = $comments[0][1];
            $year = date('Y');
            if (preg_match('/.*Copyright \\d\\d\\d\\d-(\\d\\d\\d\\d).*/', $firstComment, $match)) {
                $line = $match[0];
                $end = $match[1];
                $lineNum = $this->parser->getLineNumber($line);
            } elseif (preg_match('/.*Copyright (\\d\\d\\d\\d).*/', $firstComment, $match)) {
                $line = $match[0];
                $end = $match[1];
                $lineNum = $this->parser->getLineNumber($line);
            }
        }
        if ($end) {
            if ($end != $year) {
                $this->printError('Copyright not up-to-date', $line, $lineNum);
            }
        } else {
            $this->printError('Copyright not found');
        }
    }
}
