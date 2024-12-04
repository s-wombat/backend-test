<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Enums\ProjectStatuses;
use Illuminate\Validation\Rules\Enum;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::query()
            ->with('manager')
            ->when($request->has('name'), fn($q) => $q->where('name', 'like', '%' . $request->name . '%'))
            ->when($request->has('description'), fn($q) => $q->where('description', 'like', '%' . $request->description . '%'))
            ->when($request->has('status'), fn($q) => $q->where('status', $request->status))
            ->get();

        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => ['required', new Enum(ProjectStatuses::class)],
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::create($validated);

        return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load('manager', 'tasks');
        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => ['sometimes', new Enum(ProjectStatuses::class)],
            'users_id' => 'sometimes|exists:users,id',
        ]);

        $project->update($validated);
        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Project deleted successfully.']);
    }
}
