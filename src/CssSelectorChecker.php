<?php
namespace Lint;

/** Check selectors. */
class CssSelectorChecker extends CssFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $document = $this->parser->getDocument();
        $blocks = $document->getAllDeclarationBlocks();
        foreach ($blocks as $block) {
            $lineNum = $block->getLineNo();
            $selectors = $block->getSelectors();
            foreach ($selectors as $selector) {
                $text = $selector->getSelector();
                $specificity = $selector->getSpecificity();
                if ($specificity < 100) {
                    $message = sprintf(
                        'Low specificity = %d. Use one and only one id selector for specificity between 100 and 200',
                        $specificity
                    );
                    $this->printError($message, $text, $lineNum);
                } elseif ($specificity >= 200) {
                    $message = sprintf(
                        'High specificity = %d. Use one and only one id selector for specificity between 100 and 200',
                        $specificity
                    );
                    $this->printError($message, $text, $lineNum);
                } elseif (!preg_match('/^#/', $text)) {
                    $message = 'Selectors should always start with an id selector';
                    $this->printError($message, $text, $lineNum);
                }
                $names = CssText::splitSelector($text);
                if ($names) {
                    foreach ($names as $name) {
                        if (HtmlText::isTag($name)) {
                            if ($name != strtolower($name)) {
                                $message = sprintf(
                                    'HTML tag names such as %s should be lowercased in selectors',
                                    $name
                                );
                                $this->printError($message, $text, $lineNum);
                            }
                        } elseif (!CssText::isCamelCase($name)) {
                            $message = sprintf('The name %s is not camel case', $name);
                            $this->printError($message, $text, $lineNum);
                        }
                    }
                }
            }
        }
    }
}
