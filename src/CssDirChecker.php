<?php
namespace Lint;

/** Check directory globally and the files in it individually. */
class CssDirChecker extends Checker
{
    /**
    * Check all the things.
    */
    public function runAllChecks()
    {
        $files = [];
        $sourcePath = $this->config->getSourcePath();
        exec('find ' . escapeshellarg($sourcePath) . ' -name "*.css" -print', $files);
        $allSelectors = [];
        sort($files);

        // Process all file checks.
        foreach ($files as $file) {
            $config = new Config($file);
            $checker = new CssFileChecker($config);
            $checker->runAllChecks();
            $parser = $checker->getParser();
            $document = $parser->getDocument();
            $selectors = $this->getSelectors($document);
            $path = $config->getSourcePath();

            // Check $selectors against current $allSelectors list.
            foreach ($selectors as $text => $lineNum) {
                if (isset($allSelectors[$text])) {
                    $this->printFileError($path, 'Duplicate selector', $text, $lineNum);
                }
            }
            $allSelectors += $selectors;
        }
    }

    /**
     * Get the selectors.
     *
     * @param Document $document
     *
     * @return index
     */
    protected function getSelectors(Document $document)
    {
        $blocks = $document->getAllDeclarationBlocks();
        $texts = [];
        foreach ($blocks as $block) {
            $lineNum = $block->getLineNo();
            $selectors = $block->getSelectors();
            foreach ($selectors as $selector) {
                $text = $selector->getSelector();
                $texts[$text] = $lineNum;
            }
        }
        return $texts;
    }
}
