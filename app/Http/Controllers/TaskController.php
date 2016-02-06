<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tasks = Task::orderBy('created_at', 'asc')->get();
        return view('task', [
            'tasks' => $tasks
        ]);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255'
        ]);

        $request->user()->tasks()->create([
            'name' => $request->name
        ]);

        return redirect('tasks');
    }

    public function destroy(Task $task)
    {
        $this->authorize('destroy', $task);
        $task->delete();
        return redirect('tasks');
    }
}
