<?php

namespace App\dtos;

use App\enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateTaskDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public TaskStatusEnum $status,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ];
    }

    public static function fromRequest(Request|FormRequest $request): self
    {
        return new self(
            $request->route('id'),
            $request->get('name'),
            $request->get('description'),
            TaskStatusEnum::from($request->get('status')),
        );
    }
}
