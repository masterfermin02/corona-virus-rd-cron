<?php


namespace App\Console\Commands;


class InActiveCron implements Criteria, Executable
{
    protected const COMMAND = 'inactive';
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
