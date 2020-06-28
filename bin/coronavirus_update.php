#!/usr/bin/php
<?php declare(strict_types=1);

(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('cli only');

require __DIR__ . '/../src/Bootstrap.php';

use Kreait\Firebase\Factory;

$coronaVirus = new \App\CoronaVirus\CoronaVirus(new \App\CoronaVirus\CoronaVirusApi());


$factory = (new Factory)->withServiceAccount(__DIR__ . '/../' . $_ENV['FIREBASE_CREDENTIALS']);
$database = $factory->createDatabase();
$service = new \App\FireBase\CoronaVirusFirebaseService($database, 'provincesStat/');
$CoronaVirusFirebase = new \App\CoronaVirus\CoronaVirusFireBase($coronaVirus, $service);

$CoronaVirusFirebase->update();
