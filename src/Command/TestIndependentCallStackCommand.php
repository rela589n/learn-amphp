<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Amp\async;
use function Amp\delay;

#[AsCommand(
    name: 'test:independent-call-stack',
    description: 'Add a short description for your command',
)]
class TestIndependentCallStackCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        async(function () {
            print '++ Executing callback passed to async()'.PHP_EOL;

            delay(3);

            print '++ Finished callback passed to async()'.PHP_EOL;
        });

        print '++ Suspending to event loop...'.PHP_EOL;

        delay(5);

        print '++ Script end'.PHP_EOL;

        return 0;
    }
}
