<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;

class AdminMessageController extends Controller
{
    public function index()
    {
        return view('admin.messages.index', ['messages' => Message::orderBy('created_at', 'desc')->get()]);
    }
}
