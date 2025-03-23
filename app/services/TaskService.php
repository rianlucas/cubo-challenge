<?php

namespace App\services;

use App\dtos\CreateTaskDTO;
use App\dtos\UpdateTaskDTO;
use App\enums\TaskStatusEnum;
use App\Models\Task;
use App\repositories\interfaces\TaskRepositoryInterface;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {}

    public function findById(int $id): Task
    {
        return $this->taskRepository->findById($id);
    }

    public function list(?TaskStatusEnum $status, ?string $createdAt, ?int $perPage): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->taskRepository->list($status, $createdAt, $perPage);
    }

    public function create(CreateTaskDTO $taskDTO): Task
    {
        return $this->taskRepository->create($taskDTO);
    }

    public function update(UpdateTaskDTO $taskDTO): void
    {
        $this->taskRepository->findById($taskDTO->id);
        $this->taskRepository->update($taskDTO);
    }

    public function delete(int $id): void
    {
        $this->taskRepository->findById($id);
        $this->taskRepository->delete($id);
    }
}
