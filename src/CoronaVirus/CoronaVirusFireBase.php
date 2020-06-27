<?php


namespace App\CoronaVirus;

use App\Interfaces\UpdateEnable;
use App\Interfaces\GetEnable;
use App\Interfaces\DataBase;
use function Slash\sortBy;
use function Slash\comparator;
use function Slash\getWith;
use function Slash\useWith;

class CoronaVirusFireBase implements UpdateEnable
{
    protected $api;
    protected $dataBase;
    const STATS_REFERENCE = 'stats';
    const PROVINCES_REFERENCE = 'province/provinces';

    public function __construct(GetEnable $api, DataBase $dataBase)
    {
        $this->api = $api;
        $this->dataBase = $dataBase;
    }

    public function update()
    {
        $provinceStat = $this->dataBase->gets();
        if ($this->isUptoDate($provinceStat['lastUpdate'])) {
            echo 'Firebase is up to date';
            return;
        }
        $result = $this->api->getData();
        $response = json_decode($result['response']);
        if (!$this->isUptoDate($response->lastUpdated)) {
            echo 'The API is not up to date';
            return;
        }
        $statKey = count($provinceStat[self::STATS_REFERENCE]);
        $this->dataBase->update([
            'lastUpdate' => $response->lastUpdated,
            'cases' => $this->replaceComma($response->totalConfirmed),
            'deaths' => $this->replaceComma($response->totalDeaths),
            'recoverers' => $this->replaceComma($response->totalRecovered),
            self::STATS_REFERENCE . '/' . $statKey => [
                'date'       => date('d/m/Y', strtotime($response->lastUpdated)),
                'deaths'     => $this->replaceComma($response->totalDeaths) - intval ($provinceStat['deaths']),
                'infects'    => $this->replaceComma($response->totalConfirmed) - intval ($provinceStat['cases']),
                'recoverers' => $this->replaceComma($response->totalRecovered) - intval ($provinceStat['recoverers']),
            ]
        ]);
        $provinces = $provinceStat['province']['provinces'];
        $lastUpdate = date('Y/m/d', strtotime($response->lastUpdated));
        $descending = comparator('Slash\greaterThan');
        $dateToTime = useWith('strtotime', getWith('date'));
        $descendingByDate = useWith($descending, $dateToTime, $dateToTime);
        $sortByDate = function($cases) use ($descendingByDate) {
            return sortBy($descendingByDate)($cases);
        };
        foreach ($response->provinces as $province) {
            foreach ($provinces as $key => $firebaseProvince) {
                if ($province->name == $firebaseProvince['name']) {
                    array_push($firebaseProvince['cases'], [
                        'date'       => $lastUpdate,
                        'total_cases'     => $this->replaceComma($province->totalConfirmed),
                        'total_deaths'    => $this->replaceComma($province->totalDeaths),
                        'total_recovered' => $this->replaceComma($province->totalRecovered),
                    ]);
                    $this->dataBase->update([
                        self::PROVINCES_REFERENCE . '/' . $key . '/cases' => $sortByDate($firebaseProvince['cases'])
                    ]);
                    break;
                }
            }
        }
    }

    private function replaceComma(string $str): string
    {
        return str_replace (',','',$str);
    }

    private function isUptoDate($lastUpdated)
    {
        return date('d',strtotime($lastUpdated)) === date('d');
    }
}
