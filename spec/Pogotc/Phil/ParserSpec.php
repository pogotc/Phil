<?php

namespace spec\Pogotc\Phil;

use PhpSpec\ObjectBehavior;
use Pogotc\Phil\Ast\LiteralList;
use Pogotc\Phil\Ast\Map;
use Pogotc\Phil\Ast\SymbolList;
use Prophecy\Argument;

class ParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pogotc\Phil\Parser');
    }

    function it_can_parse_multi_nested_tokens()
    {
        $input = array('(', '*', '(', '+', '5', '5', ')', '(', '-', '10', '2', ')', ')');
        $output = new SymbolList(array(
            '*',
            new SymbolList(array('+', '5', '5')),
            new SymbolList(array('-', '10', '2')),
        ));

        $this->parse($input)->shouldBeLike($output);
    }

    function it_returns_an_empty_array_for_empty_input()
    {
        $this->parse(array())->shouldBeLike(array());
    }

    function it_can_parse_a_simple_list_of_tokens()
    {
        $input = array('(', '+', '1', '2', ')');
        $output = new SymbolList(array('+', '1', '2'));
        $this->parse($input)->shouldBeLike($output);
    }

    function it_can_parse_a_nested_list_of_tokens()
    {
        $input = array('(', '+', '1', '(', '*', '5', '5', ')', ')');
        $output = new SymbolList(array('+', '1', new SymbolList(array('*', '5', '5'))));
        $this->parse($input)->shouldBeLike($output);
    }

    function it_throws_an_exception_when_bracket_count_is_incorrect()
    {
        $input = array('(', '+', '1', '2');
        $this->shouldThrow(new \RuntimeException('Syntax error: expected ), got EOF'))->duringParse($input);
    }

    function it_can_parse_a_literal_list()
    {
        $input = array("'(", "1", "2", "3", ")");
        $output = new LiteralList(array("1", "2", "3"));

        $this->parse($input)->shouldBeLike($output);
    }

    function it_can_parse_a_map()
    {
        $input = array('{', '"a"', '1', '"b"', '2', '"c"', '3', '}');
        $output = new Map(array("a" => 1, "b" => 2, "c" => 3));
        $this->parse($input)->shouldBeLike($output);

        $input = array('{', '"a"', '1', '"b"', '2', '"c"', '}');
        $this->shouldThrow(new \RuntimeException('Odd number of hash map arguments'))->duringParse($input);
    }
}
