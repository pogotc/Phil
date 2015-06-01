<?php

namespace Pogotc\Phil;

use Pogotc\Phil\Ast\LiteralList;

class Scope
{

    private $environment;

    public function __construct()
    {
        $this->environment = array(
            '+' => function($a, $b) { return array_sum(func_get_args()); },
            '-' => function() { return array_reduce(array_slice(func_get_args(), 1), function($carry, $item) { $carry -= $item; return $carry; }, func_get_args()[0]); },
            '/' => function() { return array_reduce(array_slice(func_get_args(), 1), function($carry, $item) { $carry /= $item; return $carry; }, func_get_args()[0]); },
            '*' => function() { return array_reduce(func_get_args(), function($carry, $item) { $carry *= $item; return $carry; }, 1); },
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
                return count($args) === 0 ? false :
                    array_reduce($args, function($carry, $item) { return max($carry, $item);  }, $args[0]); },
            'min' => function() {
                $args = func_get_args();
                return count($args) === 0 ? false :
                    array_reduce($args, function($carry, $item) { return min($carry, $item);  }, $args[0]); },
            '=' => function() {
                $args = func_get_args();
                for ($i = 0; $i < count($args) - 1; $i++) {
                    if ($args[$i] != $args[$i + 1]) {
                        return false;
                    }
                }
                return true;
            },
            'not=' => function() {
                return !call_user_func_array($this->environment['='], func_get_args());
            },
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
            }
        );
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
}
