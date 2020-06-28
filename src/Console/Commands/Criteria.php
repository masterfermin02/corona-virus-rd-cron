<?php


namespace App\Console\Commands;


interface Criteria
{
    public function test(string $param): bool;
}
