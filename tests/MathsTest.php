<?php

use Pogotc\Phil\Evaluator;
use Pogotc\Phil\Parser;
use Pogotc\Phil\Phil;
use Pogotc\Phil\Scope;
use Pogotc\Phil\Tokeniser;

class MathsTest extends \PHPUnit_Framework_TestCase
{

    private $phil;

    public function setUp()
    {
        parent::setUp();
        $this->phil = new Phil(new Tokeniser(), new Parser(), new Evaluator(new Scope()));
    }


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
}