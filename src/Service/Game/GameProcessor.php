<?php
declare(strict_types=1);

namespace ForestServer\Service\Game;

use ForestServer\Api\Request\Interface\RequestInterface;
use ForestServer\Service\Config\ConfigLoader;
use ForestServer\Service\Container\ServiceContainer;
use Swoole\WebSocket\Server;

class GameProcessor
{
    private array $strategies;

    public function __construct(
        private Server $server
    ) {
        $this->strategies = $this->loadStrategies();
    }

    public function process(RequestInterface $request): void
    {
        $this->getStrategy($request)
            ->process($this->server, $request);
    }

    private function getStrategy(RequestInterface $request): GameStrategyInterface
    {
        return $this->strategies[$request->getAction()];
    }

    private function loadStrategies(): array
    {
        $config = ConfigLoader::load();

        foreach ($config['config']['processor']['strategies'] as $strategyPath) {
            /** @var GameStrategyInterface $strategy */
            $strategy = ServiceContainer::getContainer()->get($strategyPath);

            $strategies[$strategy->getType()->value] = $strategy;
        }

        return $strategies ?? [];
    }
}
