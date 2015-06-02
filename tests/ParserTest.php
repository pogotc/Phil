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

    public function testDoConstruct()
    {
        $code = '(do (println "Hello, World.")(println "Hello again"))';
        $expectedOutput = "Hello, World.\nHello again\n";

        ob_start();
        $this->phil->run($code);
        $actualOutput = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expectedOutput, $actualOutput);
    }


    public function testMultlineFiles()
    {
        ob_start();
        $this->phil->run('(load-file "'.__DIR__."/_fixtures/helloworld.phil".'")');
        $result = ob_get_contents();
        ob_end_clean();
        $expectedOutput = "Hello, World.\nHello again\n";

        $this->assertEquals($expectedOutput, $result);

    }
}