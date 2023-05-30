@extends('layout.layout')

@section('title', 'Login')

@section('contents')
<h1>Login</h1>
@include('layout.errorsvalidate')

    {{-- <div>{{isset($success) ? $success : ""}}</div> --}}
    @isset($success)
        <div>asdfasdfsadfas</div>
    @endisset
    
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
@endsection