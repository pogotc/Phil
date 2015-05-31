<?php

namespace Pogotc\Phil;

use ArrayObject;

class Evaluator
{

    /**
     * @var Scope
     */
    private $scope;

    public function __construct($scope)
    {
        $this->scope = new ArrayObject($scope);
    }

    public function evaluate($ast)
    {

        $evaluationList = array();

        if (is_a($ast, "Pogotc\\Phil\\Ast\\SymbolList")) {
            $firstElem = count($ast) ? $ast[0] : false;
            if ($firstElem == 'defn') {
                $functionName = $ast[1];
                $functionArgs = $ast[2];
                $functionBody = $ast[3];
                $this->scope[$functionName] = function () use ($functionArgs, $functionBody) {
                    $args = func_get_args();
                    $localScope = $this->scope->getArrayCopy();
                    foreach ($functionArgs as $idx => $namedArg) {
                        $localScope[$namedArg] = $args[$idx];
                    }

                    $funcEvaluator = new Evaluator($localScope);
                    return $funcEvaluator->evaluate($functionBody);
                };
            } else {
                foreach ($ast as $elem) {
                    $evaluationList[] = $this->evaluate($elem);
                }
            }
        } else if (is_a($ast, "Pogotc\\Phil\\Ast\\LiteralList")) {
            return new ArrayObject($ast->getArrayCopy());;
        } else if($this->isValidSymbolInScope($ast)) {
            return $this->getValueFromScope($ast);
        } else {
            return $ast;
        }

        return $this->determineResultFromEvaluation($evaluationList);
    }

    /**
     * @param $ast
     * @return bool
     */
    private function isValidSymbolInScope($ast)
    {
        return $this->scope->offsetExists($ast);
    }

    /**
     * @param $ast
     * @return mixed
     */
    private function getValueFromScope($ast)
    {
        return $this->scope[$ast];
    }

    /**
     * @param $evaluationList
     * @return mixed|null
     */
    private function determineResultFromEvaluation($evaluationList)
    {
        if (!count($evaluationList)) {
            return null;
        } else if (is_callable($evaluationList[0])) {
            $params = array_slice($evaluationList, 1);
            $function = $evaluationList[0];
            return call_user_func_array($function, $params);
        } else if (count($evaluationList) === 1) {
            return $evaluationList[0];
        } else {
            throw new \RuntimeException('Undeclared function "' . $evaluationList[0] . '"');
        }
    }
}
