<?php


class ParserTest extends AbstractPhilTest
{
    public function testNumbersReturnThemselves()
    {
        $this->assertEquals(2, $this->phil->run('(2)'));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSyntaxErrorsThrowExceptions()
    {
        $this->phil->run('(+ 1 2');
    }
}