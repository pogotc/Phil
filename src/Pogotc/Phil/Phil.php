<?php

namespace Pogotc\Phil;

class Phil
{

    /**
     * @var Tokeniser
     */
    private $tokeniser;

    public function __construct(Tokeniser $tokeniser)
    {
        $this->tokeniser = $tokeniser;
    }

    public function run($input)
    {
        $this->tokeniser->parse($input);
    }
}
