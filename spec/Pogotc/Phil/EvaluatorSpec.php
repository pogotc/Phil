<?php

namespace spec\Pogotc\Phil;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EvaluatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pogotc\Phil\Evaluator');
    }

    function it_returns_null_for_empty_array()
    {
        $this->evaluate(array())->shouldBe(null);
    }

    function it_returns_integers_when_passed_as_input()
    {
        $this->evaluate(array(5))->shouldBe(5);
    }

    function it_can_add_two_numbers()
    {
        $this->evaluate(array('+', 2, 3))->shouldBe(5);
    }
}
