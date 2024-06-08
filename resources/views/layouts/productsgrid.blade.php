<div class="market-grid">
    @foreach ($products as $product)
        <div class="market-card  {{ $product->quantity > 0 ? '' : 'deactive' }}">
            <div class="prewiew-part-of-card">
                <img class="preview-img" src="/storage/{{ $product->image }}" alt="{{ $product->name }}">
                {{-- /*<h1 class="prewiew-title-of-card">{{ $product->brand->name }}</h1> */ --}}
            </div>
            <div class="card-coast">
                <h2 class="coast-title-card wt-text-truncate">{{ $product->name }}</h2>
                <div class="card-coast-and-quantity">
                    <div class="coast">
                        <strong>$ {{ $product->price }}</strong>
                    </div>
                    <div class="quantity">
                        <img src="/frontend1/img/block-badged-icon.svg" alt="icon: block-badged-icon">
                        <p class="quantity-text">{{ $product->quantity }} In stock</p>
                    </div>
                </div>
            </div>
            @if ($product->quantity > 0)
            <a style="cursor: pointer; display: flex; align-items: center;" href="/product/{{ $product->slug }}" class="buy-now-block">
                <strong class="buy-now-strong-text">Buy Now</strong>
                <p class="buy-now-text" style="margin-left: auto;">from {{ $product->price }} {{$StoreSettings['currency']}}</p>
                <img src="/frontend1/img/rvector.svg" alt="icon: arrow">
            </a>
            @else
                <a style="cursor: pointer;" class="buy-now-block">
                    <p class="buy-now-text">Out of stock</p>
                    <img src="/frontend1/img/x-icon.svg" alt="icon: x-icon">
                </a>
            @endif
        </div>
    @endforeach
</div>
@if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
<div class="pagination">
    {{ $products->links('pagination::bootstrap-4') }}
</div>
@endif