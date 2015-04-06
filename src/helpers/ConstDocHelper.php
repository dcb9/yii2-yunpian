<?php

namespace dcb9\Yunpian\sdk\helpers;

class ConstDocHelper
{
    /** @var array Constant names to DocComment strings. */
    private $docComments = [];

    /** Constructor. */
    public function __construct($clazz)
    {
        $this->parse(new \ReflectionClass($clazz));
    }

    /** Parses the class for constant DocComments. */
    private function parse(\ReflectionClass $clazz)
    {
        $content = file_get_contents($clazz->getFileName());
        $tokens = token_get_all($content);

        $doc = null;
        $isConst = false;
        foreach ($tokens as $token) {
            @ list($tokenType, $tokenValue) = $token;

            switch ($tokenType) {
                // ignored tokens
                case T_WHITESPACE:
                case T_COMMENT:
                    break;

                case T_DOC_COMMENT:
                    $doc = $tokenValue;
                    break;

                case T_CONST:
                    $isConst = true;
                    break;

                case T_STRING:
                    if ($isConst) {
                        $this->docComments[$tokenValue] = self::clean($doc);
                    }
                    $doc = null;
                    $isConst = false;
                    break;

                // all other tokens reset the parser
                default:
                    $doc = null;
                    $isConst = false;
                    break;
            }
        }

    }

    /** Returns an array of all constants to their DocComment. If no comment is present the comment is null. */
    public function getDocComments()
    {
        return $this->docComments;
    }

    /** Returns the DocComment of a class constant. Null if the constant has no DocComment or the constant does not exist. */
    public function getDocComment($constantName)
    {
        if (!isset($this->docComments)) {
            return null;
        }

        return $this->docComments[$constantName];
    }

    /** Cleans the doc comment. Returns null if the doc comment is null. */
    private static function clean($doc)
    {
        if ($doc === null) {
            return null;
        }

        $result = null;
        $lines = preg_split('/\r/', $doc);
        foreach ($lines as $line) {

            $line = preg_replace('/^\s*\* /', '', trim($line, "\n/* \t\x0B\0"));
            if ($line === '') {
                continue;
            }

            if ($result != null) {
                $result .= ' ';
            }
            $result .= $line;
        }

        return $result;
    }
}
