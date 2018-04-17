<?php

namespace Lint;

/** Check law of Demeter */
class PhpLawOfDemeterChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            // Law of Demeter permits accessing direct component objects.
            $line = preg_replace('/\\$this->/', '', $line);
            if (preg_match('/->\\w+->/', $line)) {
                $this->printError('Don\'t violate the Law of Demeter', $line, $index + 1);
            }
            if (preg_match('/^\\s*->/', $line)) {
                $this->printError('Don\'t break method calls between lines', $line, $index + 1);
            }
        }
    }
}
