<?php

namespace App\Repositories;

use App\Models\UltrasonicTest;

interface UltrasonicTestRepositoryInterface
{
    public function create(int $idInspeksi, array $data): UltrasonicTest;
}
