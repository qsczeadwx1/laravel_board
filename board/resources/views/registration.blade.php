@extends('layout.layout')

@section('title', 'Registration')

@section('contents')
<h1>Registration</h1>
@include('layout.errorsvalidate')
    <form action="{{route('users.registration.post')}}" method="post">
        @csrf
        <label for="name">name : </label>
        <input type="text" name="name" id="name">
        <br>
        <label for="email">Email : </label>
        <input type="text" name="email" id="email">
        <br>
        <label for="password">password : </label>
        <input type="text" name="password" id="password">
        <br>
        <label for="passwordchk">password 확인 : </label>
        <input type="text" name="passwordchk" id="passwordchk">
        <br>
        <button type="submit">회원가입</button>
        <button type="button" onclick="location.href = '{{route('users.login')}}'">취소</button>
    </form>
@endsection