<?php


class MapTest extends AbstractPhilTest
{

    public function testSimpleMap()
    {
        $this->phil->run('(def stringmap {"a" 1, "b" 2, "c" 3})');
        $this->assertEquals(1, $this->phil->run('(stringmap "a")'));
    }
}