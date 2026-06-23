<?php

namespace App\CallablesAsArray\Listeners;

class ExampleEventListener
{
    /**
     * Usage in EventServiceProvider:
     * Event::listen(OrderCreated::class, queueableArray([ExampleEventListener::class, 'reserveStock']));
     */
    public function reserveStock(array $payload, \Psr\Log\LoggerInterface $logger): void
    {
        $logger->info('Event received: Reserving stock for Order #' . ($payload['order_id'] ?? 'unknown'));
    }
}
