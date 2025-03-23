<?php

namespace App\Http\Controllers;

use App\dtos\CreateTaskDTO;
use App\dtos\UpdateTaskDTO;
use App\enums\TaskStatusEnum;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\ListTasksRequest;
use App\services\TaskService;
use DB;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    public function findById(int $id)
    {
        $task = $this->taskService->findById($id);

        return response()->json([
            'success' => true,
            'data' => $task,
        ]);
    }

    public function list(ListTasksRequest $request)
    {
        $status = $request->get('status');
        $createdAt = $request->get('created_at');
        $perPage = $request->get('per_page');
        if ($status) {
            $status = TaskStatusEnum::from($status);
        }

        $tasks = $this->taskService->list($status, $createdAt, $perPage);

        return response()->json($tasks);
    }

    public function create(CreateTaskRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $taskCreateDTO = CreateTaskDTO::fromRequest($request);
            $taskCreated = $this->taskService->create($taskCreateDTO);
            DB::commit();

            return response()->json([
                'succes' => true,
                'data' => $taskCreated,
            ], 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(CreateTaskRequest $request)
    {
        try {
            DB::beginTransaction();
            $taskUpdateDTO = UpdateTaskDTO::fromRequest($request);
            $this->taskService->update($taskUpdateDTO);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function delete(int $id)
    {
        $this->taskService->delete($id);

        return response()->json([
            'sucess' => true,
            'message' => 'Task deleted successfully',
        ]);
    }
}
