@extends('layouts.app')
@section('title','تسجيل الدخول')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
@endsection
@section('content')
<div class="row justify-content-center title">
    <h1 class="mt-2 text-white"> <b>  كراج القليلي </b> </h1>
</div>
<div class="container">
    <div class="row justify-content-center login-form">
        <div class="col-md-8 mt-5">
            <div class="card">
                <div class="card-header text-center"> <h3> تسجيل الدخول <i class="fas fa-sign-in-alt"></i> </h3> </div>

                <div class="card-body" dir="rtl">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right"> <i class="far fa-id-card"></i> اسم المستخدم </label>

                            <div class="col-md-6">
                                @php
                                    $users = \App\User::all();   
                                @endphp
                                {{-- <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" required name="email" value="{{ old('email') }}"  autofocus> --}}
                                <select id="email" type="text" class="form-control @error('email') is-invalid @enderror" required name="email" value="{{ old('email') }}"  autofocus>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->email }}">{{ $user->email }}</option> 
                                    @endforeach
                                </select>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right"> <i class="fas fa-lock"></i> كلمة المرور </label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

            
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt"></i>
                                    دخول 
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
