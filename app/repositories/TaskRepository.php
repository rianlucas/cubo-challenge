<?php

namespace App\repositories;

use App\dtos\CreateTaskDTO;
use App\dtos\UpdateTaskDTO;
use App\enums\TaskStatusEnum;
use App\Models\Task;
use App\repositories\interfaces\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    public function findById(int $id): Task
    {
        return Task::where('id', $id)->firstOrFail();
    }

    public function list(?TaskStatusEnum $status, ?string $createdAt, ?int $perPage): LengthAwarePaginator
    {
        return Task::when($status, function ($query, $status) {
            return $query->where('status', $status);
        })->when($createdAt, function ($query, $createdAt) {
            return $query->whereDate('created_at', $createdAt);
        })->paginate($perPage ?? 10);
    }

    public function create(CreateTaskDTO $taskDTO): Task
    {
        return Task::create([
            'name' => $taskDTO->name,
            'description' => $taskDTO->description,
            'status' => $taskDTO->status,
        ]);
    }

    public function update(UpdateTaskDTO $taskDTO): bool
    {
        return Task::where('id', $taskDTO->id)->update([
            'name' => $taskDTO->name,
            'description' => $taskDTO->description,
            'status' => $taskDTO->status,
        ]);
    }

    public function delete(int $id): bool
    {
        return Task::where('id', $id)->delete();
    }
}
