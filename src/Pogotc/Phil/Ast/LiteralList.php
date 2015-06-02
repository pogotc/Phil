<?php


namespace Pogotc\Phil\Ast;


class LiteralList extends \ArrayObject implements Printable
{

    public function toString()
    {
        return var_dump($this->getArrayCopy());
    }
}