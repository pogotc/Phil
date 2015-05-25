<?php


class FunctionTest extends AbstractPhilTest
{

    public function testFunctionsWithNoArguments()
    {
        $this->phil->run('(defn getOne [] 1)');
        $this->assertEquals(1, $this->phil->run(('(getOne)')));

        $this->phil->run('(defn getTwo[] 2)');
        $this->assertEquals(2, $this->phil->run(('(getTwo)')));
    }

    public function testFunctionCanCallOtherFunctions()
    {
        $this->phil->run('(defn getIncOne[] (inc 1))');
        $this->assertEquals(2, $this->phil->run(('(getIncOne)')));

        $this->phil->run('(defn doTheThings[] (* (+ 5 5) (- 10 2)))');
        $this->assertEquals(80, $this->phil->run(('(doTheThings)')));

    }
}