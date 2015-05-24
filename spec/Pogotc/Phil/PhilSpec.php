<?php

namespace spec\Pogotc\Phil;

use PhpSpec\ObjectBehavior;
use Pogotc\Phil\Tokeniser;
use Prophecy\Argument;

class PhilSpec extends ObjectBehavior
{

    function let(Tokeniser $tokeniser)
    {
        $this->beConstructedWith($tokeniser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Pogotc\Phil\Phil');
    }

    function it_passes_input_to_the_tokeniser(Tokeniser $tokeniser)
    {
        $input = '(+ 1 2)';
        $tokeniser->parse($input)->shouldBeCalled();
        $this->run($input);
    }
}
