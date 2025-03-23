<?php

namespace App\dtos;

use App\enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateTaskDTO
{
    public function __construct(
        public string $name,
        public string $description,
        public TaskStatusEnum $status,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ];
    }

    public static function fromRequest(Request|FormRequest $request): self
    {
        return new self(
            $request->get('name'),
            $request->get('description'),
            TaskStatusEnum::from($request->get('status')),
        );
    }
}
