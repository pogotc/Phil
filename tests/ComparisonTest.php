<?php


class ComparisonTest extends AbstractPhilTest
{

    public function testEquality()
    {
        $this->assertTrue($this->phil->run('(= 2 2)'));
        $this->assertTrue($this->phil->run('(= 2 2 2)'));
        $this->assertFalse($this->phil->run('(= 4 2)'));
        $this->assertFalse($this->phil->run('(= 4 2 2)'));
    }

    public function testInequality()
    {
        $this->assertFalse($this->phil->run('(not= 2 2)'));
        $this->assertFalse($this->phil->run('(not= 2 2 2)'));
        $this->assertTrue($this->phil->run('(not= 4 2 2)'));
    }
}