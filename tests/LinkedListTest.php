<?php


use Pogotc\Phil\Ast\LiteralList;

class LinkedListTest extends AbstractPhilTest
{

    public function testLiteralLists()
    {
        $this->assertEquals(new LiteralList(array(1, 2, 3)), $this->phil->run("'(1 2 3)"));
        $this->assertEquals(new LiteralList(array()), $this->phil->run("'()"));
        $this->assertEquals(new LiteralList(array(1.23, 2.34, 3.45)), $this->phil->run("'(1.23 2.34 3.45)"));
    }

    public function testAddItemToList()
    {
        $this->assertEquals(new LiteralList(array(4, 1, 2, 3)), $this->phil->run("(cons 4 '(1 2 3))"));
        $this->assertEquals(new LiteralList(array(4.56, 1.23, 2.34, 3.45)), $this->phil->run("(cons 4.56 '(1.23 2.34 3.45))"));
        $this->assertEquals(new LiteralList(array(1)), $this->phil->run("(cons 1 '())"));
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

}
