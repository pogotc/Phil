<?php


class StringTest extends AbstractPhilTest
{

    public function testConcatenation()
    {
        $this->assertEquals("foobar", $this->phil->run('(+ "foo" "bar")'));
        $this->assertEquals("foobarbaz", $this->phil->run('(+ "foo" "bar" "baz")'));
        $this->assertEquals("foo", $this->phil->run('(+ "foo" "")'));
        $this->assertEquals("bar", $this->phil->run('(+ "" "bar")'));
        $this->assertEquals("", $this->phil->run('(+ "" "")'));
    }

    public function testPrint()
    {
        ob_start();
        $this->phil->run('(println "Hello World.")');
        $result = ob_get_contents();
        ob_end_clean();

        $this->assertEquals("Hello World.\n", $result);
    }
}