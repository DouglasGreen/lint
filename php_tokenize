#!/usr/bin/php
<?php
$options = getopt('cw');
$includeChars = isset($options['c']);
$includeWhitespace = isset($options['w']);
$lastArg = $argv[$argc - 1];
$source = file_get_contents($lastArg);
$tokens = token_get_all($source);
foreach ($tokens as $token) {
    if (is_array($token)) {
        $text = preg_replace('/\s+/', ' ', trim($token[1]));
        if ($token[0] == T_WHITESPACE && !$includeWhitespace) {
            continue;
        }
        printf("%s @ %d = %s\n", token_name($token[0]), $token[2], $text);
    } else {
        if ($includeChars) {
            echo $token . "\n";
        }
    }
}
