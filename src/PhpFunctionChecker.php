<?php
namespace Lint;

/** Check functions. */
class PhpFunctionChecker extends PhpFileChecker
{
    /** Run the check. */
    public function runCheck()
    {
        $verbs = $this->config->getVerbs();
        $functions = $this->parser->getFunctions();
        foreach ($functions as $index => $funcLines) {
            $hasReturn = false;
            $hasReturnTag = false;
            $throwsException = false;
            $hasThrowsTag = false;
            $funcName = '';
            $paramTags = [];
            $isAbstract = false;
            $isStatic = false;
            $hasThis = false;
            $returnType = null;
            foreach ($funcLines as $line) {
                if (!$funcName && PhpText::isFunctionLine($line)) {
                    preg_match('/\\bfunction\\s+(\\w+)/i', $line, $match);
                    $funcName = $match[1];
                    if (preg_match('/\\babstract\\b.*function/', $line)) {
                        $isAbstract = true;
                    }
                    if (preg_match('/\\bstatic\\b.*function/', $line)) {
                        $isStatic = true;
                    }
                }
                if (preg_match('/^\\s*\\*\\s*@return(.*)/', $line, $match)) {
                    $hasReturnTag = true;
                    $returnType = trim($match[1]);
                } elseif (preg_match('/^\\s*return\\b.*\\S.*;/', $line)) {
                    $hasReturn = true;
                }
                if (preg_match('/^\\s*\\*\\s*@throws/', $line)) {
                    $hasThrowsTag = true;
                } elseif (preg_match('/^\\s*throw\\s+new\\b/', $line)) {
                    $throwsException = true;
                }
                if (preg_match('/^\\s*\\*\\s*@param\\s+(\\S+)\\s+\\$(\\w+)/', $line, $match)) {
                    $paramType = $match[1];
                    $paramTag = $match[2];
                    $paramTags[$paramTag] = $paramType;
                }
                if (preg_match('/\\$this->/i', $line)) {
                    $hasThis = true;
                }
            }
            $funcDesc = $funcName . '()';

            // Check function name.
            if (preg_match('/^(bool|boolean)\\b/i', $returnType) && !PhpText::isBoolean($funcName)) {
                $this->printError(
                    'Boolean function names should start with "is" or "has"',
                    $funcDesc,
                    $index + 1
                );
            }
            $commentWord = '';
            foreach ($funcLines as $line) {
                if (preg_match('/^\\s*\\*\\s*(\\w+)/', $line, $match)) {
                    $commentWord = $match[1];
                    break;
                }
            }
            if ($commentWord && !isset($verbs[strtolower($commentWord)])) {
                $desc = $funcDesc . ' starts with ' . $commentWord;
                $this->printError(
                    'Function comments should start with imperative verbs',
                    $desc,
                    $index + 1
                );
            }
            $source = implode("\n", $funcLines);
            $params = [];
            if (preg_match('/\\bfunction\\s+\\w+\\s*\\((.*?)\\)\\s*[{;]/is', $source, $match)) {
                $args = $match[1];
                if (preg_match_all('/\\$(\\w+)(\\s*=\\s*([^,]+))?/', $args, $matches)) {
                    $params = $matches[1];
                    foreach ($params as $paramIndex => $param) {
                        $default = isset($matches[3][$paramIndex]) ? $matches[3][$paramIndex] : null;
                        $hasNullDefault = strtolower($default) == 'null';
                        if (isset($paramTags[$param])) {
                            $paramType = $paramTags[$param];
                            $typeHint = $paramType . ' $' . $param;
                            if ($hasNullDefault && !preg_match('/\\|null\\b/', $paramType)) {
                                $this->printError(
                                    'Param tag should have null option',
                                    $typeHint,
                                    $index + 1
                                );
                            }

                            // Don't check type hints for complex or mixed types.
                            if ($paramType == 'mixed' || preg_match('/\\|/', $paramType)) {
                                continue;
                            }
                            if (strpos($args, $typeHint) === false) {
                                $message = sprintf('Use type hint "%s" for parameter', $typeHint);
                                $this->printError($message, $funcDesc, $index + 1);
                            }
                        }
                    }
                }
            }
            if ($hasReturn && !$hasReturnTag) {
                $this->printError('Has return statement and no @return tag', $funcDesc, $index + 1);
            }
            if (!$hasReturn && $hasReturnTag && !$isAbstract) {
                $this->printError('Has @return tag and no return statement', $funcDesc, $index + 1);
            }
            if ($throwsException && !$hasThrowsTag) {
                $this->printError('Throws exception and has no @throws tag', $funcDesc, $index + 1);
            }
            if (!$throwsException && $hasThrowsTag) {
                $this->printError('Has @throws tag and throws no exception', $funcDesc, $index + 1);
            }
            $constructors = [
                '__construct',
                '__destruct'
            ];
            if ($throwsException && in_array($funcName, $constructors)) {
                $this->printError(
                    'Avoid throwing exceptions in constructors and destructors',
                    $funcDesc,
                    $index + 1
                );
            }
            foreach (array_keys($paramTags) as $tag) {
                if (!in_array($tag, $params)) {
                    $message = sprintf('Has @param tag $%s and has no such parameter', $tag);
                    $this->printError($message, $funcDesc, $index + 1);
                }
            }
            foreach ($params as $param) {
                if (!isset($paramTags[$param])) {
                    $message = sprintf('Has parameter $%s and has no such tag', $param);
                    $this->printError($message, $funcDesc, $index + 1);
                }
            }
            if (!$isStatic && !$hasThis) {
                $this->printError(
                    'Function without $this reference can be made static',
                    $funcDesc,
                    $index + 1
                );
            }
        }
    }
}
