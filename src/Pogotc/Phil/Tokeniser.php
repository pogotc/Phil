<?php

namespace Pogotc\Phil;

class Tokeniser
{

    public function parse($input)
    {
        $input = $this->stripNewLines($input);
        $input = $this->padBracketsWithSpaces($input);
        $tokens = $this->splitBySpaces($input);
        $result = $this->removeEmptyChars($tokens);
        return $result;
    }

    /**
     * @param $input
     * @return mixed
     */
    private function padBracketsWithSpaces($input)
    {
        return str_replace(array("(", ")"), array("( ", " )"), $input);
    }

    /**
     * @param $input
     * @return array
     */
    private function splitBySpaces($input)
    {
        return explode(' ', $input);
    }

    /**
     * @param $tokens
     * @return array
     */
    private function removeEmptyChars($tokens)
    {
        return array_values(array_filter($tokens, function ($token) {
            return $token !== '';
        }));
    }

    /**
     * @param $input
     * @return mixed
     */
    private function stripNewLines($input)
    {
        return str_replace(array("\r", "\n"), " ", $input);
    }
}
