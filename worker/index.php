<?php

/**
 * Use this for the child process after pcntl_fork
 */
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$kernel = \MacropaySolutions\Kernel\Container\Container::getInstance()
    ->make(\MacropaySolutions\Kernel\Contracts\Console\Kernel::class);

exit($kernel->handle(new ArgvInput(), new ConsoleOutput()));
