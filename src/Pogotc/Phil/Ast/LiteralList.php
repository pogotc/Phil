<?php


namespace Pogotc\Phil\Ast;


class LiteralList extends \ArrayObject implements Printable
{

    public function toString()
    {
        return print_r($this->getArrayCopy(), true);
    }
}