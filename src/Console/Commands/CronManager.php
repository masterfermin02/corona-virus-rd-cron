<?php


namespace App\Console\Commands;


use function Slash\filter;
use function Slash\walk;

class CronManager
{
    public static function run($param, $commands)
    {
        self::execute(self::filterByType($param, $commands));
    }

    private static function filterByType($param, $commands): array
    {
        return filter($commands, function (Criteria $command) use ($param) {
            return $command->test($param);
        });
    }

    private static function execute(array $commands)
    {
        walk($commands, function(Executable $command) {
            $command->execute();
        });
    }

}
