@extends('layouts.app')

@section('content')
<div class="dg-candidate-auth">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-sm-1"></div>
            <div class="col-lg-6 col-sm-10 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 col-sm-12 control-label">E-Mail Address</label>
                                <div class="col-md-6 col-sm-12">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 col-sm-12 control-label">Password</label>
                                <div class="col-md-6 col-sm-12">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-4 col-sm-4"></div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="checkbox remember pull-left">
                                        <input type="checkbox" id="myCheckbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="myCheckbox">Remember Me</label>
                                        <span></span>
                                    </div>
                                    <a class="btn btn-link pull-right" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4 col-sm-6 col-sm-offset-4">
                                    <button type="submit" class="btn btn-dg w-100">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-1"></div>
        </div>
    </div>
</div>
@endsection
