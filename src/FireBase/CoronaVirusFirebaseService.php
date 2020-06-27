<?php


namespace App\FireBase;


use App\Interfaces\DataBase;
use Kreait\Firebase\Database as FirebaseDB;
use Kreait\Firebase\Exception\DatabaseException;

class CoronaVirusFirebaseService implements DataBase
{
    protected $database;
    protected $reference;

    public function __construct(FirebaseDB $database, string $reference)
    {
        $this->database = $database;
        $this->reference = $reference;
    }

    public function push(string $path): string
    {
        return $this->database->getReference($path)->push()->getKey();
    }

    public function update(array $data): array
    {
        return $this->updateByRef($this->reference, $data);
    }

    public function updateByRef(string $path, array $data): array
    {
        try {

            $this->database->getReference($path)
                ->update($data);

        } catch (DatabaseException $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }

        return ['updated' => true];
    }

    public function gets(): array
    {
        return $this->database
            ->getReference($this->reference)
            ->getSnapshot()
            ->getValue();
    }
}
