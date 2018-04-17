<?php

namespace Lint;

/** Check namespaces. */
class PhpNamespaceChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $source = $this->parser->getSource();
        if (!preg_match('/namespace/', $source)) {
            $this->printError('No namespace is declared');
        }
        $lines = $this->parser->getLines();
        foreach ($lines as $index => $line) {
            $line = preg_replace('/^\\s*use\\b.*/', '', $line);
            if (preg_match('/\\\\\\w+(\\\\\\w+)+\\(/', $line)) {
                $this->printError('Declare namespace in use statement', $line, $index + 1);
            }
        }
    }
}
