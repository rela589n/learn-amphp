<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function Amp\async;
use function Amp\delay;

#[AsCommand(
    name: 'hello:world',
    description: 'Add a short description for your command',
)]
class HelloWorldCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $future1 = async(function () use ($output) {
            $output->write('Hello');
            delay(2);

            $output->writeln(' the future');
        });

        $future2 = async(function () use ($output) {
            $output->writeln(', World!');

            delay(1);

            $output->write('from');
        });

        $future1->await();
        $future2->await();

        return Command::SUCCESS;
    }
}
