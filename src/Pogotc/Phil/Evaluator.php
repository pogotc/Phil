<?php

namespace Pogotc\Phil;

use ArrayObject;

class Evaluator
{

    /**
     * @var \ArrayObject
     */
    private $scope;

    /**
     * @var Phil
     */
    private $phil;

    public function __construct($scope)
    {
        $this->scope = new ArrayObject($scope);
    }

    public function evaluate($ast)
    {
        if ($this->isUnevaluatable($ast)) {
            return null;
        }

        if ($this->isASymbolList($ast)) {
            return $this->evaluateSymbolList($ast);
        } else if ($this->isALiteralList($ast)) {
            return $ast;
        } else if($this->isValidSymbolInScope($ast)) {
            return $this->getValueFromScope($ast);
        } else {
            return $ast;
        }


    }

    /**
     * @param $ast
     * @return bool
     */
    private function isUnevaluatable($ast)
    {
        return is_array($ast) && !count($ast);
    }

    /**
     * @param $ast
     * @return bool
     */
    private function isASymbolList($ast)
    {
        return is_a($ast, "Pogotc\\Phil\\Ast\\SymbolList");
    }

    /**
     * @param $ast
     * @return bool
     */
    private function isALiteralList($ast)
    {
        return is_a($ast, "Pogotc\\Phil\\Ast\\LiteralList");
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
     * @param Phil $phil
     */
    public function setPhilInterpreter($phil)
    {
        $this->phil = $phil;
    }
    /**
     * @param $ast
     * @return mixed|null
     */
    private function evaluateSymbolList($ast)
    {
        $firstElem = count($ast) ? $ast[0] : false;
        if ($this->isFunctionDeclaration($firstElem)) {
            $this->evaluateFunction($ast);
        } elseif ($this->isIfConditional($firstElem)) {
            return $this->evaluateIfConditional($ast);
        } elseif ($this->isDoBlock($firstElem)) {
            return $this->evaluateDoBlock($ast);
        } elseif ($this->isLoadFileDeclaration($firstElem)) {
            return $this->evaluateLoadFile($ast);
        } else {
            return $this->evaluateAst($ast);
        }
    }

    /**
     * @param $firstElem
     * @return bool
     */
    private function isFunctionDeclaration($firstElem)
    {
        return $firstElem == 'defn';
    }

    /**
     * @param $ast
     */
    private function evaluateFunction($ast)
    {
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
    }

    /**
     * @param $firstElem
     * @return bool
     */
    private function isIfConditional($firstElem)
    {
        return $firstElem == 'if';
    }

    /**
     * @param $ast
     * @return mixed|null
     */
    private function evaluateIfConditional($ast)
    {
        $predicate = $this->evaluate($ast[1]);
        if ($predicate) {
            return $this->evaluate($ast[2]);
        } else {
            return $this->evaluate($ast[3]);
        }
    }

    /**
     * @param $firstElem
     * @return bool
     */
    private function isDoBlock($firstElem)
    {
        return $firstElem == 'do';
    }

    /**
     * @param $ast
     * @return mixed|null
     */
    private function evaluateDoBlock($ast)
    {
        $astArray = $ast->getArrayCopy();
        $astsToDo = array_slice($astArray, 1, -1);
        if (count($astsToDo)) {
            foreach ($astsToDo as $astToDo) {
                $this->evaluate($astToDo);
            }
        }
        $finalAst = $astArray[count($astArray) - 1];
        return $this->evaluate($finalAst);
    }

    /**
     * @param $firstElem
     * @return bool
     */
    private function isLoadFileDeclaration($firstElem)
    {
        return $firstElem == 'load-file';
    }

    /**
     * @param $ast
     * @return mixed|null
     */
    private function evaluateLoadFile($ast)
    {
        $path = $ast[1];
        $command = sprintf('(do %s)', file_get_contents($path));
        return $this->phil->run($command);
    }

    /**
     * @param $ast
     * @return mixed|null
     */
    private function evaluateAst($ast)
    {
        $evaluationList = array();
        foreach ($ast as $elem) {
            $evaluationList[] = $this->evaluate($elem);
        }
        return $this->determineResultFromEvaluation($evaluationList);
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
