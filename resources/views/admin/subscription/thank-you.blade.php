@extends('layouts.master')

@section('title', 'Thank You')

@section('main-content')
<section class="content">
    <div class="container">
        <div class="card text-center p-5">
            <h2>Thank you for your subscription 🎉</h2>
            <p>Your payment was successful.</p>

            <a href="{{ route('admin.dashboard') }}"
               class="btn btn-primary mt-3">
                Go to Dashboard
            </a>
        </div>
    </div>
</section>
@endsection
