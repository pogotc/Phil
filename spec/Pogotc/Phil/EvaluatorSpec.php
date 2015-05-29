<?php

namespace spec\Pogotc\Phil;

use PhpSpec\ObjectBehavior;
use Pogotc\Phil\Ast\SymbolList;
use Pogotc\Phil\Scope;
use Prophecy\Argument;

class EvaluatorSpec extends ObjectBehavior
{

    function let()
    {
        $scope = new Scope();
        $this->beConstructedWith($scope->getEnvironment());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Pogotc\Phil\Evaluator');
    }

    function it_returns_null_for_empty_array()
    {
        $this->evaluate(new SymbolList(array()))->shouldBe(null);
    }

    function it_returns_integers_when_passed_as_input()
    {
        $this->evaluate(new SymbolList(array(5)))->shouldBe(5);
    }

    function it_can_add_two_numbers()
    {
        $this->evaluate(new SymbolList(array('+', 2, 3)))->shouldBe(5);
    }

    function it_throws_an_expection_for_undefined_functions()
    {
        $this->shouldThrow(new \RuntimeException('Undeclared function "foobar"'))->duringEvaluate(new SymbolList(array('foobar', 5)));
    }
}
