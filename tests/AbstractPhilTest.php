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
        $scope = new Scope();
        $this->phil = new Phil(new Tokeniser(), new Parser(), new Evaluator($scope->getEnvironment()));
    }
}