<?php declare(strict_types=1);

(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('cli only');

require __DIR__ . '/../src/Bootstrap.php';

$coronaVirus = new \App\CoronaVirus\CoronaVirus(new \App\CoronaVirus\CoronaVirusApi());
use Kreait\Firebase\Factory;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$factory = (new Factory)->withServiceAccount(__DIR__ . '/../' . $_ENV['FIREBASE_CREDENTIALS']);
$database = $factory->createDatabase();
$service = new \App\FireBase\CoronaVirusFirebaseService($database, 'provincesStat/');
$CoroVirusFirebase = new \App\CoronaVirus\CoronaVirusFireBase($coronaVirus, $service);

$CoroVirusFirebase->update();
