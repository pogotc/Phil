<?php

namespace Pogotc\Phil;

class Evaluator
{

    private $symbolTable;

    public function __construct()
    {
        $this->symbolTable = array(
            '+' => function($a, $b) { return array_sum(func_get_args()); }
        );
    }

    public function evaluate($ast)
    {
//        if (count($ast) === 0) {
//            return null;
//        }

        $el = array();

        if (is_array($ast)) {
            foreach($ast as $elem) {
                $el[]= $this->evaluate($elem);
            }
        } else if(array_key_exists($ast, $this->symbolTable)) {
            return $this->symbolTable[$ast];
        } else {
            return $ast;
        }

        if (!count($el)) {
            return null;
        } else if (is_callable($el[0])) {
            return call_user_func_array($el[0], array_slice($el, 1));
        } else {
            return $el[0];
        }
    }
}
