<?php

namespace Lint;

/** Static text functions. */
class PhpToken
{
    /**
     * Is this a comment token?
     *
     * @param array|string $token
     *
     * @return bool
     */
    public static function isComment($token)
    {
        if (is_array($token)) {
            if ($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
                return true;
            }
        }
        return false;
    }

    /**
     * Is this a keyword token?
     *
     * @param array|string $token
     *
     * @return bool
     */
    public static function isKeyword($token)
    {
        $types = [
            T_ABSTRACT,
            T_ARRAY,
            T_ARRAY_CAST,
            T_AS,
            T_BOOL_CAST,
            T_BREAK,
            T_CALLABLE,
            T_CASE,
            T_CATCH,
            T_CLASS,
            T_CLONE,
            T_CONST,
            T_CONTINUE,
            T_DECLARE,
            T_DEFAULT,
            T_DO,
            T_DOUBLE_CAST,
            T_ECHO,
            T_ELSE,
            T_ELSEIF,
            T_EMPTY,
            T_ENDDECLARE,
            T_ENDFOR,
            T_ENDFOREACH,
            T_ENDIF,
            T_ENDSWITCH,
            T_ENDWHILE,
            T_EVAL,
            T_EXIT,
            T_EXTENDS,
            T_FINAL,
            T_FINALLY,
            T_FOR,
            T_FOREACH,
            T_FUNCTION,
            T_GLOBAL,
            T_GOTO,
            T_HALT_COMPILER,
            T_IF,
            T_IMPLEMENTS,
            T_INCLUDE,
            T_INCLUDE_ONCE,
            T_INSTANCEOF,
            T_INSTEADOF,
            T_INT_CAST,
            T_INTERFACE,
            T_ISSET,
            T_LIST,
            T_LNUMBER,
            T_LOGICAL_AND,
            T_LOGICAL_OR,
            T_LOGICAL_XOR,
            T_NAMESPACE,
            T_NEW,
            T_OBJECT_CAST,
            T_OPEN_TAG,
            T_PRINT,
            T_PRIVATE,
            T_PUBLIC,
            T_PROTECTED,
            T_REQUIRE,
            T_REQUIRE_ONCE,
            T_RETURN,
            T_STATIC,
            T_STRING_CAST,
            T_SWITCH,
            T_THROW,
            T_TRAIT,
            T_TRY,
            T_UNSET,
            T_UNSET_CAST,
            T_USE,
            T_VAR,
            T_WHILE,
            T_YIELD
        ];
        if (is_array($token)) {
            if (in_array($token[0], $types)) {
                return true;
            } elseif ($token[0] == T_STRING && preg_match('/^(self|static|parent)$/i', $token[1])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Is this a predefined constant token?
     *
     * @param array|string $token
     *
     * @return bool
     */
    public static function isPredefinedConstant($token)
    {
        $types = [
            T_CLASS_C,
            T_DIR,
            T_FILE,
            T_FUNC_C,
            T_LINE,
            T_METHOD_C,
            T_NS_C,
            T_TRAIT_C
        ];
        $isConst = is_array($token) && in_array($token[0], $types);
        return $isConst;
    }

    /**
     * Is this a whitespace token?
     *
     * @param array|string $token
     *
     * @return bool
     */
    public static function isWhitespace($token)
    {
        if (is_array($token)) {
            if ($token[0] == T_WHITESPACE) {
                return true;
            }
        }
        return false;
    }
}
