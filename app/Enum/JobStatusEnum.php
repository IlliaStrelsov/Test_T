<?php

declare(strict_types=1);

namespace App\Enum;

enum JobStatusEnum: int
{
    case JOB_STATUS_NOT_COMPLETED = 0;

    case JOB_STATUS_COMPLETED = 2;
}
