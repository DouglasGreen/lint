<?php
namespace Lint;

/** Handling parsing of CSS. */
class CssParser extends Parser
{
    /** @var array Lines of source code with comments */
    protected $allLines = [];

    /** @var Document Parsed Sabberworm document */
    protected $document;

    /** @var array Lines of source code without comments */
    protected $lines = [];

    /** @var string Source code without comments */
    protected $source;

    /**
     * Construct various parsing methods.
     *
     * @param string $source
     */
    public function __construct($source)
    {
        $this->allLines = explode("\n", $source);
        $parser = new \Sabberworm\CSS\Parser($source);
        $this->document = $parser->parse();
        $this->source = self::stripComments($source);
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
     * Get the document.
     *
     * @return Document
     */
    public function getDocument()
    {
        return $this->document;
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
     * Get source code without comments.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Strip comments from source code.
     *
     * @param string $source
     *
     * @return string
     */
    protected static function stripComments($source)
    {
        if (preg_match_all('~/\\*.*?\\*/~s', $source, $matches)) {
            foreach ($matches[0] as $comment) {
                $comment = preg_replace('/[^\\n]/', '', $comment);
                $source = str_replace($comment, '', $source);
            }
        }
        return $source;
    }
}
