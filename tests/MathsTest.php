<?php

use Pogotc\Phil\Evaluator;
use Pogotc\Phil\Parser;
use Pogotc\Phil\Phil;
use Pogotc\Phil\Tokeniser;

class MathsTest extends \PHPUnit_Framework_TestCase
{

    private $phil;

    public function setUp()
    {
        parent::setUp();
        $this->phil = new Phil(new Tokeniser(), new Parser(), new Evaluator());
    }


    public function testAddition()
    {
        $input = '(+ 2 2)';
        $this->assertEquals(4, $this->phil->run($input));
    }

}