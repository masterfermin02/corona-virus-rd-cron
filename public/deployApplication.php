<?php declare(strict_types=1);

require __DIR__ . '/../src/Bootstrap.php';

use App\Input;
use CloudWays\Deploy\Client;
use CloudWays\Requester;
use CloudWays\Server;

$apiKey = $_ENV['API_KEY'];
$API_URL = $_ENV['API_URL'];
$email = $_ENV['DEPLOYMENT_EMAIL'];
$input = Input::create(array_merge($_GET, $_POST));


$gitPullResponse = Client::create($email, $apiKey, Requester::create($API_URL))
->execute(Server::create(
    $input->get('server_id'),
    $input->get('git_url'),
    $input->get('branch_name'),
    $input->get('app_id')
));

echo (json_encode($gitPullResponse));
