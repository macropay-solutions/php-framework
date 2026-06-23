<?php

namespace App\CallablesAsArray\Jobs;

use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Psr\Log\LoggerInterface;

class EncryptedExample implements ShouldBeEncrypted
{
    /**
     * Usage: dispatch([EncryptedExample::class, 'handle', ['empId' => 99, 'amount' => 5000]]);
     */
    public function handle(int $empId, int $amount, LoggerInterface $logger): void
    {
        $logger->info('Securely processing payroll for Employee #' . $empId);
    }
}
