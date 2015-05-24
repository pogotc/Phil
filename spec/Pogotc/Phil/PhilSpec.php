<?php

namespace spec\Pogotc\Phil;

use PhpSpec\ObjectBehavior;
use Pogotc\Phil\Evaluator;
use Pogotc\Phil\Parser;
use Pogotc\Phil\Tokeniser;
use Prophecy\Argument;

class PhilSpec extends ObjectBehavior
{

    function let(Tokeniser $tokeniser, Parser $parser, Evaluator $evaluator)
    {
        $this->beConstructedWith($tokeniser, $parser, $evaluator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Pogotc\Phil\Phil');
    }

    function it_passes_output_from_the_tokeniser_to_the_parser(Tokeniser $tokeniser, Parser $parser, Evaluator $evaluator)
    {
        $input = '(+ 1 2)';
        $inputAsTokens = array('(', '+', '1', '2', ')');
        $parserOutput = array('+', '1', 2);
        $tokeniser->parse($input)->shouldBeCalled();
        $tokeniser->parse($input)->willReturn($inputAsTokens);
        $parser->parse($inputAsTokens)->shouldBeCalled()->willReturn($parserOutput);
        $evaluator->evaluate($parserOutput)->shouldBeCalled();
        $this->run($input);
    }
}
