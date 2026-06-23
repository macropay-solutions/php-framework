<?php

namespace App\CallablesAsArray\Jobs;

use Psr\Log\LoggerInterface;

class TaskProcessorExample
{
    /**
     * Usage: dispatch([TaskProcessorExample::class, 'process', ['id' => 101]]);
     */
    public function process(int $id, LoggerInterface $logger): void
    {
        $logger->info("Processing standalone task or batch item for ID: {$id}");
    }
}
