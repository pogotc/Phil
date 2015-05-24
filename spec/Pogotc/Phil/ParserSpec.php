<?php

namespace spec\Pogotc\Phil;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pogotc\Phil\Parser');
    }

    function it_returns_an_empty_array_for_empty_input()
    {
        $this->parse(array())->shouldBeLike(array());
    }

    function it_can_parse_a_simple_list_of_tokens()
    {
        $input = array('(', '+', '1', '2', ')');
        $output = array('+', '1', '2');
        $this->parse($input)->shouldBeLike($output);
    }

    function it_can_parse_a_nested_list_of_tokens()
    {
        $input = array('(', '+', '1', '(', '*', '5', '5', ')', ')');
        $output = array('+', '1', array('*', '5', '5'));
        $this->parse($input)->shouldBeLike($output);
    }

    function it_throws_an_exception_when_bracket_count_is_incorrect()
    {
        $input = array('(', '+', '1', '2');
        $this->shouldThrow(new \RuntimeException('Syntax error: expected ), got EOF'))->duringParse($input);
    }
}
