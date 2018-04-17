<?php

namespace Lint;

/** Check member order. */
class PhpMemberOrderChecker extends PhpFileChecker
{
    /** @var array Lines of source code */
    protected $lines;

    /** @var array Numeric order */
    protected $orders;

    /** Run the check. */
    public function runCheck()
    {
        $source = $this->parser->getSource();
        $regexp = '/^\\s*(\\w+\\s+)*.*\\b(const|public|protected|private)\\b(\\s+\\w+)*\\s+\\b(function|\\$\\w+).*/m';
        if (!preg_match_all($regexp, $source, $matches)) {
            return;
        }
        $this->initialize();
        $this->fill($matches[0]);
        $this->check();
    }

    /**
     * Check the order.
     */
    protected function check()
    {
        $previous = -1;
        foreach ($this->orders as $type => $ord1) {
            foreach ($ord1 as $access => $ord2) {
                foreach ($ord2 as $stat => $ord3) {
                    ksort($ord3, SORT_STRING | SORT_FLAG_CASE);
                    foreach ($ord3 as $name => $current) {
                        if ($current != $previous + 1) {
                            $line = $this->lines[$type][$access][$stat][$name];
                            $lineNum = $this->parser->getLineNumber($line);
                            $ident = "{$type} {$access} {$stat} {$name}";
                            $this->printError('Out of properly grouped alphabetical order', $ident, $lineNum);
                        }
                        $previous = $current;
                    }
                }
            }
        }
    }

    /**
     * Fill the orders and lines arrays.
     *
     * @param array $lines
     */
    protected function fill(array $lines)
    {
        $order = 0;
        foreach ($lines as $line) {
            // Get member access.
            if (preg_match('/\\b(const|public|protected)\\b/', $line, $match)) {
                $access = $match[1];
            }

            // Get static keyword.
            $stat = preg_match('/\\b(static)\\b/', $line, $match) ? 'static' : '';

            // Get type (function or var).
            if (preg_match('/\\bfunction (\\w+)/', $line, $match)) {
                $type = 'function';
                $name = $match[1] . '()';
            } elseif (preg_match('/(\\$\\w+)/', $line, $match)) {
                $type = 'var';
                $name = $match[1];
            } else {
                $lineNum = $this->parser->getLineNumber($line);
                $this->printError('Unmatched line', $line, $lineNum);
            }

            // Fill out orders array with next entry.
            $this->orders[$type][$access][$stat][$name] = $order++;

            // Save line to display.
            $this->lines[$type][$access][$stat][$name] = $line;
        }
    }

    /**
     * Initialize the orders.
     */
    protected function initialize()
    {
        $this->lines = [];
        $this->orders = [];
        $this->orders['const'][''][''] = [];
        $this->orders['var']['public']['static'] = [];
        $this->orders['var']['public'][''] = [];
        $this->orders['var']['protected']['static'] = [];
        $this->orders['var']['protected'][''] = [];
        $this->orders['var']['private']['static'] = [];
        $this->orders['var']['private'][''] = [];
        $this->orders['func']['public']['static'] = [];
        $this->orders['func']['public'][''] = [];
        $this->orders['func']['protected']['static'] = [];
        $this->orders['func']['protected'][''] = [];
        $this->orders['func']['private']['static'] = [];
        $this->orders['func']['private'][''] = [];
    }
}
