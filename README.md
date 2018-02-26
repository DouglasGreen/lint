# lint

A set of scripts for checking source code for problems.

Each of the lint scripts has two modes of operation.
1. With a file argument, checks the file.
2. With a directory argument, iterates over all the files with the right extensions in a directory
then checks the directory globally.

The checks are just suggestions. Feel free to ignore them when modifying your code.

## Installation

1. `git clone https://github.com/DouglasGreen/lint.git`
2. `cd lint`
3. `composer install`
4. Add to path in Bash profile: `PATH=$PATH:$HOME/lint`

## Usage

### css_lint

css_lint uses [PHP-CSS-Parser by sabberworm](https://github.com/sabberworm/PHP-CSS-Parser) to parse
CSS, then does a few basic checks.

### php_lint

php_lint uses a combination of checks from exising lint programs, plus a bunch of checks of my own
design.

This program uses:
* [PHP_CodeSniffer](https://www.squizlabs.com/php-codesniffer)
* [PHP Copy/Paste Detector (PHPCPD)](https://github.com/sebastianbergmann/phpcpd)
* [PHPMD - PHP Mess Detector](https://phpmd.org/)

### php_tokenize

A utility script to dump PHP tokens from a file to the command line.

## Notes

Some notes on code quality used in preparing this utility are:
* [Code Complete 2nd. Ed. Checklists](https://www.matthewjmiller.net/files/cc2e_checklists.pdf)
