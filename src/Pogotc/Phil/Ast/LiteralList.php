<?php


namespace Pogotc\Phil\Ast;


class LiteralList extends \ArrayObject
{

    public function __toString()
    {
        return print_r($this->getArrayCopy(), true);
    }
}