<?php

class MathsTest extends AbstractPhilTest
{

    public function testAddition()
    {
        $this->assertEquals(4, $this->phil->run('(+ 2 2)'));
        $this->assertEquals(300, $this->phil->run('(+ 100 200)'));
    }

    public function testSubtraction()
    {
        $this->assertEquals(2, $this->phil->run('(- 10 5 3)'));
        $this->assertEquals(-5, $this->phil->run('(- 5 5 5)'));
        $this->assertEquals(0, $this->phil->run('(- 100 75 25)'));
    }

    public function testDivision()
    {
        $this->assertEquals(1, $this->phil->run('(/ 10 5 2)'));
        $this->assertEquals(2.5, $this->phil->run('(/ 5 2)'));
        $this->assertEquals(2, $this->phil->run('(/ 100 5 10)'));
    }

    function testMultiplication()
    {
        $this->assertEquals(125, $this->phil->run('(* 5 5 5)'));
        $this->assertEquals(-10, $this->phil->run('(* 10 -1)'));
        $this->assertEquals(400, $this->phil->run('(* 10 8 5)'));
    }

    function testQuot()
    {
        $this->assertEquals(3, $this->phil->run('(quot 10 3)'));
        $this->assertEquals(1, $this->phil->run('(quot 3 2)'));
        $this->assertEquals(-1, $this->phil->run('(quot -5.9 3)'));
        $this->assertFalse($this->phil->run('(quot -5.9)'));
    }

    function testMod()
    {
        $this->assertEquals(2, $this->phil->run('(mod 5 3)'));
        $this->assertEquals(28, $this->phil->run('(mod 100 36 4)'));
        $this->assertEquals(3, $this->phil->run('(mod -2 5)'));
        $this->assertFalse($this->phil->run('(mod 100)'));
    }

    function testRem()
    {
        $this->assertEquals(-1, $this->phil->run('(rem -21 4)'));
        $this->assertEquals(6, $this->phil->run('(rem 56 10)'));
        $this->assertEquals(50, $this->phil->run('(rem 1250 60)'));
        $this->assertFalse($this->phil->run('(rem 100)'));
    }

    function testInc()
    {
        $this->assertEquals(0, $this->phil->run('(inc -1)'));
        $this->assertEquals(2, $this->phil->run('(inc 1)'));
        $this->assertEquals(5, $this->phil->run('(inc 4 5)'));
        $this->assertFalse($this->phil->run('(inc)'));
    }

    function testDec()
    {
        $this->assertEquals(-2, $this->phil->run('(dec -1)'));
        $this->assertEquals(0, $this->phil->run('(dec 1)'));
        $this->assertEquals(3, $this->phil->run('(dec 4 5)'));
        $this->assertFalse($this->phil->run('(dec)'));
    }

    function testMax()
    {
        $this->assertEquals(10, $this->phil->run('(max 2 1 10 5)'));
        $this->assertEquals(-1, $this->phil->run('(max -1 -10 -5 -3)'));
        $this->assertFalse($this->phil->run('(max)'));
    }

    function testMin()
    {
        $this->assertEquals(1, $this->phil->run('(min 2 1 10 5)'));
        $this->assertEquals(-10, $this->phil->run('(min -1 -10 -5 -3)'));
        $this->assertFalse($this->phil->run('(min)'));
    }
}