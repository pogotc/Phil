<?php


class FunctionTest extends AbstractPhilTest
{

    public function testFunctionsWithNoArguments()
    {
        $this->phil->run('(defn getOne () 1)');
        $this->assertEquals(1, $this->phil->run(('(getOne)')));

        $this->phil->run('(defn getTwo () 2)');
        $this->assertEquals(2, $this->phil->run(('(getTwo)')));
    }

    public function testFunctionCanCallOtherFunctions()
    {
        $this->phil->run('(defn getIncOne () (inc 1))');
        $this->assertEquals(2, $this->phil->run(('(getIncOne)')));

        $this->phil->run('(defn doTheThings () (* (+ 5 5) (- 10 2)))');
        $this->assertEquals(80, $this->phil->run(('(doTheThings)')));

    }

    public function testFunctionsCanReadArgs()
    {
        $this->phil->run('(defn returnArg (a) a)');
        $this->assertEquals(5, $this->phil->run(('(returnArg 5)')));
        $this->assertEquals(10, $this->phil->run(('(returnArg 10)')));

        // verifies 'a' is kept in local and not global scope
        $this->assertEquals(1, $this->phil->run(('(+ 1 a)')));


        $this->phil->run('(defn addNumbers (a b c) (+ a b c))');
        $this->assertEquals(6, $this->phil->run(('(addNumbers 1 2 3)')));
    }
}