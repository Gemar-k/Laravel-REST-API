<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\Task as TaskResource;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $tasks = $user->tasks()->orderBy('id', 'desc')->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:196',
            'description' => 'max:196',
            'color' => 'max:7'
        ]);

        $task = new Task();


        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->user_id = auth()->id();
        $task->color = $request->input('color');
        $task->bookmark = false;

        $task->save();


        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($task)
    {
        $task = Task::where('id', $task)->where('user_id', auth()->id())->get();

        return new TaskResource($task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $task)
    {
        $request->validate([
            'title' => 'required|max:196',
            'description' => 'max:196',
            'color' => 'max:7'
        ]);

        $task = Task::where('id', $task)->where('user_id', auth()->id())->first();

        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->color = $request->input('color');
        $task->save();

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($task)
    {
        $tasks = Task::findOrFail($task);

        if ($tasks->user_id == \auth()->id()){
            $tasks->delete();
            return response()->json([
                'status' => 'succes',
                'message' => 'Task deleted'
            ], 500);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete Task, please try again.'
            ], 500);
        }

    }
}
