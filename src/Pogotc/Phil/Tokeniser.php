<?php

namespace Pogotc\Phil;

class Tokeniser
{

    public function parse($input)
    {
        $input = $this->stripNewLines($input);
        $input = $this->padBracketsWithSpaces($input);
        $input = $this->separateFunctionNameAndArgs($input);
        $tokens = $this->splitBySpaces($input);
        $result = $this->removeEmptyChars($tokens);
        $result = $this->handleStringLiterals($result);
        $result = $this->handleBooleanLiterals($result);
        return $result;
    }

    /**
     * @param $input
     * @return mixed
     */
    private function padBracketsWithSpaces($input)
    {
        return preg_replace("~('?\(|(?!\()(\)))~", ' $1 ', $input);
    }

    /**
     * @param $input
     * @return array
     */
    private function splitBySpaces($input)
    {
        preg_match_all('~"[^"]+"|\S+~', $input, $matches);
        return $matches[0];
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

    /**
     * @param $input
     */
    private function separateFunctionNameAndArgs($input)
    {
        return preg_replace('~(defn [\w\d]+)\[\]~', '$1 []', $input);
    }

    /**
     * @param $result
     * @return mixed
     */
    private function handleStringLiterals($result)
    {
        return array_map(function($item) {
            return preg_replace('~"([^"]*)"~', "$1", $item);
        }, $result);
    }

    /**
     * @param $result
     * @return mixed
     */
    private function handleBooleanLiterals($result)
    {
        return array_map(function($item) {
            return str_replace(array('true', 'false'), array(true, 0), $item);
        }, $result);
    }
}
