<?php

namespace App\Command;

use Amp\Future;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Amp\async;
use function Amp\delay;

#[AsCommand(
    name: 'async:multiply',
    description: 'Add a short description for your command',
)]
class AsyncMultiplyCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $future = asyncMultiply(11, 12);

        $result = $future->await();

        $output->writeln($result);

        return Command::SUCCESS;
    }
}

function asyncMultiply(int $a, int $b): Future
{
    return async(static function () use ($a, $b) {
        delay(1);

        $result = $a * $b;

        delay(1);

        return $result;
    });
}
