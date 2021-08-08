<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTask extends Model
{
    use HasFactory;

    public const TASK_COMPLETED = 1;
    public const TASK_NOT_COMPLETED = 0;

    protected $table = 'user_task';
}
