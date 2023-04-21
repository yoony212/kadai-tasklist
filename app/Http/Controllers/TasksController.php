<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\task;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TasksController;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // タスク一覧を取得
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(5);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        // タスク一覧ビューでそれを表示
        return view('dashboard', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new task;

        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
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
            'status' => 'required',
            'content' => 'required|max:255',
        ]);
        // タスクを作成
        $task = new task;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->user_id = \Auth::id();
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
            // タスク詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
        } else {
            return redirect('/dashboard');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
            // タスク編集ビューでそれを表示
            return view('tasks.edit', [
                'task' => $task,
            ]);
        } else {
            return redirect('/dashboard');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'content' => 'required|max:255',
        ]);
        // idの値でタスクを検索して取得
        $task = task::findOrFail($id);
        // メッセージを更新
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
            // タスクを削除
            $task->delete();
    
            // タスク編集ビューでそれを表示
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
        return redirect('/dashboard');
    }
}
