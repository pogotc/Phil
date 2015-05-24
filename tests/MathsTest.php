<?php

use Pogotc\Phil\Phil;

class MathsTest extends \PHPUnit_Framework_TestCase
{

    public function testAddition()
    {
        $input = '(+ 2 2)';
        $phil = new Phil();
        $this->assertEquals(4, $phil->run($input));
    }

}