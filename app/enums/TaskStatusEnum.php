<?php

namespace App\enums;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case IN_PROGRESS = 'in_progress';
}
