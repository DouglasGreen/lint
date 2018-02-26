<?php
namespace Lint;

/** Static text functions. */
class PhpText
{
    /** @var array PHP predefined identifiers */
    protected static $idents = [
        '__call' => true,
        '__callstatic' => true,
        '__clone' => true,
        '__construct' => true,
        '__debuginfo' => true,
        '__destruct' => true,
        '__get' => true,
        '_get' => true,
        'globals' => true,
        '__invoke' => true,
        '__isset' => true,
        '_post' => true,
        '_request' => true,
        '_server' => true,
        '_session' => true,
        '__set' => true,
        '__set_state' => true,
        '__sleep' => true,
        '__tostring' => true,
        '__unset' => true,
        '__wakeup' => true
    ];

    /** @var array PHP reserved keywords */
    protected static $keywords = [
        '__halt_compiler' => true,
        'abstract' => true,
        'and' => true,
        'array' => true,
        'as' => true,
        'break' => true,
        'callable' => true,
        'case' => true,
        'catch' => true,
        'class' => true,
        'clone' => true,
        'const' => true,
        'continue' => true,
        'declare' => true,
        'default' => true,
        'die' => true,
        'do' => true,
        'echo' => true,
        'else' => true,
        'elseif' => true,
        'empty' => true,
        'enddeclare' => true,
        'endfor' => true,
        'endforeach' => true,
        'endif' => true,
        'endswitch' => true,
        'endwhile' => true,
        'eval' => true,
        'exit' => true,
        'extends' => true,
        'final' => true,
        'finally' => true,
        'for' => true,
        'foreach' => true,
        'function' => true,
        'global' => true,
        'goto' => true,
        'if' => true,
        'implements' => true,
        'include' => true,
        'include_once' => true,
        'instanceof' => true,
        'insteadof' => true,
        'interface' => true,
        'isset' => true,
        'list' => true,
        'namespace' => true,
        'new' => true,
        'or' => true,
        'print' => true,
        'private' => true,
        'protected' => true,
        'public' => true,
        'require' => true,
        'require_once' => true,
        'return' => true,
        'static' => true,
        'switch' => true,
        'throw' => true,
        'trait' => true,
        'try' => true,
        'unset' => true,
        'use' => true,
        'var' => true,
        'while' => true,
        'xor' => true,
        'yield' => true
    ];

    /**
     * Is this a properly named boolean identifier?
     *
     * @param string $ident
     *
     * @return bool
     */
    public static function isBoolean($ident)
    {
        $isBoolean = preg_match('/^(is|has)[A-Z]/', $ident);
        return $isBoolean;
    }

    /**
     * Is this identifier camel case?
     *
     * @param string $ident
     *
     * @return bool
     */
    public static function isCamelCase($ident)
    {
        $ident = preg_replace('/^\\$/', '', $ident);
        $lowIdent = strtolower($ident);

        // Allow PHP predefined identifiers.
        if (isset(self::$idents[$lowIdent])) {
            return true;
        }

        // Be strict about camel case.
        if (preg_match('/_|[A-Z]{2,}|^[A-Z]/', $ident)) {
            return false;
        }
        return true;
    }

    /**
     * Is a class, interface, or trait definition line?
     *
     * @param string $line
     *
     * @return bool
     */
    public static function isClassLine($line)
    {
        $isClass = (bool) preg_match(
            '/^\\s*(abstract)?\\s*(class|interface|trait)\\s+\\w+/i',
            $line
        );
        return $isClass;
    }

    /**
     * Is a const (attribute) line?
     *
     * @param string $line
     *
     * @return bool
     */
    public static function isConstLine($line)
    {
        $isConst = (bool) preg_match(
            '/^\\s*((public|protected|private)\\s+)?const\\s+\\w+/i',
            $line
        );
        return $isConst;
    }

    /**
     * Is a function definition line?
     *
     * @param string $line
     *
     * @return bool
     */
    public static function isFunctionLine($line)
    {
        $isFunc = (bool) preg_match(
            '/^\\s*((public|protected|private|static|abstract)\\s+)*function\\s+\\w+/i',
            $line
        );
        return $isFunc;
    }

    /**
     * Is initial caps?
     *
     * @param string $ident
     *
     * @return bool
     */
    public static function isInitialCaps($ident)
    {
        $ident = str_replace('ID', 'Id', $ident);
        if (!preg_match('/^([A-Z][a-z_0-9]*)+$/', $ident)) {
            return false;
        }
        return true;
    }

    /**
     * Is this a keyword?
     *
     * @see http://php.net/manual/en/reserved.keywords.php
     *
     * @param string $word
     *
     * @return bool
     */
    public static function isKeyword($word)
    {
        $lowWord = strtolower($word);
        return isset(self::$keywords[$lowWord]);
    }

    /**
     * Is a property (attribute) line?
     *
     * @param string $line
     *
     * @return bool
     */
    public static function isPropertyLine($line)
    {
        $isProperty = (bool) preg_match(
            '/^\\s*((public|protected|private|static|var)\\s+)+\\$\\w+/i',
            $line
        );
        return $isProperty;
    }

    /**
     * Is upper case?
     *
     * @param string $ident
     *
     * @return bool
     */
    public static function isUpperCase($ident)
    {
        if (!preg_match('/^[A-Z][A-Z_0-9]*$/', $ident)) {
            return false;
        }
        return true;
    }

    /**
     * Split an identifier into parts.
     *
     * @param string $ident
     *
     * @return array
     */
    public static function splitIdentifier($ident)
    {
        $parts = preg_split('/[_0-9]+/', $ident, -1, PREG_SPLIT_NO_EMPTY);
        $words = [];
        foreach ($parts as $part) {
            while ($part) {
                if (preg_match('/^[a-z]+/', $part, $match)) {
                    $part = preg_replace('/^[a-z]+/', '', $part);
                    $words[] = $match[0];
                }
                if (preg_match('/^[A-Z]+[a-z]*/', $part, $match)) {
                    $part = preg_replace('/^[A-Z]+[a-z]+/', '', $part);
                    $words[] = $match[0];
                }
            }
        }
        return $words;
    }
}
