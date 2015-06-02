<?php

namespace Pogotc\Phil;

use Pogotc\Phil\Ast\LiteralList;

class Scope
{

    private $environment;

    public function __construct()
    {
        $this->environment = array();

        $this->addCoreArithmeticFunctions();
        $this->addMathsFunctions();
        $this->addComparisonFunctions();
        $this->addListFunctions();
        $this->addOutputFunctions();
    }

    private function addCoreArithmeticFunctions()
    {
        $this->environment = array_merge($this->environment, array(
            '+' => function () {
                $args = func_get_args();
                if (count($args)) {
                    if (is_string($args[0])) {
                        return implode("", $args);
                    } else {
                        return array_sum($args);
                    }
                } else {
                    return null;
                }
            },
            '-' => function () {
                return $this->reduceOverArgsWithFirstAsInitial(func_get_args(), "-");
            },
            '/' => function () {
                return $this->reduceOverArgsWithFirstAsInitial(func_get_args(), "/");
            },
            '*' => function () {
                $initial = 1;
                $args = func_get_args();
                $operation = "*";
                return $this->reduceOverArgs($args, $operation, $initial);
            }
        ));
    }

    private function reduceOverArgsWithFirstAsInitial($funcArgs, $operation)
    {
        $args = array_slice($funcArgs, 1);
        $initial = $funcArgs[0];
        return $this->reduceOverArgs($args, $operation, $initial);
    }

    private function addMathsFunctions()
    {
        $this->environment = array_merge($this->environment, array(
            'quot' => function() {
                return $this->runIfXArgsNotNull(2, func_get_args(), function($args){
                    $a = $args[0];
                    $b = $args[1];

                    $div = $a / $b;
                    return $div > 0 ? floor($div) : ceil($div);
                });

            },
            'mod' => function() {
                return $this->runIfXArgsNotNull(2, func_get_args(), function($args){
                    $a = $args[0];
                    $b = $args[1];

                    return ($a % $b) + ($a < 0 ? $b : 0);
                });
            },
            'rem' => function($a, $b = null) {
                return $this->runIfXArgsNotNull(2, func_get_args(), function($args){
                    return $args[0] % $args[1];
                });
            },
            'inc' => function() {
                return $this->runIfXArgsNotNull(1, func_get_args(), function($args){
                     return $args[0] + 1;
                });
            },
            'dec' => function($a = null) {
                return $this->runIfXArgsNotNull(1, func_get_args(), function($args){
                    return $args[0] - 1;
                });
            },
            'max' => function() {
                $args = func_get_args();
                return $this->reduceOverArgs($args, "max", $args[0]);
            },
            'min' => function() {
                $args = func_get_args();
                return $this->reduceOverArgs($args, "min", $args[0]);

            }
        ));
    }

    private function runIfXArgsNotNull($x, $args, $callback) {
        $argsToCheck = array_slice($args, 0, min($x, count($args)));
        if (count($argsToCheck) < $x) {
            return false;
        }
        foreach ($argsToCheck as $arg) {
            if ($arg === null) {
                return false;
            }
        }
        return $callback($argsToCheck);
    }

    private function addComparisonFunctions()
    {
        $this->environment = array_merge($this->environment, array(
            '=' => function() {
                $args = func_get_args();
                for ($i = 0; $i < count($args) - 1; $i++) {
                    if ($args[$i] != $args[$i + 1]) {
                        return false;
                    }
                }
                return true;
            },
            '>' => function($a, $b) {
                return $a > $b;
            },
            'not=' => function() {
                return !call_user_func_array($this->environment['='], func_get_args());
            }
        ));
    }

    private function addListFunctions()
    {
        $this->environment = array_merge($this->environment, array(
            'cons' => function($elem, $list) {
                $listArray = $list->getArrayCopy();
                array_unshift($listArray, $elem);
                return new LiteralList($listArray);
            },
            'map' => function($mappingFunction, $list) {
                $listArray = $list->getArrayCopy();
                $mappedArray = array_map($mappingFunction, $listArray);
                return new LiteralList($mappedArray);
            },
            'count' => function($elem) {
                if (is_string($elem)) {
                    return strlen($elem);
                } else {
                    return count($elem);
                }
            },
            'rest' => function($elem) {
                if ($this->isValidNonEmptyString($elem)) {
                    return substr($elem, 1);
                } else if ($this->isValidNonEmptyArrayObject($elem)) {
                    return new LiteralList(array_slice($elem->getArrayCopy(), 1));
                } else {
                    return null;
                }
            },
            'first' => function($elem) {
                if ($this->isValidNonEmptyString($elem)) {
                    return substr($elem, 0, 1);
                } else if ($this->isValidNonEmptyArrayObject($elem)) {
                    return $elem[0];
                } else {
                    return null;
                }
            }
        ));
    }

    private function addOutputFunctions()
    {
        $this->environment = array_merge($this->environment, array(
            'println' => function($elem) {
                print($elem);
                print("\n");
            }
        ));
    }

    public function call($functionName, $params)
    {
        return call_user_func_array($this->environment[$functionName], $params);
    }

    /**
     * @return array
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param $args
     * @param $operation
     * @param $initial
     * @return mixed
     */
    private function reduceOverArgs($args, $operation, $initial)
    {
        if (count($args) === 0) {
            return false;
        }

        return array_reduce($args, function ($carry, $item) use ($operation) {
            switch ($operation) {
                case "-":
                    $carry -= $item;
                    break;
                case "/":
                    $carry /= $item;
                    break;
                case "*":
                    $carry *= $item;
                    break;
                default:
                    $carry = $operation($carry, $item);
                    break;
            }

            return $carry;
        }, $initial);
    }

    /**
     * @param $elem
     * @return bool
     */
    private function isValidNonEmptyString($elem)
    {
        return is_string($elem) && strlen($elem) > 0;
    }

    /**
     * @param $elem
     * @return bool
     */
    private function isValidNonEmptyArrayObject($elem)
    {
        return is_a($elem, "\ArrayObject") && count($elem) > 0;
    }
}
