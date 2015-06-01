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

    public function testEdgeCases()
    {
        $this->assertNull($this->phil->run(''));
    }

    public function testReverseString()
    {
        $this->phil->run("(defn revStr (n)
                            (if
                                (= 0 (count n))
                                \"\"
                                (+
                                    (revStr (rest n))
                                    (first n))
                                )
                            )");

        $this->assertEquals("olleh", $this->phil->run('(revStr "hello")'));
    }
}