<?php


class ConditionalTest extends AbstractPhilTest
{

    public function testBasicIfElse()
    {
        $this->assertEquals("a", $this->phil->run('(if true "a" "b")'));
        $this->assertEquals("b", $this->phil->run('(if false "a" "b")'));
    }
}