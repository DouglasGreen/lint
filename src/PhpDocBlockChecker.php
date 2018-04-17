<?php

namespace Lint;

/** Check docblocks. */
class PhpDocBlockChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        // These tags are presented in the order they should appear in docblocks.
        // DO NOT CHANGE ORDER.
        $validTags = [
            'package',
            'author',
            'copyright',
            'license',
            'version',
            'api',
            'internal',
            'method',
            'deprecated',
            'since',
            'todo',
            'see',
            'example',
            'var',
            'property',
            'throws',
            'global',
            'uses',
            'param',
            'return'
        ];
        $comments = $this->parser->getComments();
        foreach ($comments as $comment) {
            $docBlock = $comment[1];
            $lineNum = $this->parser->getLineNumber($docBlock);
            $docBlockLines = explode("\n", $docBlock);
            $prevOrder = null;
            foreach ($docBlockLines as $line) {
                if (preg_match('/\\s*\\*\\s*@(\\w+)/', $line, $match)) {
                    $tag = $match[1];
                    if (in_array($tag, $validTags)) {
                        $keys = array_keys($validTags, $tag);
                        $order = $keys[0];
                        if ($order < $prevOrder) {
                            $this->printError('Docblock tag out of preferred order', $line, $lineNum);
                        }
                        $prevOrder = $order;
                    } else {
                        $this->printError('Invalid docblock tag', $line, $lineNum);
                    }
                }
            }
        }
    }
}
