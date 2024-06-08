@extends('layouts.app')



@section('single-product-view')
<section class="explore-products">
    <div class="container">
      <div class="explore-products-content">
        <h1 class="explore-products-title" style="text-align: center;">
          Checkout
        </h1>
        @if (session('message'))
        <div class="alert alert-warning">
            {{ session('message') }}
        </div>
        @endif
        @if (session('successmessage'))
        <div class="alert alert-success">
            {{ session('successmessage') }}
        </div>
        @endif
      <div class="checkout-grid">
      <div class="checkout-card">
        <div class="product-landing-cards" style="width: 50vw;">
        <div style=" font-weight: 400; font-size: 16px; line-height: 26px; cursor: pointer; color: #ffffffc2;" onclick="window.location.href='{{ url()->previous() }}'">← Go back</div>
        @include('layouts.checkout-form')
        <div>
      </div>
    </div>

      </div>
      </div>
    </div>
</section>
@endsection