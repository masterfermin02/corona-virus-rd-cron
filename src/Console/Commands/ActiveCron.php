<?php


namespace App\Console\Commands;


class ActiveCron implements Criteria, Executable
{
    protected const COMMAND = 'active';
    protected $cmd;

    public function __construct(string $cmd)
    {
        $this->cmd = $cmd;
    }

    public function test(string $param): bool
    {
        return self::COMMAND === $param;
    }

    public function execute()
    {
        shell_exec( $this->cmd );
    }
}
