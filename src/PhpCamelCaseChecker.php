<?php

namespace Lint;

/** Check camel case. */
class PhpCamelCaseChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $tokens = $this->parser->getTokens();
        $prevToken = null;
        foreach ($tokens as $token) {
            if (is_array($token)) {
                $this->checkCase($token, $prevToken);
            }
            $prevToken = $token;
        }
    }

    /**
     * Perform case checks.
     *
     * @param array $token
     * @param array|string|null $prevToken
     */
    protected function checkCase(array $token, $prevToken)
    {
        if ($token[0] == T_VARIABLE) {
            if (!PhpText::isCamelCase($token[1])) {
                $this->printError('Not camel case', $token[1], $token[2]);
            }
        } elseif (is_array($prevToken) && $token[0] == T_STRING) {
            switch ($prevToken[0]) {
                case T_CLASS:
                case T_NAMESPACE:
                case T_NS_SEPARATOR:
                case T_TRAIT:
                    if (!PhpText::isInitialCaps($token[1])) {
                        $this->printError('Not initial caps', $token[1], $token[2]);
                    }
                    break;
                case T_CONST:
                    if (!PhpText::isUpperCase($token[1])) {
                        $this->printError('Not upper case', $token[1], $token[2]);
                    }
                    break;
            }
        }
    }
}
