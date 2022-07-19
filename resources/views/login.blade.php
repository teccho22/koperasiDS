@extends('layouts.master')
 
@section('title', 'LOGIN')
 
@section('sidebar')
@stop
 
@section('content')
    <br />
    <div class="container box">
        <h2 align="center" style="color: #0877DE;"><img src="{{ URL::asset('assets/images/Logo.png') }}" alt="KDS Logo" style="margin-right: 20px;"> Koperasi Damai Sejahtera</h2><br />
        <h3 align="center" style="color: #0877DE;">User Login</h3><br />
        
        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
    
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
    
        <form method="post" action="{{ url('/login/checklogin') }}" style="font-family: Roboto; color:black">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="E-mail/ Username"/>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password"/>
            </div>
            <div class="form-group">
                <input type="submit" name="login" class="btn btn-primary" value="Login" />
            </div>
        </form>
    </div>
@stop

<style type="text/css">
    .box{
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        width: 650px !important;
    }
</style>