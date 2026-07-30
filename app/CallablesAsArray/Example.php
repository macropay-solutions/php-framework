<?php

namespace App\CallablesAsArray;

class Example
{
    public function __construct(protected \Psr\Log\LoggerInterface $log)
    {
    }

    /**
     * Use as:
     * [\App\CallablesAsArray\Example::class, 'methodExample', ['id' => 5]]
     */
    public function methodExample(int $id): void
    {
        $this->log->info(__CLASS__ . '::' . __FUNCTION__ . ' received id: ' . $id);
    }

    /**
     * Use as:
     * [\App\CallablesAsArray\Example::class, 'staticMethodExample', ['id' => 5]]
     */
    public static function staticMethodExample(int $id, \Psr\Log\LoggerInterface $log): void
    {
        // $this->log is not available! static methods don't trigger the object instantiation.

        $log->info(__CLASS__ . '::' . __FUNCTION__ . ' received id: ' . $id);
    }
}
