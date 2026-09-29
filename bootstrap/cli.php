<?php
/**
 * Use this for the parent process before pcntl_fork
 */
/** @var \App\Application $app */
$app = require_once 'app.php';

return $app->preRegisterAllAvailableBindings();