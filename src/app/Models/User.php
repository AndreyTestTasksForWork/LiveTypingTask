<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getTodayTasksByCategory(int $categoryId, int $limit = null) : array
    {
        $todayTasks = $this->getTodayTask();

        if (empty($todayTasks)) {
            $todayTasks = (new Task())->getByCategoryId($categoryId, $limit);
            foreach ($todayTasks as $task) {
                $userTask = new UserTask();
                $userTask->setAttribute('user_id', $this->id);
                $userTask->setAttribute('task_id', $task->id);
                $userTask->setAttribute('status', UserTask::TASK_NOT_COMPLETED);
                $userTask->save();
            }
        }

       return $todayTasks;
    }

    public function getTodayTask() : array
    {
        return UserTask::query()
            ->select(['id' => 'task.id', 'name', 'description', 'status', 'category_id', 'user_task.created_at'])
            ->leftJoin('task', 'task.id', '=', 'user_task.task_id')
            ->where([
                'user_id' => $this->id,
                'user_task.created_at' => (new \DateTime())->format('Y-m-d')
            ])->get()->all();
    }
}
