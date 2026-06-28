<?php

namespace App\CallablesAsArray\Jobs;

use MacropaySolutions\Kernel\Contracts\Queue\Job;
use Psr\Log\LoggerInterface;

class ExampleThrottle
{
    /**
     * Usage: dispatch([ExampleThrottle::class, 'send', ['userId' => 42]]);
     */
    public function send(int $userId, Job $job, LoggerInterface $logger): void
    {
        \app('redis')->throttle('sms-api')
            ->allow(1)->every(10)
            ->then(function () use ($userId, $logger) {
                $logger->info('Rate limit clear. SMS sent to user ' . $userId);
            }, function () use ($job, $logger) {
                $logger->warning('Rate limit hit. Releasing job back to queue for 30s.');
                $job->release(30);
            });
    }
}
