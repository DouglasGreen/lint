#!/usr/bin/php
<?php
namespace Lint;

require_once __DIR__ . '/vendor/autoload.php';
if ($argc < 2) {
    die("Usage: $argv[0] <file_or_dir>...\n");
}
for ($i = 1; $i < $argc; $i++) {
    $file = $argv[$i];
    $config = new Config($file);
    if (is_dir($file)) {
        $checker = new PhpDirChecker($config);
        $checker->runAllChecks();
    } else {
        $checker = new PhpFileChecker($config);
        $checker->runAllChecks();
    }
}
