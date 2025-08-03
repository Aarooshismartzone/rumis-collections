@extends('frontend.customer.layouts.customer-master')

@section('title')
    Profile
@endsection

@section('content')
    <div class="container">
        <p>Name: {{ $customer->fname." ".$customer->lname }}</p>
        <p>Email: {{ $customer->email }}</p>
        <p>Contact Number: {{ $customer->pnum }}</p>
    </div>
@endsection
