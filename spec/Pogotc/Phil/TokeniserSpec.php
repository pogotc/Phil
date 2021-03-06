<?php

namespace spec\Pogotc\Phil;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokeniserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pogotc\Phil\Tokeniser');
    }

    function it_returns_an_empty_array_for_empty_input()
    {
        $this->parse('')->shouldBeLike(array());
    }

    function it_returns_a_single_array_element_for_one_char_input()
    {
        $this->parse('1')->shouldBeLike(array(1));
    }

    function it_returns_two_array_elements_for_two_numbers()
    {
        $this->parse('1 2')->shouldBeLike(array(1, 2));
    }

    function it_ignores_extra_spaces()
    {
        $this->parse('  1   2 3')->shouldBeLike(array(1, 2, 3));
    }

    function it_can_split_multicharacter_input()
    {
        $this->parse('123 456')->shouldBeLike(array(123, 456));
    }

    function it_keeps_brackets_separate_from_data()
    {
        $this->parse('(+ 2 3)')->shouldBeLike(array('(', '+', 2, 3, ')'));
    }

    function it_accepts_brackets_that_are_next_to_each_other()
    {
        $this->parse('(+(* 1 2)(+ 3 4))')->shouldBeLike(array('(', '+', '(', '*', '1', '2', ')', '(', '+', '3', '4', ')', ')'));
    }

    function it_treats_quoted_open_bracket_as_a_single_token()
    {
        $this->parse("'(")->shouldBeLike(array("'("));
    }

    function it_supports_multiline_input()
    {
        $input = <<<INPUT
(
    +
    1
3
)
INPUT;
        $this->parse($input)->shouldBeLike(array('(', '+', 1, 3, ')'));
    }


    function it_splits_args_lists_from_functions()
    {
        $input = '(defn funcName[] 5)';
        $this->parse($input)->shouldBeLike(array('(', 'defn', 'funcName', '[]', '5', ')'));
    }

    function it_handles_string_literals()
    {
        $this->parse('"foo"')->shouldBeLike(array("foo"));
        $this->parse('"foo bar"')->shouldBeLike(array("foo bar"));
    }

    function it_handles_boolean_literals()
    {
        $this->parse('true')->shouldBeLike(array(true));
        $this->parse('false')->shouldBeLike(array(false));
    }

    function it_ignores_commas()
    {
        $this->parse('(func 1, 2, 3)')->shouldBeLike(array('(', 'func', '1', '2', '3', ')'));
        $this->parse('(func 1,2,3)')->shouldBeLike(array('(', 'func', '1', '2', '3', ')'));
    }

    function it_handles_curly_brackets()
    {
        $this->parse('{"a" 1, "b" 2, "c" 3}')->shouldBeLike(array('{', 'a', '1', 'b', '2', 'c', '3', '}'));
    }
}
