<?php
namespace Lint;

/** Handling parsing of PHP. */
class PhpParser extends Parser
{
    /** @var array Lines of source code with comments */
    protected $allLines = [];

    /** @var array All tokens */
    protected $allTokens = [];

    /** @var array Array tokens */
    protected $arrayTokens = [];

    /** @var array Comments */
    protected $comments = [];

    /** @var array Lines of source code without comments */
    protected $lines = [];

    /** @var string Source code without comments */
    protected $source;

    /** @var array Tokens without whitespace or comments */
    protected $tokens = [];

    /**
     * Construct various parsing methods.
     *
     * @param string $source
     */
    public function __construct($source)
    {
        $this->allLines = explode("\n", $source);
        $this->allTokens = token_get_all($source);
        $this->source = '';
        foreach ($this->allTokens as $token) {
            if (is_array($token)) {
                $this->arrayTokens[] = $token;
            }
            if (PhpToken::isComment($token)) {
                $this->comments[] = $token;

                // Strip comments from source but leave line count intact.
                $this->source .= preg_replace('/[^\\n]/', '', $token[1]);
            } else {
                if (is_array($token)) {
                    $this->source .= $token[1];
                } else {
                    $this->source .= $token;
                }
                if (!PhpToken::isWhitespace($token)) {
                    $this->tokens[] = $token;
                }
            }
        }
        $this->lines = explode("\n", $this->source);
    }

    /**
     * Get all lines.
     *
     * @return array
     */
    public function getAllLines()
    {
        return $this->allLines;
    }

    /**
     * Get all tokens.
     *
     * @return array
     */
    public function getAllTokens()
    {
        return $this->allTokens;
    }

    /**
     * Get tokens that are arrays.
     *
     * @return array
     */
    public function getArrayTokens()
    {
        return $this->arrayTokens;
    }

    /**
     * Get comment tokens.
     *
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Get function declaration lines.
     *
     * @return array
     */
    public function getFunctionLines()
    {
        $lines = [];
        foreach ($this->lines as $index => $line) {
            if (PhpText::isFunctionLine($line)) {
                $lines[$index] = $line;
            }
        }
        return $lines;
    }

    /**
     * Get function definitions.
     *
     * @return array
     */
    public function getFunctions()
    {
        $lines = $this->getFunctionLines();

        // Find the start of each function, including preceding comment.
        $starts = [];
        foreach (array_keys($lines) as $index) {
            $start = $index;
            while ($start > 0 && preg_match('~^\\s*/?\\*~', $this->allLines[$start - 1])) {
                $start--;
            }
            $starts[] = $start;
        }

        // Split the functions up from the start of one function to the next.
        $count = count($starts);
        $functions = [];
        foreach ($starts as $startIndex => $start) {
            if ($startIndex == $count - 1) {
                $end = count($this->allLines) - 1;
            } else {
                $end = $starts[$startIndex + 1] - 1;
            }
            $length = $end - $start + 1;
            $func = array_slice($this->allLines, $start, $length, true);
            $functions[$start] = $func;
        }
        return $functions;
    }

    /**
     * Get the line number of a snippet inside the PHP source.
     *
     * @param string $snippet
     *
     * @return int
     */
    public function getLineNumber($snippet)
    {
        $lineNum = Text::getLineNumber($this->source, $snippet);
        return $lineNum;
    }

    /**
     * Get lines of source code without comments.
     *
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Get property declaration lines.
     *
     * @return array
     */
    public function getPropertyLines()
    {
        $lines = [];
        foreach ($this->lines as $index => $line) {
            if (PhpText::isPropertyLine($line)) {
                $lines[$index] = $line;
            }
        }
        return $lines;
    }

    /**
     * Get the names of properties.
     *
     * @return array
     */
    public function getPropertyNames()
    {
        $lines = $this->getPropertyLines();
        $names = [];
        foreach ($lines as $line) {
            preg_match('/\$(\w+)/', $line, $match);
            $names[] = $match[1];
        }
        return $names;
    }

    /**
     * Get source code without comments.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get tokens without whitespace or comments.
     *
     * @return array
     */
    public function getTokens()
    {
        return $this->tokens;
    }
}
