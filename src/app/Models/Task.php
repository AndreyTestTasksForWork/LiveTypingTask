<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Task extends Model
{
    use HasFactory;

    public const TASK_COMPLETED = 1;
    public const CACHE_LIFE_TIME_IN_MINUTES = '1440';
    public const CACHE_KEY = 'tasks_for_user';

    protected $table = 'task';

    public function getUniq(User $user) : array
    {
        $existTask = $user->getTasks()->select('id')->where('status', '=', self::TASK_COMPLETED)->get();
        $existTaskIds = [];
        foreach ($existTask as $task) {
            $existTaskIds[] = $task->id;
        }

        $cacheKey = sprintf('%s_%s', self::CACHE_KEY, $user->id);
        if (!Cache::has($cacheKey)) {
            $newTasksIds = Task::inRandomOrder()->select('id')->whereNotIn('id', $existTaskIds)->limit(5)->get()->toArray();
            $newTasksIds = array_map(function ($task) {
                return $task['id'] ?? '';
            }, $newTasksIds);

            Cache::add($cacheKey, json_encode($newTasksIds), Carbon::now()->addMinutes(self::CACHE_LIFE_TIME_IN_MINUTES));
        } else {
            $newTasksIds = json_decode(Cache::get($cacheKey));
        }

        return Task::query()
            ->select(['id' => 'task.id', 'name', 'description', 'status', 'category_id'])
            ->leftJoin('user_task', 'task.id', '=', 'user_task.task_id')
            ->whereIn('task.id', $newTasksIds)
            ->get()
            ->all();
    }

    public function change(int $id) : self
    {
        return Task::inRandomOrder()
            ->select(['id' => 'task.id', 'name', 'description', 'status', 'category_id'])
            ->leftJoin('user_task', 'task.id', '=', 'user_task.task_id')
            ->whereNotIn('task.id', [$id])
            ->get()
            ->first();
    }
}
