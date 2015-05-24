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
}
