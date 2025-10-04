@extends('frontend.layout.app')
@section('content')

 
  @include('frontend.layout.header')
  
  <div class="root_modal"></div>

  @yield('frontend_content')
  
  @include('frontend.layout.footer')

@endsection