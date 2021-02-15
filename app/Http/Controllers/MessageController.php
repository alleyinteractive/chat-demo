<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Chat', [
            'messages' => Message::all(),
            'user' => auth()->user(),
            'users' => User::all()->map->minimalInfo(),
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
        $message = auth()->user()->messages()->create($request->validate([
            'body' => 'required',
        ]));
        broadcast(new NewMessage($message))->toOthers();

        return $message;
    }
}
