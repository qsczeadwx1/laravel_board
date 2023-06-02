@extends('layout.layout')

@section('title', 'Login')

@section('contents')
<h1>Login</h1>
@include('layout.errorsvalidate')

    <div>{!!session()->has('success') ? session('success') : ""!!}</div>
    
    <form action="{{route('users.login.post')}}" method="post">
        @csrf
        <label for="email">Email : </label>
        <input type="text" name="email" id="email">
        <br>
        <label for="password">password : </label>
        <input type="text" name="password" id="password">
        <br>
        <button type="submit">로그인</button>
        <button type="button" onclick="location.href = '{{route('users.registration')}}'">회원가입</button>
    </form>
    <?php
    function m_page_view(){
    return preg_match('/phone|samsung|lgtel|mobile|[^A]skt|nokia|blackberry|android|sony/i', $_SERVER['HTTP_USER_AGENT']);
    }
    echo m_page_view();
    var_dump($_SERVER['HTTP_USER_AGENT']);
    if(m_page_view()) {
        echo 'a';
    } else {
        echo 'b';
    }
    ?>
@endsection