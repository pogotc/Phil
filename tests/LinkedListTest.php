<?php


use Pogotc\Phil\Ast\LiteralList;

class LinkedListTest extends AbstractPhilTest
{

    public function testLiteralLists()
    {
        $this->assertEquals(new LiteralList(array(1, 2, 3)), $this->phil->run("'(1 2 3)"));
        $this->assertEquals(new LiteralList(array()), $this->phil->run("'()"));
        $this->assertEquals(new LiteralList(array(1.23, 2.34, 3.45)), $this->phil->run("'(1.23 2.34 3.45)"));
        $this->assertEquals(new LiteralList(array("foo", "bar")), $this->phil->run("'(\"foo\" \"bar\")"));
    }

    public function testAddItemToList()
    {
        $this->assertEquals(new LiteralList(array(4, 1, 2, 3)), $this->phil->run("(cons 4 '(1 2 3))"));
        $this->assertEquals(new LiteralList(array(4.56, 1.23, 2.34, 3.45)), $this->phil->run("(cons 4.56 '(1.23 2.34 3.45))"));
        $this->assertEquals(new LiteralList(array(1)), $this->phil->run("(cons 1 '())"));
        $this->assertEquals(new LiteralList(array("foo")), $this->phil->run("(cons \"foo\" '())"));
        $this->assertEquals(new LiteralList(array("foo", "bar")), $this->phil->run("(cons \"foo\" '(\"bar\"))"));
    }

    public function testMap()
    {
        $result = $this->phil->run("(map inc '(1 2 3))");
        $expectedResult = new LiteralList(array(2, 3, 4));
        $this->assertEquals($expectedResult, $result);

        $result = $this->phil->run("(map inc '(1.23 2.34 3.45))");
        $expectedResult = new LiteralList(array(2.23, 3.34, 4.45));
        $this->assertEquals($expectedResult, $result);
    }

    public function testCount()
    {
        $this->assertEquals(3, $this->phil->run("(count '(1 2 3))"));
        $this->assertEquals(6, $this->phil->run("(count \"foobar\")"));
        $this->assertEquals(0, $this->phil->run("(count \"\")"));
    }

    public function testRest()
    {
        $this->assertEquals("oobar", $this->phil->run('(rest "foobar")'));
        $this->assertEquals(new LiteralList(array(2, 3, 4)), $this->phil->run("(rest '(1 2 3 4))"));
        $this->assertNull($this->phil->run('(rest "")'));
        $this->assertNull($this->phil->run("(rest '())"));
    }

    public function testFirst()
    {
        $this->assertEquals("f", $this->phil->run('(first "foobar")'));
        $this->assertEquals(1, $this->phil->run("(first '(1 2 3))"));
        $this->assertNull($this->phil->run('(first "")'));
        $this->assertNull($this->phil->run("(first '())"));
    }
}
