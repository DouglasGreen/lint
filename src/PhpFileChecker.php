<?php

namespace Lint;

/** Check a file. */
class PhpFileChecker extends PhpChecker
{
    /** @var float PHP version to check */
    const VERSION = 7.2;

    /**
     * Set the program configuration.
     *
     * @param Config $config
     * @param PhpParser|null $parser
     */
    public function __construct(Config $config, PhpParser $parser = null)
    {
        $this->config = $config;
        if (!$parser) {
            $source = file_get_contents($config->getSourcePath());
            $parser = new PhpParser($source);
        }
        $this->parser = $parser;
    }

    /**
     * Get the parser.
     *
     * @return PhpParser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * Check all the things.
     *
     * @return array
     */
    public function runAllChecks()
    {
        $argumentCountChecker = new PhpArgumentCountChecker($this->config, $this->parser);
        $argumentCountChecker->runCheck();
        $atSignChecker = new PhpAtSignChecker($this->config, $this->parser);
        $atSignChecker->runCheck();
        $attributeAccessChecker = new PhpAttributeAccessChecker($this->config, $this->parser);
        $attributeAccessChecker->runCheck();
        $blankLineChecker = new PhpBlankLineChecker($this->config, $this->parser);
        $blankLineChecker->runCheck();
        $camelCaseChecker = new PhpCamelCaseChecker($this->config, $this->parser);
        $camelCaseChecker->runCheck();
        $classFileChecker = new PhpClassFileChecker($this->config, $this->parser);
        $classFileChecker->runCheck();
        $commentDuplicationChecker = new PhpCommentDuplicationChecker($this->config, $this->parser);
        $commentDuplicationChecker->runCheck();
        $commentedBraceChecker = new PhpCommentedBraceChecker($this->config, $this->parser);
        $commentedBraceChecker->runCheck();
        $commentedCodeChecker = new PhpCommentedCodeChecker($this->config, $this->parser);
        $commentedCodeChecker->runCheck();
        $commentChecker = new PhpCommentChecker($this->config, $this->parser);
        $commentChecker->runCheck();
        $compatibilityChecker = new PhpCompatibilityChecker($this->config, $this->parser);
        $compatibilityChecker->runCheck();
        $configurationChecker = new PhpConfigurationChecker($this->config, $this->parser);
        $configurationChecker->runCheck();
        $copyrightChecker = new PhpCopyrightChecker($this->config, $this->parser);
        $copyrightChecker->runCheck();
        $debugChecker = new PhpDebugChecker($this->config, $this->parser);
        $debugChecker->runCheck();
        $docBlockChecker = new PhpDocBlockChecker($this->config, $this->parser);
        $docBlockChecker->runCheck();
        $evalChecker = new PhpEvalChecker($this->config, $this->parser);
        $evalChecker->runCheck();
        $exceptionChecker = new PhpExceptionChecker($this->config, $this->parser);
        $exceptionChecker->runCheck();
        $extractChecker = new PhpExtractChecker($this->config, $this->parser);
        $extractChecker->runCheck();
        $fluentInterfaceChecker = new PhpFluentInterfaceChecker($this->config, $this->parser);
        $fluentInterfaceChecker->runCheck();
        $functionAccessChecker = new PhpFunctionAccessChecker($this->config, $this->parser);
        $functionAccessChecker->runCheck();
        $functionClassChecker = new PhpFunctionClassChecker($this->config, $this->parser);
        $functionClassChecker->runCheck();
        $functionNameChecker = new PhpFunctionNameChecker($this->config, $this->parser);
        $functionNameChecker->runCheck();
        $functionChecker = new PhpFunctionChecker($this->config, $this->parser);
        $functionChecker->runCheck();
        $globalChecker = new PhpGlobalChecker($this->config, $this->parser);
        $globalChecker->runCheck();
        $heredocChecker = new PhpHeredocChecker($this->config, $this->parser);
        $heredocChecker->runCheck();
        $includeChecker = new PhpIncludeChecker($this->config, $this->parser);
        $includeChecker->runCheck();
        $identifierChecker = new PhpIdentifierChecker($this->config, $this->parser);
        $results = $identifierChecker->runCheck();
        $indentChecker = new PhpIndentChecker($this->config, $this->parser);
        $indentChecker->runCheck();
        $instanceOfChecker = new PhpInstanceOfChecker($this->config, $this->parser);
        $instanceOfChecker->runCheck();
        $keywordChecker = new PhpKeywordChecker($this->config, $this->parser);
        $keywordChecker->runCheck();
        $lawOfDemeterChecker = new PhpLawOfDemeterChecker($this->config, $this->parser);
        $lawOfDemeterChecker->runCheck();
        $localVariableChecker = new PhpLocalVariableChecker($this->config, $this->parser);
        $localVariableChecker->runCheck();
        $logicalOperatorChecker = new PhpLogicalOperatorChecker($this->config, $this->parser);
        $logicalOperatorChecker->runCheck();
        $memberOrderChecker = new PhpMemberOrderChecker($this->config, $this->parser);
        $memberOrderChecker->runCheck();
        $messChecker = new PhpMessChecker($this->config, $this->parser);
        $messChecker->runCheck();
        $methodAccessChecker = new PhpMethodAccessChecker($this->config, $this->parser);
        $methodAccessChecker->runCheck();
        $nameRepetitionChecker = new PhpNameRepetitionChecker($this->config, $this->parser);
        $nameRepetitionChecker->runCheck();
        $namespaceChecker = new PhpNamespaceChecker($this->config, $this->parser);
        $namespaceChecker->runCheck();
        $nestingChecker = new PhpNestingChecker($this->config, $this->parser);
        $nestingChecker->runCheck();
        $numberChecker = new PhpNumberChecker($this->config, $this->parser);
        $numberChecker->runCheck();
        $openTagChecker = new PhpOpenTagChecker($this->config, $this->parser);
        $openTagChecker->runCheck();
        $predefinedConstantChecker = new PhpPredefinedConstantChecker($this->config, $this->parser);
        $predefinedConstantChecker->runCheck();
        $psr2Checker = new PhpPsr2Checker($this->config, $this->parser);
        $psr2Checker->runCheck();
        $returnChecker = new PhpReturnChecker($this->config, $this->parser);
        $returnChecker->runCheck();
        $staticChecker = new PhpStaticChecker($this->config, $this->parser);
        $staticChecker->runCheck();
        $strposChecker = new PhpStrposChecker($this->config, $this->parser);
        $strposChecker->runCheck();
        $switchChecker = new PhpSwitchChecker($this->config, $this->parser);
        $switchChecker->runCheck();
        $syntaxChecker = new PhpSyntaxChecker($this->config, $this->parser);
        $syntaxChecker->runCheck();
        $testConditionChecker = new PhpTestConditionChecker($this->config, $this->parser);
        $testConditionChecker->runCheck();
        $testRepetitionChecker = new PhpTestRepetitionChecker($this->config, $this->parser);
        $testRepetitionChecker->runCheck();
        $toDoChecker = new PhpToDoChecker($this->config, $this->parser);
        $toDoChecker->runCheck();
        $useStatementChecker = new PhpUseStatementChecker($this->config, $this->parser);
        $useStatementChecker->runCheck();
        $variableChecker = new PhpVariableChecker($this->config, $this->parser);
        $variableChecker->runCheck();
        return $results;
    }
}
