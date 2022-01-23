
@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Lupa password Anda?</h1>
                                    <p class="mb-4">Kami mengerti, hal-hal terjadi. Cukup masukkan alamat email Anda di bawah ini dan kami 
                                        akan mengirimkan tautan untuk mengatur ulang kata sandi Anda!</p>
                                </div>
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if (Session::has('status'))
                                    <div class="alert alert-warning" role="alert">
                                        {{ Session::get('status') }}
                                    </div>
                                @endif
                                <form class="user" action="{{ route('user.reset-password') }}" method="Post">
                                    @csrf
                                    <input type="text" name="user_id" value="{{ $user['id'] }}" hidden>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                        id="exampleInputPassword" placeholder="Password" name="password" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                        id="exampleRepeatPassword" placeholder="Repeat Password" name="password_confirmation" required>
                                    </div>
                                    <input type="submit" class="btn btn-primary btn-user btn-block">
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ url('register') }}">Create an Account!</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="{{ url('login') }}">Already have an account? Login!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

