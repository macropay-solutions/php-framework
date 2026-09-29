<?php

/**
 * Use this for the child process after pcntl_fork
 */
\app()->run(App\Request::capture());
