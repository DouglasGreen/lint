<?php
namespace Lint;

/** Check selectors. */
class CssMediaQueryChecker extends CssFileChecker
{
    /**
    * @see https://medium.freecodecamp.org/the-100-correct-way-to-do-css-breakpoints-88d6a5ba1862
    *
    * @var array Recommended media minimum breakpoints
    */
    protected $minBreakpoints = [
        600,
        900,
        1200,
        1500,
        1800
    ];

    /** Run the check. */
    public function runCheck()
    {
        $document = $this->parser->getDocument();
        $contents = $document->getContents();
        $maxBreakpoints = $this->getMaxBreakpoints();
        foreach ($contents as $content) {
            // There doesn't seem to be a convenience function to use in place of instanceof.
            if ($content instanceof \Sabberworm\CSS\CSSList\AtRuleBlockList) {
                $atRuleName = $content->atRuleName();
                if (strtolower($atRuleName) == 'media') {
                    $atRuleArgs = $content->atRuleArgs();
                    $lineNum = $content->getLineNo();
                    if (preg_match_all('/(min|max)-width\\s*:\\s*(\\S+)/i', $atRuleArgs, $matches)) {
                        foreach ($matches[1] as $index => $minOrMax) {
                            $breakDesc = strtolower($minOrMax);
                            $isMin = $breakDesc == 'min';
                            $breakpoints = $isMin ? $this->minBreakpoints : $maxBreakpoints;
                            $widthDesc = $matches[2][$index];
                            if (preg_match('/^(\\d+)px$/i', $widthDesc, $match)) {
                                $width = (int) $match[1];
                            } else {
                                $width = null;
                            }
                            if (!in_array($width, $breakpoints)) {
                                $message = sprintf(
                                    'Use recommended media breakpoints for %s-width of %s',
                                    $breakDesc,
                                    implode('/', $breakpoints) . 'px'
                                );
                                $this->printError($message, $atRuleArgs, $lineNum);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Get maximum breakpoints.
     *
     * @return array
     */
    protected function getMaxBreakpoints()
    {
        // Max breakpoints are one less than min.
        $maxBreakpoints = [];
        foreach ($this->minBreakpoints as $breakpoint) {
            $maxBreakpoints[] = $breakpoint - 1;
        }
        return $maxBreakpoints;
    }
}
