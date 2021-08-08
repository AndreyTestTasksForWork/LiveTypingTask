<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $table = 'task';

    public function change(int $id) : self
    {
        $user = Auth::user();
        $currentTasksIds = array_map(function ($task) {
            return $task->id;
        }, $user->getTodayTask());

        $newTask = Task::inRandomOrder()
            ->select(['id' => 'task.id', 'name', 'description', 'status', 'category_id'])
            ->leftJoin('user_task', 'task.id', '=', 'user_task.task_id')
            ->whereNotIn('task.id', $currentTasksIds)
            ->get()
            ->first();

        UserTask::query()->where([
            'task_id' => $id,
            'user_id' => Auth::user()->id
        ])->update(['task_id' => $newTask->id]);

        return $newTask;
    }

    public function getByCategoryId(int $categoryId, int $limit = null) : array
    {
        $tasks = Task::inRandomOrder()
            ->select(['id' => 'task.id', 'name', 'description', 'status', 'category_id'])
            ->leftJoin('user_task', 'task.id', '=', 'user_task.task_id')
            ->where('category_id', $categoryId);

        if ($limit !== null) {
            $tasks->limit($limit);
        }

        return  $tasks->get()->all();
    }
}
