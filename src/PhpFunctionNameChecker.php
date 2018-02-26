<?php
namespace Lint;

/** Check function name. */
class PhpFunctionNameChecker extends PhpFileChecker
{
    /** @var array Articles. */
    protected static $articles = [
        'a',
        'an',
        'the'
    ];

    /** @var array Conjunctions. */
    protected static $conjunctions = [
        'and',
        'or',
        'not'
    ];

    /** Run the check. */
    public function runCheck()
    {
        $verbs = $this->config->getVerbs();
        $lines = $this->parser->getFunctionLines();
        foreach ($lines as $index => $line) {
            if (preg_match('/function\\s+(\\w+)/', $line, $match)) {
                $func = $match[1];
                $words = array_map('strtolower', PhpText::splitIdentifier($func));
                $verb = $words[0];
                if (!isset($verbs[$verb])) {
                    $message = 'Function verb ' . $verb . ' not recognized';
                    $this->printError($message, $func, $index + 1);
                }
                $lineNum = $index + 1;
                $this->checkBadWords($words, self::$articles, $func, $lineNum);
                $this->checkBadWords($words, self::$conjunctions, $func, $lineNum);
            }
        }
    }

    /**
     * Check the bad words.
     *
     * @param array $words
     * @param array $badWords
     * @param string $func
     * @param int $lineNum
     */
    protected function checkBadWords(array $words, array $badWords, $func, $lineNum)
    {
        if (array_intersect($words, $badWords)) {
            $expr = implode('/', $badWords);
            $this->printError(
                'Avoid the words ' . $expr . ' in function names',
                $func,
                $lineNum
            );
        }
    }
}
