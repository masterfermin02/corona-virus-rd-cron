<?php


namespace App\Console;


class Model
{
    protected $props;

    public function __construct($props)
    {
        $this->props = $props;
    }

    public function get(string $prop): string
    {
        return $this->props[$prop];
    }
}
