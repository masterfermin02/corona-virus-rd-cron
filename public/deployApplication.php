<?php

declare(strict_types=1);

require __DIR__ . '/../src/Bootstrap.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$API_KEY = $_ENV['FIREBASE_CREDENTIALS'];
const API_URL = "https://api.cloudways.com/api/v1";
$EMAIL = $_ENV['DEPLOYMENT_EMAIL'];

const BranchName = "master";
const GitUrl = "git@bitbucket.org:user22/repo_name.git";

//Use this function to contact CW API
function callCloudwaysAPI($method, $url, $accessToken, $post = [])
{
    $baseURL = API_URL;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_URL, $baseURL . $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //Set Authorization Header
    if ($accessToken) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
    }

    //Set Post Parameters
    $encoded = '';
    if (count($post)) {
        foreach ($post as $name => $value) {
            $encoded .= urlencode($name) . '=' . urlencode($value) . '&';
        }
        $encoded = substr($encoded, 0, strlen($encoded) - 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded);
        curl_setopt($ch, CURLOPT_POST, 1);
    }
    $output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpcode != '200') {
        die('An deploy error occurred code: ' . $httpcode . ' output: ' . substr($output, 0, 10000));
    }
    curl_close($ch);
    return json_decode($output);
}

//Fetch Access Token
$tokenResponse = callCloudwaysAPI('POST', '/oauth/access_token', null
    , [
        'email' => $EMAIL,
        'api_key' => $API_KEY
    ]);

$accessToken = $tokenResponse->access_token;
$gitPullResponse = callCloudWaysAPI('POST', '/git/pull', $accessToken, [
    'server_id' => $_GET['server_id'],
    'app_id' => $_GET['app_id'],
    'git_url' => $_GET['git_url'],
    'branch_name' => $_GET['branch_name']
    /* Uncomment it if you want to use deploy path, Also add the new parameter in your link
    'deploy_path' => $_GET['deploy_path']
    */
]);

echo (json_encode($gitPullResponse));
