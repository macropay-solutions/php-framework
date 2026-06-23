<?php

namespace App\CallablesAsArray\Jobs;

use Psr\Log\LoggerInterface;

class JobChainingExample
{
    /**
     * Usage: dispatch([JobChainingExample::class, 'stepOne', ['userId' => 1]])
     *     ->chain([[JobChainingExample::class, 'stepTwo', ['userId' => 1]]]);
     */
    public function stepOne(int $userId, LoggerInterface $logger): void
    {
        $logger->info('Chain Step 1: Validating user ' . $userId);
    }

    public function stepTwo(int $userId, LoggerInterface $logger): void
    {
        $logger->info('Chain Step 2: Activating account for user ' . $userId);
    }
}
