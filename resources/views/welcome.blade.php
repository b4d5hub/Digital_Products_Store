@extends('layouts.app')

{{-- @section('presentation')
@include('layouts.presentation')
@endsection --}}

{{-- @section('information')
@include('layouts.information')
@endsection --}}

@section('productsgrid')
<section id="products" class="explore-products">
    <div class="container">
        <div class="explore-products-content">
            @include('layouts.search-bar')
            @include('layouts.categories-bar')
            @include('layouts.productsgrid')
        </div>
    </div>
</section>
@endsection