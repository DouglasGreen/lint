<?php
namespace Lint;

/** Check a file. */
class CssFileChecker extends Checker
{
    /**
    * Set the program configuration.
    *
    * @param Config $config
    * @param CssParser|null $parser
    */
    public function __construct(Config $config, CssParser $parser = null)
    {
        $this->config = $config;
        if (!$parser) {
            $source = file_get_contents($config->getSourcePath());
            $parser = new CssParser($source);
        }
        $this->parser = $parser;
    }

    /**
     * Get the parser.
     *
     * @return CssParser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * Check all the things.
     */
    public function runAllChecks()
    {
        $mediaQueryChecker = new CssMediaQueryChecker($this->config, $this->parser);
        $mediaQueryChecker->runCheck();
        $rulesetChecker = new CssRulesetChecker($this->config, $this->parser);
        $rulesetChecker->runCheck();
        $selectorChecker = new CssSelectorChecker($this->config, $this->parser);
        $selectorChecker->runCheck();
    }
}
