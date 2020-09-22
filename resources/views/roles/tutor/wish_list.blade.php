@extends('roles.tutor.layout')

@section('content-tutor-index')
   
	@include('shopping/pending_shopping_cart')
   
@endsection
@section('js')
    <script src="{{asset('/../angular/controller/tutorProductsCtrl.js')}}"></script>
@endsection