@extends('layouts.frontend')
@section('content')
    <div class="container mt-5" style="margin-bottom:100px">
        <div class="row">
            @livewire('buynow', ['product' => $product])
        </div>
    </div>
@endsection
@section('javascript')
@endsection
