<?php

namespace Lint;

/** Check directory globally and the files in it individually. */
class PhpDirChecker extends PhpChecker
{
    /**
     * Check all the things.
     */
    public function runAllChecks()
    {
        $files = [];
        exec('find ' . escapeshellarg($this->config->getSourcePath()) . ' -name "*.php" -print', $files);
        $codeNames = [];
        $codeRefs = [];
        sort($files);
        foreach ($files as $file) {
            $config = new Config($file);
            $checker = new PhpFileChecker($config);

            // The results of each file check contain code names and references to accumulate.
            $results = $checker->runAllChecks();
            $codeNames = array_merge($codeNames, $results['names']);
            foreach ($results['references'] as $reference => $count) {
                if (isset($codeRefs[$reference])) {
                    $codeRefs[$reference] = $codeRefs[$reference] + $count;
                } else {
                    $codeRefs[$reference] = $count;
                }
            }
        }
        $copyPasteChecker = new PhpCopyPasteChecker($this->config);
        $copyPasteChecker->runCheck();
        $referenceChecker = new PhpReferenceChecker($this->config);
        $referenceChecker->runCheck($codeNames, $codeRefs);
    }
}
