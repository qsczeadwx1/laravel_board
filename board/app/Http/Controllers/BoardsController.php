<?php
/***********************************
 * 프로젝트 명  : laravel_board
 * 디렉토리     : Controllers
 * 파일 명      : BoardController.php
 * 이력         : v001 0526 MG.Kim new
 *                v002 0530 MG.Kim 유효성 체크 추가
 * ********************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\models\Boards;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

        // v002 add start
        $req->validate([
            'title' => 'required|between:3,30'
            ,'content' => 'required|max:2000'
        ]);
        // v002 add end

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
        // $id->validate([
            
        // ]);
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
        // v002 add start
        // ID를 리퀘스트 객체에 병합
        $arr = ['id' => $id];
        // $request->merge($arr);
        $request->request->add($arr); // merge와 같은 작동
        
        $request->validate([
            'id' => 'required|integer'
            ,'title' => 'required|between:3,30'
            ,'content' => 'required|max:2000'
        ]);
        // v002 add end

        // 유효성 검사 방법2
        // $validator = Validator::make(
        //     $request->only('id', 'title', 'content')
        //     ,[
        //         'id' => 'required|integer'
        //         ,'title' => 'required|between:3,30'
        //         ,'content' => 'required|max:2000'
        //     ]
        // );

        // if($validator->fails()) {
        //     return redirect()
        //         ->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }


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
