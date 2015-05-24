<?php

namespace spec\Pogotc\Phil;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ScopeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pogotc\Phil\Scope');
    }

    function it_can_add_two_numbers_together()
    {
        $this->call('+', array(1, 2))->shouldBe(3);
    }

    function it_can_subtract_numbers()
    {
        $this->call('-', array(10, 3))->shouldBe(7);
    }

    function it_can_divide_numbers()
    {
        $this->call('/', array(20, 5))->shouldBe(4);
    }

    function it_can_multiply_numbers()
    {
        $this->call('*', array(5, 5))->shouldBe(25);
    }
}
