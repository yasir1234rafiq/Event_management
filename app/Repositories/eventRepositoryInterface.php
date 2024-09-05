<?php

namespace App\Repositories;

use App\Models\Event;

interface EventRepositoryInterface
{
    public function create(array $data): Event;
    public function update(int $id, array $data): bool;
    public function find(int $id): ?Event;
    public function delete(int $id): bool;
    public function all(array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}
