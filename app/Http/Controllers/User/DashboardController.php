<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Operasi\TodoListController;
use App\Models\Calendar;
use App\Models\TodoList;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todoListController = new TodoListController();
        $todoList = $todoListController->hitungDeadline(TodoList::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->offset(0)->limit(5)->get());
        $todoList = $todoListController->EditFormatDeadline($todoList);
        $nTodoList = count(TodoList::all());
        $jumlahHalaman = ceil($nTodoList / 5);
        
        $calendar = Calendar::where('user_id', auth()->user()->id)->get();
        return view('user/dashboard', compact('todoList', 'jumlahHalaman', 'calendar'));    
    }
}
