<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\UserTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function get() : JsonResponse
    {
        return response()->json(['success' => (new Task())->getUniq(Auth::user())], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function setStatus(Request $request) : JsonResponse
    {
        $taskData = json_decode($request->getContent(), true);

        if ($taskData !== null) {
            $validator = Validator::make($taskData, [
                'task_id' => 'required|int',
                'status' => 'required|int'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            $userTask = new UserTask();
            $userTask->setAttribute('user_id', Auth::user()->id);
            $userTask->setAttribute('task_id', $taskData['task_id']);
            $userTask->setAttribute('status', $taskData['status']);

            $isExist = UserTask::query()->where(['task_id' => $userTask->task_id, 'user_id' => $userTask->user_id])->exists();
            if ($isExist) {
                UserTask::query()->where([
                    'task_id' => $userTask->task_id,
                    'user_id' => $userTask->user_id
                ])->update($taskData);
            } else {
                $userTask->save();
            }

            return response()->json(['success' => $userTask], 200);
        }

        return response()->json(['error' => json_last_error_msg()], 400);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function change(int $id) : JsonResponse
    {
        return response()->json(['success' => (new Task())->change($id)], 200);
    }
}
