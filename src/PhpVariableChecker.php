<?php
namespace Lint;

/** Check variables. */
class PhpVariableChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $functions = $this->parser->getFunctions();
        $lines = $this->parser->getLines();
        foreach ($functions as $index => $funcLines) {
            $declares = [];
            $uses = [];
            $funcName = '';
            $funcDesc = '';
            foreach ($funcLines as $index => $line) {
                if (!$funcName && PhpText::isFunctionLine($line)) {
                    preg_match('/\\bfunction\\s+(\\w+)/i', $line, $match);
                    $funcName = $match[1];
                    $funcDesc = $funcName . '()';
                }

                // First find declarations.
                if (preg_match('/^\\s*\\$(\\w+)\\s*=[^=]/', $line, $match)) {
                    $name = $match[1];
                    if (!isset($declares[$name])) {
                        $declares[$name] = $index;
                    }
                }

                // Then find uses on the first line after declaration.
                if (preg_match_all('/\\$(\\w+)/', $line, $matches)) {
                    foreach ($matches[1] as $name) {
                        $isFirstUse = isset($declares[$name]) && $index > $declares[$name] &&
                            !isset($uses[$name]);
                        if ($isFirstUse) {
                            $uses[$name] = $index;
                        }
                    }
                }
            }

            // Now check where variables were declared.
            foreach ($declares as $name => $declareIndex) {
                if (isset($uses[$name])) {
                    $useIndex = $uses[$name];

                    // Count lines of code ending in semicolon between declaration and use.
                    $lineCount = 0;
                    for ($curLine = $declareIndex + 1; $curLine < $useIndex; $curLine++) {
                        if (preg_match('/;\\s*$/', $lines[$curLine])) {
                            $lineCount++;
                        }
                    }
                    if ($lineCount > 10) {
                        $message = sprintf('Declare variable $%s close to first use', $name);
                        $this->printError($message, $funcDesc, $declareIndex + 1);
                    }
                }
            }
        }
    }
}
