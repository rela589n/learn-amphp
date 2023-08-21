<?php

namespace App\Command;

use Amp\Future;
use Revolt\EventLoop;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Amp\async;
use function Amp\delay;

#[AsCommand(
    name: 'async:http-req',
    description: 'Add a short description for your command',
)]
class AsyncHttpReqCommand extends Command
{
    private HttpClientInterface $httpClient;

    public function __construct()
    {
        parent::__construct();

        $this->httpClient = new CurlHttpClient();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->measure($this->asyncRequests(...));
        $this->measure($this->syncRequests(...));

        return Command::SUCCESS;
    }

    private function getServiceAAsync(): Future
    {
        return async($this->getServiceA(...));
    }

    private function getServiceA(): array
    {
        $response = $this->httpClient->request('GET', 'https://127.0.0.1:8000/service/a');

        delay(0);

        return array_column($response->toArray(), 'id');
    }

    private function getServiceBAsync(): Future
    {
        return async($this->getServiceB(...));
    }

    private function getServiceB(): array
    {
        $response = $this->httpClient->request('GET', 'https://127.0.0.1:8000/service/b');

        delay(0);

        return array_column($response->toArray(), 'id');
    }

    private function syncRequests(): void
    {
        $this->getServiceA();
        $this->getServiceB();
    }

    private function measure(mixed $callback): void
    {
        $start = hrtime(true);

        $callback();

        $elapsed = hrtime(true) - $start;

        var_dump($elapsed / 1e+9);
    }

    private function asyncRequests(): void
    {
        $future1 = $this->getServiceAAsync();
        $future2 = $this->getServiceBAsync();

        $await = Future\await([$future1, $future2]);
    }
}
