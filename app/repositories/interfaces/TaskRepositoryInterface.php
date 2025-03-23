<?php

namespace App\repositories\interfaces;

use App\dtos\CreateTaskDTO;
use App\dtos\UpdateTaskDTO;
use App\enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function findById(int $id): Task;
    public function list(?TaskStatusEnum $status, ?string $createdAt, int $perPage): LengthAwarePaginator;
    public function create(CreateTaskDTO $taskDTO): Task;
    public function update(UpdateTaskDTO $taskDTO): bool;
    public function delete(int $id): bool;
}
