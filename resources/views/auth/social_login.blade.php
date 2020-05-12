@extends('lap::layouts.guest')

@section('title', 'Login')
@section('child-content')
<a href="{{ route('social.oauth', 'google') }}" class="btn btn-danger btn-block">
Login with <i class="fab fa-google ml-1"></i>
</a>
<a href="{{ route('social.oauth', 'facebook') }}" class="btn btn-primary btn-block">
Login with <i class="fab fa-facebook ml-1"></i>
</a>
<a href="{{ route('social.oauth', 'twitter') }}" class="btn btn-info btn-block">
Login with <i class="fab fa-twitter ml-1"></i>
</a>
<a href="{{ route('social.oauth', 'linkedin') }}" class="btn btn-default btn-block">
Login with <i class="fab fa-linkedin ml-1"></i>
</a> 
@endsection