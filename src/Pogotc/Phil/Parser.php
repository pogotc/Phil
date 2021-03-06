<?php

namespace Pogotc\Phil;

use Pogotc\Phil\Ast\SymbolList;
use Pogotc\Phil\Ast\Map;

class Parser
{

    private $tokenStream;
    private $tokenStreamPos;

    public function parse(array $tokens)
    {
        if (count($tokens) === 0) {
            return array();
        }

        $this->tokenStream = $tokens;
        $this->tokenStreamPos = 0;

        return $this->parseNextToken();
    }

    /**
     * @return mixed|void
     */
    private function parseNextToken()
    {
        $nextToken = $this->readNextToken();

        switch ($nextToken) {
            case "'(":
                return $this->readList("Pogotc\\Phil\\Ast\\LiteralList");
            case '(':
                return $this->readList("Pogotc\\Phil\\Ast\\SymbolList");
            case '{':
                return Map::fromLiteralList($this->readList("Pogotc\\Phil\\Ast\\LiteralList", '}'));
            default:
                if (is_numeric($nextToken)) {
                    return floatval($nextToken);
                }
                return $nextToken;
        }
    }

    /**
     * @return mixed
     */
    private function readNextToken()
    {
        return $this->tokenStream[$this->tokenStreamPos++];
    }

    private function readList($listType = null, $endToken = ')')
    {
        $ast = new $listType();

        while (($token = $this->peekNextToken()) !== $endToken) {
            if ($this->isUnexpectedToken($token)) {
                throw new \RuntimeException('Syntax error: expected ' . $endToken . ', got EOF');
            }
            $ast[] = $this->parseNextToken();
        }
        $this->readNextToken();
        return $ast;
    }

    private function peekNextToken()
    {
        if ($this->tokenStreamPos >= count($this->tokenStream)) {
            return null;
        }
        return $this->tokenStream[$this->tokenStreamPos];
    }

    /**
     * @param $token
     * @return bool
     */
    private function isUnexpectedToken($token)
    {
        return $token === null;
    }
}
