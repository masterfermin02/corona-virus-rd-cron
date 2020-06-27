<?php


namespace App\CoronaVirus;

use App\Interfaces\Api;

class CoronaVirusApi implements  Api
{
    protected $apiUrl = 'https://covid-rd.now.sh/api/boletin';
    protected $headerVars = array();

    /**
     *
     * @return array
     */
    public function connectApi(): array
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $this->headerVars,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return array('error' => true, 'message' => "cURL Error #:" . $err);
        }

        return [
            'response' => $response
        ];

    }
}
