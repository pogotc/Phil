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
            'quot' => function($a = null, $b = null) {
                if ($a == null || $b == null) {
                    return false;
                }
                $div = $a / $b;
                return $div > 0 ? floor($div) : ceil($div);
            },
            'mod' => function($a, $b = null) { return $b !== null ? ($a % $b) + ($a < 0 ? $b : 0) : false; },
            'rem' => function($a, $b = null) {
                if ($b == null) {
                    return false;
                }
                return $a % $b;
            },
            'inc' => function($a = null) { return $a !== null ? $a + 1 : false; },
            'dec' => function($a = null) { return $a !== null ? $a - 1 : false; },
            'max' => function() {
                $args = func_get_args();
                $initial = $args[0];
                $operation = "max";
                if (count($args) === 0) {
                    return false;
                }
                return $this->reduceOverArgs($args, $operation, $initial);
            },
            'min' => function() {
                $args = func_get_args();
                $initial = $args[0];
                $operation = "min";
                if (count($args) === 0) {
                    return false;
                }
                return $this->reduceOverArgs($args, $operation, $initial);

            }
        ));
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
