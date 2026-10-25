<div class="container">
    <div class="header-content">
      <div class="logo-box">
        <a href="/" class="logo">
          {{-- <H1 class="text-xl font-medium">{{$StoreSettings['store_name']}}</H1> --}}
          <H1 class="product-landing-purchase-itemprice" style=" font-family: Blue-curve; ">{{$StoreSettings['store_name']}}</H1>
          {{-- <img src="/frontend1/img/Logo.svg" alt="{{$StoreSettings['store_name']}}"> --}}
        </a>
      </div>
      <div class="items-box">
        <a href="{{ route('terms') }}" class="term-logo">
          <img src="/frontend1/img/terms-logo.svg" alt="img: terms of use">
          <p class="terms-text">Terms of use</p>
        </a>
      </div>
    </div>
  </div>
