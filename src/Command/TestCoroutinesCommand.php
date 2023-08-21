<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Amp\async;
use function Amp\delay;

#[AsCommand(
    name: 'test:coroutines',
    description: 'Add a short description for your command',
)]
class TestCoroutinesCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $future1 = async(static function () {
            for ($i = 0; $i < 5; $i++) {
                echo '.';
                delay(1);
            }
        });

        $future2 = async(static function () {
            for ($i = 0; $i < 5; $i++) {
                echo '_';
                delay(0.5);
            }
        });

        $future1->await();
        $future2->await();
        
        return Command::SUCCESS;
    }
}
