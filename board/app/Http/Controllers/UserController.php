<?php
/***********************************
 * 프로젝트 명  : laravel_board
 * 디렉토리     : Controllers
 * 파일 명      : UserController.php
 * 이력         : v001 0530 MG.Kim new          
 * ********************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class UserController extends Controller
{
    function login() {
        return view('login');
    }

    function loginpost(Request $req) {
        // 유효성 체크
        $req->validate([
            'email' => 'required|email|max:100'
            ,'password' => 'required|regex:/^(?=.*[a-zA-z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        // 유저정보 습득
        $user = User::where('email', $req->email)->first();
        if(!$user || !(Hash::check($req->password, $user->password))) {
            $error = '아이디와 비밀번호를 확인해 주세요';
            return redirect()->back()->with('error', $error);
        }

        // 유저 인증 작업
        Auth::login($user);
        if(Auth::check()) {
            session($user->only('id')); // 세션에 인증된 회원 pk 등록
            return redirect()->intended(route('boards.index'));
        } else {
            $error = '인증작업 에러';
            return redirect()->back()->with('error', $error);
        }

    }

    function registration() {
        return view('registration');
    }

    function registrationpost(Request $req) {
        // 유효성 체크
        $req->validate([
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'
            ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password);

        $user = User::create($data); // insert
        if(!$user) {
            $error = '시스템 에러가 발생하여, 회원가입에 실패했습니다.<br>잠시 후에 다시 시도해 주십시오.';
            return redirect()
                ->route('users.registration')
                ->with('error', $error);
        }
            // 회원가입 완료 로그인 페이지로 이동
            return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료 했습니다.<br>가입하신 아이디와 비밀번호로 로그인해 주십시오.');
        
    }


    function useredit() {
        $id = session('id');
        return view('useredit')->with('data', User::findOrFail($id));
    }

    function usereditpost(Request $req) {
        // 유효성체크
        $validate = $req->validate([
            'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);
        $same = Hash::check($validate['password'], Auth::user()->password);
        if ($same) {
            return redirect() -> back() -> with('message', '이전 비밀번호는 사용할 수 없습니다.');
        }
        $user = User::find(Auth::user()-> id);
        $user->password = Hash::make($validate['password']);
        $user->save();

            // 회원정보 수정 완료 후 보드 리턴
            return redirect()
            ->route('boards.index');
    }



    function logout() {
        Session::flush();
        Auth::logout();
        return redirect()->route('users.login');
    }

    function withdraw() {
        $id = session('id');
        $result = User::destroy($id); // 탈퇴 시키면서 에러가 나거나 하는 처리 해야됨 원래는
        Session::flush();
        Auth::logout();
        return redirect()->route('users.login');
        
    }

}
