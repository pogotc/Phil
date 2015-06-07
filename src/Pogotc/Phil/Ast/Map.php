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
        for ($i = 0; $i < count($list); $i+=2) {
            $key = str_replace('"', '', $list[$i]);
            $value = $list[$i + 1];
            $map[$key] = $value;
        }
        return $map;
    }
}