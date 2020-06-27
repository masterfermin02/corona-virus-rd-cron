<?php declare(strict_types=1);

require __DIR__ . '/../src/Bootstrap.php';

$coronaVirus = new \App\CoronaVirus\CoronaVirus(new \App\CoronaVirus\CoronaVirusApi());
use Kreait\Firebase\Factory;

$factory = (new Factory)->withServiceAccount(__DIR__ . '/../coronavirus-rd-dev-bfe74aec1f2a.json');
$database = $factory->createDatabase();
$service = new \App\FireBase\CoronaVirusFirebaseService($database, 'provincesStat/');
$CoroVirusFirebase = new \App\CoronaVirus\CoronaVirusFireBase($coronaVirus, $service);

$CoroVirusFirebase->update();
//print_r();
