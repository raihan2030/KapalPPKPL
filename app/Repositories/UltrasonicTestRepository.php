<?php

namespace App\Repositories;

use App\Models\UltrasonicTest;

class UltrasonicTestRepository implements UltrasonicTestRepositoryInterface
{
    public function create(int $idInspeksi, array $data): UltrasonicTest
    {
        $payload = array_merge($data, ['id_inspeksi' => $idInspeksi]);

        return UltrasonicTest::query()->create($payload);
    }
}
