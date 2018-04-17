<?php

namespace Lint;

/** Check heredoc names. */
class PhpHeredocChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        // HTML and SQL do syntax highlighting. Everything else should be TEXT.
        $validHeredocs = [
            'HTML',
            'SQL',
            'TEXT'
        ];
        $tokens = $this->parser->getTokens();
        foreach ($tokens as $token) {
            if (is_array($token) && $token[0] == T_END_HEREDOC) {
                if (!in_array($token[1], $validHeredocs)) {
                    $this->printError('Invalid heredoc name', $token[1], $token[2]);
                }
            }
        }
    }
}
