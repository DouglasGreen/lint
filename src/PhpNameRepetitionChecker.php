<?php
namespace Lint;

/** Check repetition of class names in variable names. */
class PhpNameRepetitionChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $source = $this->parser->getSource();
        if (preg_match('/\\bclass\\s+(\\w+)/i', $source, $match)) {
            $name = $match[1];
            $tokens = $this->parser->getArrayTokens();
            foreach ($tokens as $token) {
                if ($token[0] == T_VARIABLE && preg_match('/^\\$' . $name . '\\w+/i', $token[1])) {
                    $this->printError(
                        'Variable names in classes should not repeat class name',
                        $token[1],
                        $token[2]
                    );
                }
            }
        }
    }
}
