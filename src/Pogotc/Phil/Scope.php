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
            '*' => function() { return array_reduce(func_get_args(), function($carry, $item) { $carry *= $item; return $carry; }, 1); }
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
