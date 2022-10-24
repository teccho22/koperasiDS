@extends('layouts.master')
 
@section('title', 'KopDS')
 
@section('sidebar')
@stop
 
@section('content')
    <br />
        <div class="container box">
            {{-- <img src="{{ URL::asset('assets/images/Logo.png') }}" alt="KDS Logo" style="display: block; margin-left: auto; margin-right: auto;"> --}}
            <h2 align="center" style="color: #0877DE;font-family: 'Righteous', cursive !important;" class="card-title"><img src="{{ URL::asset('assets/images/Logo.png') }}" alt="KDS Logo" style=""> Koperasi Damai Sejahtera</h2><br />
            <div class="card p-3 mb-5 bg-white rounded" style="width: 40% !important; margin-left: auto;margin-right:auto; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important">
                <div class="card-body">
                    <h3 align="center" style="color: #0877DE;font-family: 'Righteous', cursive !important;">Login</h3><br />
                    
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
                
                    <form method="post" action="{{ url('/login/checklogin') }}" style="font-family: 'Roboto', cursive !important; color:black">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="text" name="username" class="form-control" placeholder="E-mail/ Username"/>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Password"/>
                        </div>
                        <div class="form-group" style="">
                            <input type="submit" name="login" class="btn btn-primary" value="Login" style="width: 100%" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
@stop

<style type="text/css">
    .box{
        position: absolute;
        top: 50%;
        left: 50%;
        right: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }
</style>