@extends('layout.layout')

@section('title', 'UserUpdate')

@section('contents')
<h1>UserUpdate</h1>
@include('layout.errorsvalidate')

    <div>
        이름 : {{$data->name}}
        <br>
        이메일 : {{$data->email}}
    </div>
    <form action="{{route('users.useredit.post')}}" method="post">
        @csrf
        {{-- <label for="name">name : </label>
        <input type="text" name="name" id="name" value="{{$data->name}}">
        <br>
        <label for="email">Email : </label>
        <input type="text" name="email" id="email" value="{{$data->email}}">
        <br> --}}
        <label for="password">바꿀 password : </label>
        <input type="text" name="password" id="password">
        <span>{{Session::get('message')}}</span>
        <br>
        <label for="passwordchk">password 확인 : </label>
        <input type="text" name="passwordchk" id="passwordchk">
        <br>

        <button type="submit">정보 수정</button>
        <button type="button" onclick="location.href = '{{route('boards.index')}}'">취소</button>
    </form>
@endsection