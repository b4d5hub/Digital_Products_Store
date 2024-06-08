@extends('layouts.app')



@section('single-product-view')
<section class="explore-products">
    <div class="container">
      <div class="explore-products-content">
        <h1 class="explore-products-title" style="text-align: center;">
          Digital Item Download
        </h1>
      <div class="checkout-grid">
      <div class="checkout-card">
        <div class="product-landing-cards" style="width: 50vw;">
        <!-- <div style=" font-weight: 400; font-size: 16px; line-height: 26px; cursor: pointer; color: #ffffffc2;" onclick="window.location.href='{{ url()->previous() }}'">← Go back</div> -->
        @if (session('successmessage'))
        <div class="alert alert-success">
            {{ session('successmessage') }}
        </div>
        @endif
        
        <!-- SHOW PRODUCT DESCRIPTION HERE -->
        <div>
            <div class="product-landing-purchase-iteminfo">
                <p>Order:</p>
                <span>{{$order_number}}</span>
            </div>
            <div class="product-landing-purchase-iteminfo">
                <p>Deliverable:</p>
                <span>{{$order->download_data}}</span>
                {{$test}}
            </div>
        </div>
        <div>
      </div>
    </div>

      </div>
      </div>
    </div>
</section>
@endsection