<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\Validator;

class ApiListController extends Controller
{
    function getlist($id) {
        $user = Boards::find($id);
        return response()->json($user, 200);
    }

    function postlist(Request $req) {
        // 유효성 체크 필요

        $boards = new Boards([
            'title' => $req->title
            ,'content' => $req->content
        ]);
        $boards->save();

        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        $arr['data'] = $boards->only('id','title');

        return $arr;
    }

    function putlist(Request $req, $id) {
        $arrData = [
            'code' => '0'
            ,'msg' => ''
        ];

        $data = $req->only('title', 'content');
        $data['id'] = $id;

        // 유효성체크
        $validator = Validator::make($data, [
            'id' => 'required|integer|exists:Boards'
            ,'title' => 'required|between:3,30'
            ,'content' => 'required|max:2000'
        ]);

        if($validator->fails()) {
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validate Error';
            $arrData['errmsg'] = $validator->errors()->all();
            return $arrData;
        } else {
            $board = Boards::find($id);
            $board->title = $req->title;
            $board->content = $req->content;
            $board->save();
            $arrData['code'] = '0';
            $arrData['msg'] = 'Success';
        }
            return $arrData;
    }

    function deletelist($id) {
        $arrData = [
            'code' => '0'
            ,'msg' => ''
        ];
        $data['id'] = $id;
        // 유효성체크
        $validator = Validator::make($data, [
            'id' => 'required|integer|exists:Boards'
        ]);

        if($validator->fails()) {
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validate Error';
            $arrData['errmsg'] = 'id not found';
        } else {
            $board = Boards::find($id);
            if($board) {
                $board->delete();
                $arrData['code'] = '0';
                $arrData['msg'] = 'Success';
            } else {
                $arrData['code'] = 'E02';
                $arrData['msg'] = 'Already Deleted';
            }
        }
            return $arrData;
    }

}
