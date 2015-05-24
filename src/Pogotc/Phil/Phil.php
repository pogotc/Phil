<?php

namespace Pogotc\Phil;

class Phil
{

    /**
     * @var Tokeniser
     */
    private $tokeniser;
    /**
     * @var Parser
     */
    private $parser;

    public function __construct(Tokeniser $tokeniser, Parser $parser)
    {
        $this->tokeniser = $tokeniser;
        $this->parser = $parser;
    }

    public function run($input)
    {
        $tokens = $this->tokeniser->parse($input);
        $this->parser->parse($tokens);
    }
}
