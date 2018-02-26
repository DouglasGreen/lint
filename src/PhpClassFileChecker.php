<?php
namespace Lint;

/** Check class file name. */
class PhpClassFileChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $tokens = $this->parser->getTokens();
        foreach ($tokens as $index => $token) {
            if (!is_array($token)) {
                continue;
            }
            if ($token[0] != T_CLASS && $token[0] != T_INTERFACE && $token[0] != T_TRAIT) {
                continue;
            }
            if (!isset($tokens[$index + 1])) {
                continue;
            }
            $nextToken = $tokens[$index + 1];
            if ($nextToken[0] != T_STRING) {
                continue;
            }
            $name = $nextToken[1];
            $basename = basename($this->config->getSourcePath(), '.php');
            if ($name != $basename) {
                $message = 'Name of file should be same as name of class, interface, or trait';
                $this->printError($message, $token[1] . ' ' . $name, $token[2]);
            }
        }
    }
}
