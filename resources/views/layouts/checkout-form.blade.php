        <div>
            <h2 class="product-landing-purchase-title" style="text-align: center;">Product Overview</h2>
            {{-- <p class="product-landing-purchase-text">Enter the email address you would like to receive the product to          </p> --}}
          </div>
        <div style=" width: 80%; margin-bottom: 15px; ">
            
            {{-- <div style="text-align: center;">
                <h2 style="color: #fff; font-size: 20px; padding-bottom: 30px; font-weight: bold;">{{$product->name}}</h2>
            </div> --}}
            
            <div class="product-landing-purchase-iteminfo">
                <p>Product</p>
                <span class="product-landing-purchase-itemprice wt-text-truncate">{{$product->name}}</span>
            </div>
                
            <div class="product-landing-purchase-iteminfo">
            <p>Purchase Quantity</p>
            <span>x {{$quantity}}</span>
            </div>

            <div class="product-landing-purchase-iteminfo">
            <p>Total</p>
            <span class="product-landing-purchase-itemprice">{{$StoreSettings['currency']}} {{$totalPrice}}</span>
            </div>
        
            <div class="product-landing-purchase-iteminfo">
            <p>Delivery Type</p>
            <span>{{ $product->type = 'Downloadable' ? 'Digital File' : 'Service' }}</span>
            </div>
        </div>
        

        <div>
            <h2 class="product-landing-purchase-title" style="text-align: center;">Product Delivery</h2>
            <p class="product-landing-purchase-text">Enter the email address you would like to receive the product to          </p>
          </div>
          <div style="width: 80%;">
            <form action="{{ route('paypal') }}" method="post">
                @csrf
                <input type="text" name="email" placeholder="Email for product delivery" style="width: 100%;margin-bottom: 10px;background: #76717a69;border-radius: 15px;padding: 10px 10px;border: 0;color: #ffffffd1;"/>
                <input type="hidden" name="product_id" value="{{$product->id}}">
                <input type="hidden" name="price" value="{{$product->price}}">
                <input type="hidden" name="product_name" value="{{$product->name}}">
                <input type="hidden" name="quantity" value="{{$quantity}}">
                <button type="submit" style="padding: 10px 10px;border-radius: 12px;border: 0;cursor: pointer;margin-top: 11px;width: 100%;background: #2d831d;color: #fff;">
                  <span style=" margin-top: 1px; font-size: 14px; ">Buy Now</span>
                </button>
            </form>
          </div>  



        