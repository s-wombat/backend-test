<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatuses;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::with('project', 'user')
            ->when($request->name, fn($query) => $query->where('name', 'like', '%' . $request->name . '%'))
            ->when($request->status, fn($query) => $query->where('status', $request->status))
            ->when($request->project_id, fn($query) => $query->where('project_id', $request->project_id))
            ->when($request->user_id, fn($query) => $query->where('user_id', $request->user_id))
            ->get();

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => ['required', new Enum(TaskStatuses::class)],
            'deadline' => 'nullable|date',
        ]);

        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load('project', 'user');
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'project_id' => 'sometimes|required|exists:projects,id',
            'user_id' => 'nullable|exists:users,id',
            'status' => ['sometimes', 'required', new Enum(TaskStatuses::class)],
            'deadline' => 'nullable|date',
        ]);

        $task->update($validated);
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully.']);
    }
}
