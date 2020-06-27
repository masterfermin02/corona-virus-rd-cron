<?php


namespace App\FireBase;

use Kreait\Firebase\Database;

class Service
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

}
