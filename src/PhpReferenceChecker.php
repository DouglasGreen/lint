<?php

namespace Lint;

/** Check php case matching between definition and reference. */
class PhpReferenceChecker extends PhpDirChecker
{
    /** @var array Code names */
    protected $codeNames;

    /** @var array Code references */
    protected $codeReferences;

    /**
     * Check problems with references.
     *
     * @param array $codeNames
     * @param array $codeReferences
     */
    public function runCheck(array $codeNames, array $codeReferences)
    {
        $this->codeNames = $codeNames;
        $this->codeReferences = $codeReferences;
        $this->checkCaseMatching();
        $this->checkNameSimilarity();
        $this->checkUnused();
    }

    /**
     * Check code case matching.
     */
    protected function checkCaseMatching()
    {
        $names = array_keys($this->codeNames);
        $lowNames = array_map('strtolower', $names);
        foreach (array_keys($this->codeReferences) as $name) {
            $lowName = strtolower($name);
            if (in_array($lowName, $lowNames) && !in_array($name, $names)) {
                foreach ($this->codeNames as $codeName => $type) {
                    if ($lowName == strtolower($codeName)) {
                        printf("Case of %s %s does not match declaration %s\n", $type, $name, $codeName);
                        break;
                    }
                }
            }
        }
    }

    /**
     * Check name similarity.
     */
    protected function checkNameSimilarity()
    {
        $names = array_keys($this->codeNames);
        $count = count($names);
        for ($index1 = 0; $index1 < $count; $index1++) {
            $name1 = $names[$index1];
            $type1 = $this->codeNames[$name1];
            for ($index2 = $index1 + 1; $index2 < $count; $index2++) {
                $name2 = $names[$index2];
                $type2 = $this->codeNames[$name2];
                $this->checkSimilarity($name1, $type1, $name2, $type2);
            }
        }
    }

    /**
     * Check similarity between pairs of names and types.
     *
     * @param string $name1
     * @param string $type1
     * @param string $name2
     * @param string $type2
     */
    protected function checkSimilarity($name1, $type1, $name2, $type2)
    {
        // Short name errors were already reported.
        if (strlen($name1) < 4 || strlen($name2) < 4) {
            return;
        }

        // Don't check magic functions.
        if (preg_match('/^__/', $name1) || preg_match('/^__/', $name2)) {
            return;
        }
        $lowName1 = strtolower($name1);
        $lowName2 = strtolower($name2);

        // Allow getter and setter functions to have similar names.
        $isGetter1 = preg_match('/^[gs]et(\\w+)/', $lowName1, $match1);
        $isGetter2 = preg_match('/^[gs]et(\\w+)/', $lowName2, $match2);
        if ($isGetter1 && $isGetter2 && $match1[1] == $match2[1]) {
            return;
        }

        // Use levenshtein to check for string difference, ignoring case.
        if (levenshtein($lowName1, $lowName2) < 2) {
            // Allow singular and plural functions to have similar names.
            if ($lowName1 == $lowName2 . 's' || $lowName2 == $lowName1 . 's') {
                return;
            }
            printf("The names of %s %s and %s %s are too similar\n", $type1, $name1, $type2, $name2);
        }
    }

    /**
     * Check for names with no references.
     */
    protected function checkUnused()
    {
        foreach ($this->codeNames as $name => $type) {
            if (!isset($this->codeReferences[$name]) && !preg_match('/^__/', $name)) {
                printf("No reference found to %s %s\n", $type, $name);
            }
        }
    }
}
