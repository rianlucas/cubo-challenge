<?php

use App\enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{deleteJson, getJson, postJson, putJson};

uses(RefreshDatabase::class);

describe("Find by id", function () {
    it('should fetch a task by id', function () {
        $task = Task::factory()->create();

        getJson("api/tasks/{$task->id}")
            ->assertStatus(200)
            ->assertJson([
                "success" => true,
                "data" => [
                    "id" => $task->id,
                    "name" => $task->name,
                    "description" => $task->description,
                    "status" => $task->status,
                ]
            ]);
    });

    it("should return 404 if task not found", function () {
        getJson("api/tasks/1")
            ->assertStatus(404)
            ->assertJson([
                "success" => false,
                "error" => "Not Found",
                "message" => "Resource not found. Please check the id and try again."
            ]);
    });

    it("should return 500 if an error occurs", function () {
        getJson("api/tasks/invalid-id")
            ->assertStatus(500)
            ->assertJsonStructure([
               "success",
                "error",
                "message"
            ]);
    });
});

describe("List tasks", function () {
    it('should list tasks', function () {
        Task::factory()->count(3)->create();

        getJson('api/tasks')
            ->assertStatus(200)
            ->assertJsonCount(3, "data")
            ->assertJsonStructure([
                "data" => [
                    "*" => ["id", "name", "description", "status"]
                ]
            ]);
    });

    it("should list tasks filtered by status", function () {
       Task::factory()->count(3)->create([
           "status" => TaskStatusEnum::PENDING->value
       ]);
       Task::factory()->count(2)->create([
           "status" => TaskStatusEnum::COMPLETED->value
       ]);

       getJson("api/tasks?status=" . TaskStatusEnum::PENDING->value)
           ->assertJsonCount(3, "data")
           ->assertJsonStructure([
               "data" => [
                   "*" => ["id", "name", "description", "status"]
               ]
           ]);
    });

    it("should list tasks filtered by created_at", function () {
        Task::factory()->count(3)->create([
            "created_at" => now()->subDays(2)
        ]);
        Task::factory()->count(2)->create([
            "created_at" => now()->subDays(1)
        ]);

        getJson("api/tasks?created_at=" . now()->subDays(2)->toDateString())
            ->assertJsonCount(3, "data")
            ->assertJsonStructure([
                "data" => [
                    "*" => ["id", "name", "description", "status"]
                ]
            ]);
    });

    it("should list tasks paginated", function () {
        Task::factory()->count(10)->create();

        getJson("api/tasks?per_page=5")
            ->assertJsonCount(5, "data")
            ->assertJsonStructure([
                "data" => [
                    "*" => ["id", "name", "description", "status"]
                ]
            ]);
    });

    it("should be capable of choose page of listed tasks", function () {
        Task::factory()->count(10)->create();

        getJson("api/tasks?per_page=5&page=2")
            ->assertJsonCount(5, "data")
            ->assertJsonStructure([
                "data" => [
                    "*" => ["id", "name", "description", "status"]
                ]
            ]);

        getJson("api/tasks?per_page=5&page=3")
            ->assertJsonCount(0, "data");
    });

    it("should not list tasks when filter is incorrect", function () {
        getJson("api/tasks?per_page=invalid&created_at=Y-m-d&status=invalid_status")
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "per_page",
                    "created_at",
                    "status"
                ]
            ]);

    });
});

describe("Create task", function () {

    it('should create a task', function () {
        $taskData = [
            "name" => "New Task",
            "description" => "Task description",
            "status" => TaskStatusEnum::PENDING->value,
        ];

        postJson('api/tasks', $taskData)
            ->assertStatus(201)
            ->assertJson([
                "succes" => true,
                "data" => [
                    "name" => $taskData["name"],
                    "description" => $taskData["description"],
                    "status" => $taskData["status"],
                ]
            ]);

        $this->assertDatabaseHas('tasks', $taskData);
    });

    it("should not create a task with invalid data", function () {
        $taskData = [
            "name" => "",
            "description" => "",
            "status" => "invalid_status",
        ];

        postJson('api/tasks', $taskData)
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "name",
                    "description",
                    "status"
                ]
            ]);

    $this->assertDatabaseMissing('tasks', $taskData);
    });
});

describe("Update task", function () {
    it('should update a task', function () {
        $task = Task::factory()->create();
        $updatedData = [
            "id" => $task->id,
            "name" => "Updated Task",
            "description" => "Updated description",
            "status" => TaskStatusEnum::COMPLETED->value,
        ];

        putJson("api/tasks/{$task->id}", $updatedData)
            ->assertStatus(200)
            ->assertJson([
                "success" => true,
                "message" => "Task updated successfully",
            ]);

        $this->assertDatabaseHas('tasks', $updatedData);
    });

    it("should not update a task with invalid data", function () {
        $task = Task::factory()->create();
        $updatedData = [
            "id" => $task->id,
            "name" => 4,
            "description" => "",
            "status" => "invalid_status",
        ];

        putJson("api/tasks/{$task->id}", $updatedData)
            ->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors" => [
                    "name",
                    "description",
                    "status"
                ]
            ]);

        $this->assertDatabaseMissing('tasks', $updatedData);
    });

    it("should return 404 if task not found", function () {
        $updatedData = [
            "id" => 1,
            "name" => "Updated Task",
            "description" => "Updated description",
            "status" => TaskStatusEnum::COMPLETED->value,
        ];

        putJson("api/tasks/1", $updatedData)
            ->assertStatus(404)
            ->assertJson([
                "success" => false,
                "error" => "Not Found",
                "message" => "Resource not found. Please check the id and try again."
            ]);
    });
});

describe("Delete task", function () {
    it('should delete a task', function () {
        $task = Task::factory()->create();

        deleteJson("/api/tasks/{$task->id}")
            ->assertStatus(200)
            ->assertJson([
                "sucess" => true,
                "message" => "Task deleted successfully",
            ]);

        $this->assertDatabaseMissing('tasks', ["id" => $task->id]);
    });

    it("should return 404 if task is not found", function () {
        deleteJson("/api/tasks/1")
            ->assertStatus(404)
            ->assertJson([
                "success" => false,
                "error" => "Not Found",
                "message" => "Resource not found. Please check the id and try again."
            ]);
    });
});
