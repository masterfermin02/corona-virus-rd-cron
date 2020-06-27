<?php


namespace App\Interfaces;


interface DataBase
{
    public function push(string $path): string;

    public function update(array $data): array;

    public function updateByRef(string $path, array $data): array;

    public function gets(): array;
}
