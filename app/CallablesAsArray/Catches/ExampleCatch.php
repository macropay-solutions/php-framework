<?php

namespace App\CallablesAsArray\Catches;

use Psr\Log\LoggerInterface;
use Throwable;

class ExampleCatch
{
    /**
     * Usage: dispatch([ExampleCatch::class, 'generate', ['reportId' => 5]])
     * ->catch([ExampleCatch::class, 'onFailure', ['reportId' => 5]]);
     */
    public function generate(int $reportId, LoggerInterface $logger): void
    {
        $logger->info('Attempting to generate report #' . $reportId);
        // Logic...
    }

    public function onFailure(int $reportId, Throwable $e, LoggerInterface $logger): void
    {
        $logger->error('Report #' . $reportId . ' failed execution.', ['error' => $e->getMessage()]);
    }
}
