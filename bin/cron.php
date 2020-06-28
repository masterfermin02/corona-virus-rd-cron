#!/usr/bin/php
<?php

require __DIR__ . '/../src/Bootstrap.php';

use App\Console\Commands\CronManager;

$param    = isset( $argv[1] ) ? $argv[1] : '';
$filename = isset( $argv[2] ) ? $argv[2] : '';

$model = new \App\Console\Model([
    'on' => $_ENV['SCRIPT_ON'],
    'off' => $_ENV['SCRIPT_OFF'],
    'param' => $param,
    'filename' => $filename
]);

$commands = [
    new \App\Console\Commands\ActiveCron($_ENV['SCRIPT_ACTIVE']),
    new \App\Console\Commands\InActiveCron($_ENV['SCRIPT_INACTIVE']),
    new \App\Console\Commands\TurnOnOffCron($model)
];

CronManager::run($param, $commands);

exit();
