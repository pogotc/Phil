<?php


class LinkedListTest extends AbstractPhilTest
{

    public function testLiteralLists()
    {
        $this->assertEquals(new \ArrayObject(array(1, 2, 3)), $this->phil->run("'(1 2 3)"));
        $this->assertEquals(new \ArrayObject(array()), $this->phil->run("'()"));
    }

    public function testAddItemToList()
    {
        $this->assertEquals(new \ArrayObject(array(4, 1, 2, 3)), $this->phil->run("(cons 4 '(1 2 3))"));
        $this->assertEquals(new \ArrayObject(array(1)), $this->phil->run("(cons 1 '())"));
    }

    public function testMap()
    {
        $result = $this->phil->run("(map inc '(1 2 3))");
        $expectedResult = new ArrayObject(array(2, 3, 4));

        $this->assertEquals($expectedResult, $result);
    }

}
