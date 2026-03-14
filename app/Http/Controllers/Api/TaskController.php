<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()
            ->select('id', 'title', 'is_completed')
            ->latest()
            ->get();

        return $this->success($tasks, 'Tasks fetched successfully');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
        ]);

        if ($validated->fails()) {
            return $this->error(null, $validated->errors()->first(), 422);
        }

        $task = $request->user()->tasks()->create([
            'title' => $request->title,
        ]);

        return $this->success($task, 'Task created successfully', 201);
    }

    public function show(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return $this->error(null, 'Unauthorized', 403);
        }

        return $this->success($task, 'Task fetched successfully');
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return $this->error(null, 'Unauthorized', 403);
        }

        $validated = Validator::make($request->all(), [
            'title'        => 'sometimes|string',
            'is_completed' => 'sometimes|boolean',
        ]);

        if ($validated->fails()) {
            return $this->error(null, $validated->errors()->first(), 422);
        }

        $task->update($validated->validated());

        return $this->success($task, 'Task updated successfully');
    }

    public function destroy(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return $this->error(null, 'Unauthorized', 403);
        }

        $task->delete();

        return $this->success(null, 'Task deleted successfully');
    }
}