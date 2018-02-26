<?php
namespace Lint;

/** Check PHP open tags. */
class PhpOpenTagChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $tokens = $this->parser->getTokens();
        foreach ($tokens as $token) {
            if (is_array($token)) {
                if ($token[0] == T_OPEN_TAG) {
                    if (trim($token[1]) != '<?php') {
                        $this->printError('Use <?php for open tag', $token[1], $token[2]);
                    }
                } elseif ($token[0] == T_OPEN_TAG_WITH_ECHO) {
                    if (trim($token[1]) != '<?=') {
                        $this->printError('Use <?= for open tag with echo', $token[1], $token[2]);
                    }
                }
            }
        }
    }
}
