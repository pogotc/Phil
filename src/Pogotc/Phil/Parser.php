<?php

namespace Pogotc\Phil;

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
            case '(':
                return $this->readList();
            default:
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

    private function readList()
    {
        $ast = array();

        while (($token = $this->peekNextToken()) !== ')') {
            if ($token === '' || $token === null) {
                throw new \RuntimeException('Syntax error: expected ), got EOF');
            }
            $ast[]= $this->parseNextToken();
        }
        return $ast;
    }

    private function peekNextToken()
    {
        if ($this->tokenStreamPos >= count($this->tokenStream)) {
            return null;
        }
        return $this->tokenStream[$this->tokenStreamPos];
    }
}
