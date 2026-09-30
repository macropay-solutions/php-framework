<?php
/**
 * Use this for the parent process before pcntl_fork
 */
/** @var \App\Application $app */
$app = require_once __DIR__ . '/app.php';

return $app->preRegisterAllAvailableBindings();
