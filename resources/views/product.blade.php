@extends('layouts.app')

@section('single-product-view')
    <section class="explore-products">
        <div class="container">
            <div class="explore-products-content">
                <h1 class="landing-products-title wt-text-truncate" style="text-align: center;">
                    {{ $product->name }}
                </h1>

                <div class="market-grid" style="width:100%; grid-template-columns: 3fr 2fr; grid-template-areas: '. .';">

                    <div style="float: left;">
                        <div class="product-landing-cards">
                            {{-- <h1 class="product-landing-title">{{$product->name}}</h1> --}}
                            <img src="/storage/{{ $product->image }}" alt="Product 1">
                            <p class="product-landing-description">
                                {{ $product->description }}
                            </p>
                        </div>
                    </div>

                    <div style="float: right;">
                        <div class="product-landing-cards">
                            {{-- <div>
            <h2 class="product-landing-purchase-title">Purchase Product</h2>
            <p class="product-landing-purchase-text">Start your purchase by clicking the buy button</p>
          </div> --}}
                            <div>
                                <div class="product-landing-purchase-iteminfo">
                                    <p>Price</p>
                                    <span class="product-landing-purchase-itemprice">{{$StoreSettings['currency']}} {{ $product->price }}</span>
                                </div>
                                <div class="product-landing-purchase-iteminfo">
                                    <p>Delivery Type</p>
                                    <span>{{ $product->type = 'Downloadable' ? 'Digital File' : 'Service' }}</span>
                                </div>
                                <div class="product-landing-purchase-iteminfo">
                                    <p>In Stock</p>
                                    <span>{{ $product->quantity }}</span>
                                </div>
                                <div class="product-landing-purchase-iteminfo">
                                    <p>Quantity</p>
                                    <div class="counter-plusminus">
                                        <button id="quantity-minus" style="background: 0 0; border: 0; color: white;">-</button>
                                        <input name="purchase_qty" type="text" id="quantity-input" value="1" min="1"
                                            max="{{ $product->quantity }}" />
                                        <button id="quantity-plus" style="background: 0 0; border: 0; color: white;">+</button>
                                    </div>
                                </div>
                                <footer class="product-landing-purchase-footerblock">
                                  <button id="buy-now-button">Buy Now</button>
                                </footer>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        </div>
    </section>
@endsection

<script>
  document.addEventListener('DOMContentLoaded', function() {
      const priceElement = document.getElementById('product-price');
      const quantityInput = document.getElementById('quantity-input');
      const quantityMinus = document.getElementById('quantity-minus');
      const quantityPlus = document.getElementById('quantity-plus');
      const buyNowButton = document.getElementById('buy-now-button');
      const productPrice = {{ $product->price }};
      
      function updatePrice() {
          const quantity = parseInt(quantityInput.value);
          const totalPrice = productPrice * quantity;
          priceElement.textContent = `$ ${totalPrice.toFixed(2)}`;
      }

      quantityMinus.addEventListener('click', function() {
          let quantity = parseInt(quantityInput.value);
          if (quantity > 1) {
              quantity -= 1;
              quantityInput.value = quantity;
              updatePrice();
          }
      });

      quantityPlus.addEventListener('click', function() {
          let quantity = parseInt(quantityInput.value);
          if (quantity < {{ $product->quantity }}) {
              quantity += 1;
              quantityInput.value = quantity;
              updatePrice();
          }
      });

      quantityInput.addEventListener('input', function() {
          let quantity = parseInt(quantityInput.value);
          if (isNaN(quantity) || quantity < 1) {
              quantity = 1;
          } else if (quantity > {{ $product->quantity }}) {
              quantity = {{ $product->quantity }};
          }
          quantityInput.value = quantity;
          updatePrice();
      });

      buyNowButton.addEventListener('click', function() {
          const quantity = quantityInput.value;
          const url = `{{ route('checkout', ['slug' => $product->slug]) }}?purchase_qty=${quantity}`;
          window.location.href = url;
      });
  });
</script>
