<?php

namespace Lint;

/** Check selectors. */
class CssRulesetChecker extends CssFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $document = $this->parser->getDocument();
        $rulesets = $document->getAllRulesets();
        foreach ($rulesets as $ruleset) {
            $lineNum = $ruleset->getLineNo();
            $rules = $ruleset->getRules();
            foreach ($rules as $rule) {
                $text = (string) $rule;
                if ($rule->getIsImportant()) {
                    $this->printError('Avoid marking rules important', $text, $lineNum);
                }
            }
        }
    }
}
