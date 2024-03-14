<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Filament::auth()->check()) {
            return redirect(Filament::getLoginUrl());
        }
        if (!request()->news_id || News::find(request()->news_id) === null) {
            abort(404, 'News not found');
        }
        $user = Filament::auth()->user();
        return view('comments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Filament::auth()->check()) {
            return redirect(Filament::getLoginUrl());
        }
        $request->validate([
            'news_id' => 'required|exists:news,id',
            'comment_body' => 'required|string',
        ]);

        $data = new Comment();
        $data->comment_body = $request->comment_body;
        $data->news_id = $request->news_id;
        $data->created_by = Filament::auth()->user()->id;
        $data->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
