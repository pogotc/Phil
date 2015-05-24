<?php

namespace Pogotc\Phil;

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
            'mod' => function($a, $b = null) { return $b !== null ? $a % $b : false; },
            'rem' => function($a, $b = null) {
                if ($b == null) {
                    return false;
                }
                return $a - (floor($a / $b) * $b);
            },
            'inc' => function($a = null) { return $a !== null ? intval($a) + 1 : false; },
            'dec' => function($a = null) { return $a !== null ? intval($a) - 1 : false; },
            'max' => function() {
                $args = func_get_args();
                return count($args) === 0 ? false :
                    array_reduce($args, function($carry, $item) { return max($carry, $item);  }, $args[0]); },
            'min' => function() {
                $args = func_get_args();
                return count($args) === 0 ? false :
                    array_reduce($args, function($carry, $item) { return min($carry, $item);  }, $args[0]); }
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
