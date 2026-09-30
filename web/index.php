<?php

/**
 * Use this for the child process after pcntl_fork
 * @see \MacropaySolutions\Framework\Concerns\RoutesRequests::run()
 */
\MacropaySolutions\Kernel\Container\Container::getInstance()->run(App\Request::capture());
