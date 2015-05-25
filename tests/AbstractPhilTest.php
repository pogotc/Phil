<?php

use Pogotc\Phil\Evaluator;
use Pogotc\Phil\Parser;
use Pogotc\Phil\Phil;
use Pogotc\Phil\Scope;
use Pogotc\Phil\Tokeniser;


abstract class AbstractPhilTest extends  \PHPUnit_Framework_TestCase
{

    /* @var Pogotc\Phil\Phil */
    protected $phil;

    public function setUp()
    {
        parent::setUp();
        $this->phil = new \Pogotc\Phil\Phil(new \Pogotc\Phil\Tokeniser(), new \Pogotc\Phil\Parser(), new \Pogotc\Phil\Evaluator(new \Pogotc\Phil\Scope()));
    }
}