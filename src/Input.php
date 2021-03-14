<?php

namespace App;

class Input {

    public function __construct(protected array $gets) {}

    public static function create($gets) : self
    {
        return new static($gets);
    }

    public function get($val) {
        if (isset($this->gets[$val])) {
            return $this->gets[$val];
        }
    
        return null;
    }
}