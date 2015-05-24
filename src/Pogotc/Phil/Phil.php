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
    /**
     * @var Evaluator
     */
    private $evaluator;

    public function __construct(Tokeniser $tokeniser, Parser $parser, Evaluator $evaluator)
    {
        $this->tokeniser = $tokeniser;
        $this->parser = $parser;
        $this->evaluator = $evaluator;
    }

    public function run($input)
    {
        $tokens = $this->tokeniser->parse($input);
        $ast = $this->parser->parse($tokens);
        return $this->evaluator->evaluate($ast);
    }
}
