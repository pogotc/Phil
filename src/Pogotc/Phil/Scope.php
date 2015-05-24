<?php

namespace Pogotc\Phil;

class Scope
{

    private $environment;

    public function __construct()
    {
        $this->environment = array(
            '+' => function($a, $b) { return array_sum(func_get_args()); }
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
