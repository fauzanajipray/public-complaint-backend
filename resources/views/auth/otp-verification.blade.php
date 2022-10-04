@extends('layouts.app')

@section('content')
<div class="container">
  <div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
      <!-- Nested Row within Card Body -->
      <div class="row">
        <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
        <div class="col-lg-7">
          <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Verification OTP</h1>
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
            <form class="user" action="{{ route('register.otp-verification') }}" method="POST">
              @csrf
              <div class="form-group" hidden>
                <input type="text" class="form-control form-control-user"
                    placeholder="Email Address" name="email" value="{{ $user->email }}" required>
              </div>
              <div class="form-group">
                <input type="number" class="form-control form-control-user"
                    placeholder="OTP" name="otp" required>
              </div>
              <input type="submit" class="btn btn-primary btn-user btn-block" value="Verify OTP">
              <a href="{{ route('register.resend-otp', ['email' => $user->email] ) }}" class="btn btn-google btn-user btn-block">
                Resend OTP </a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
