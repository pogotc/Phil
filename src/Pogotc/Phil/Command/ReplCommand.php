<?php

namespace Pogotc\Phil\Command;

use Pogotc\Phil\Evaluator;
use Pogotc\Phil\Parser;
use Pogotc\Phil\Phil;
use Pogotc\Phil\Scope;
use Pogotc\Phil\Tokeniser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReplCommand extends Command
{

    protected function configure()
    {
        $this->setName('repl')
             ->setDescription('Run the PHIL REPL');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scope = new Scope();
        $phil = new Phil(new Tokeniser(), new Parser(), new Evaluator($scope->getEnvironment()));

        do {
            $line = readline("PHIL> ");
            $output->writeln($phil->run($line));
        } while (true);
    }
}