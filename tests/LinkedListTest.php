<?php

class LinkedListTest extends AbstractPhilTest
{

    public function testLiteralLists()
    {
        $this->assertEquals(array(1, 2, 3), $this->phil->run("'(1 2 3)"));
    }

//    public function testAddItemToList()
//    {
//        $this->assertEquals('(4 1 2 3)', $this->phil->run("(cons 4 '(1 2 3))"));
//    }


}
