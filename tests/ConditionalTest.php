<?php


class ConditionalTest extends AbstractPhilTest
{

    public function testBasicIfElse()
    {
        $this->assertEquals("a", $this->phil->run('(if true "a" "b")'));
        $this->assertEquals("b", $this->phil->run('(if false "a" "b")'));
        $this->assertEquals("a", $this->phil->run('(if (= 5 5) "a" "b")'));
        $this->assertEquals(50, $this->phil->run('(if (= 5 5) (* 5 10) (+ 5 10))'));
    }
}