<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Boards;


class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Boards::select(['id', 'title', 'hits', 'created_at', 'updated_at'])->orderBy('hits', 'desc')->get();
        return view('list')->with('data', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $boards = new Boards([
            'title' => $req->input('title')
            ,'content' => $req->input('content')
        ]);
        $boards->save();
        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $boards = Boards::find($id);
        $boards->hits++;
        $boards->save();

        return view('detail')->with('data', Boards::findOrFail($id));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $boards = Boards::findOrFail($id);
        return view('edit')->with('data', $boards);
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
        Boards::where('id', $id)
        ->update(['title' => $request->input('title')
                ,'content'=>$request->input('content')
                ]);
        return redirect('/boards/'.$id);
        // return view('detail')->with('data', Boards::findOrFail($id));
        // return redirect()->route('boards.show', ['board' => $id]);

        // $result = Boards::find($id);
        // $result->title = $request->title;
        // $result->content = $request->content;
        // $result->save();
        // return redirect('/boards/'.$id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 이거는 무조건 destroy안에 pk를 넣어야함
        // Boards::destroy($id);
        
        Boards::where('id', $id)->firstOrFail()->delete();
        return redirect('/boards');
    }
}
