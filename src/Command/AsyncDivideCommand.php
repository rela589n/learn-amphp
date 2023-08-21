<?php

namespace App\Command;

use Amp\Future;
use Error;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Amp\async;
use function Amp\delay;

#[AsCommand(
    name: 'async:divide',
    description: 'Add a short description for your command',
)]
class AsyncDivideCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = asyncDivide(10, 0);

        try {
            $result->await();
        } catch (Error $e) {
            dd($e::class);
        }

        return 0;
    }
}

function asyncDivide(int $a, int $b): Future
{
    return async(static function () use ($a, $b) {
        delay(1);

        $result = $a / $b;

        delay(1);

        return $result;
    });
}

