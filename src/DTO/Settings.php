<?php
declare(strict_types=1);

namespace ForestServer\DTO;

use ForestServer\Service\Utils\Trait\ObjectTrait;

class Settings implements DTOInterface
{
    use ObjectTrait;

    protected int $workerNum;
    protected int $taskWorkerNum;
    protected int $hookFlags;
    protected int $heartbeatIdleTime;
    protected int $heartbeatCheckInterval;
    protected string $logFile;
    protected string $sslCertFile;
    protected string $sslKeyFile;
    protected bool $openHttp2Protocol;

    public function setWorkerNum(int $num): self
    {
        $this->workerNum = $num;

        return $this;
    }

    public function setTaskWorkerNum(int $num): self
    {
        $this->taskWorkerNum = $num;

        return $this;
    }

    public function setHooks(): self
    {
        $this->hookFlags = SWOOLE_HOOK_ALL;

        return $this;
    }

    public function setLogFilePath(string $path): self
    {
        $this->logFile = $path;

        return $this;
    }

    public function setSslFiles(string $certFile, string $keyFile): self
    {
        $this->sslCertFile = $certFile;
        $this->sslKeyFile = $keyFile;

        return $this;
    }

    public function setHttp2Protocol(): self
    {
        $this->openHttp2Protocol = true;

        return $this;
    }

    public function setHeartbeatIdleTime(int $time): self
    {
        $this->heartbeatIdleTime = $time;

        return $this;
    }

    public function setHeartbeatCheckInterval(int $time): self
    {
        $this->heartbeatCheckInterval = $time;

        return $this;
    }
}
