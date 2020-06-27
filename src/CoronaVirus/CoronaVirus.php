<?php


namespace App\CoronaVirus;

use App\Interfaces\GetEnable;
use App\Interfaces\Api;

class CoronaVirus implements GetEnable
{
    protected $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function getData(): array
    {
        $result = $this->api->connectApi();

        if (!isset($result['error'])) {
            return $result;
        }

        return array('error' => true, 'message' => 'not found');
    }
}
