<?php


namespace Pogotc\Phil\Ast;


class Map extends \ArrayObject
{

    public static function fromLiteralList(LiteralList $literalList)
    {
        $list = $literalList->getArrayCopy();

        if (count($list) % 2 !== 0) {
            throw new \RuntimeException('Odd number of hash map arguments');
        }

        $map = new Map();
        $listCount = count($list);
        for ($i = 0; $i < $listCount; $i += 2) {
            $key = str_replace('"', '', $list[$i]);
            $value = $list[$i + 1];
            $map[$key] = $value;
        }
        return $map;
    }

    public function call($params)
    {
        return $this->offsetExists($params[0]) ? $this->offsetGet($params[0]) : false;
    }

    public function __toString()
    {
        return print_r($this->getArrayCopy(), true);
    }
}