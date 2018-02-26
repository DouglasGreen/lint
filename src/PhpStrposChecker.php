<?php
namespace Lint;

/** Check strpos return values to make sure === is used. */
class PhpStrposChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/\\bstr\\w*pos\\s*\\(/', $line) && !preg_match('/[!=]==/', $line)) {
                $this->printError(
                    'Return value of strpos() functions should be checked with ===',
                    $line,
                    $index + 1
                );
            }
        }
    }
}
