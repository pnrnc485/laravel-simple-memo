<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ここでメモを取得
        $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')// ASC=小さい順
            ->get();
        return view('create', compact('memos'));
    }

    public function store(Request $request)
    {
        $posts = $request->all();
        // dump dieの略 - メソッドの引数を撮った値を展開して止める

        Memo::insert(['content' => $posts['content'], 'user_id' => \Auth::id()]);
        return redirect( route('home') );
    }

    public function edit($id)
    {
        // ここでメモを取得
        $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')// ASC=小さい順
            ->get();

        $edit_memo = Memo::find($id);
        return view('edit', compact('memos', 'edit_memo'));
    }

    public function update(Request $request)
    {
        $posts = $request->all();
        // dump dieの略 - メソッドの引数を撮った値を展開して止める

        Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content']]);
        return redirect( route('home') );
    }
}
