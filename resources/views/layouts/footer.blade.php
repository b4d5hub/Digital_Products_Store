
    <div class="container">
      <div class="footer-content">
        <div class="copyright-block">
          <p class="copyright-text">{{ $StoreSettings['store_name'] }} © Copyright 2023{{ now()->year > 2023 ? '–' . now()->year : '' }}</p>
        </div>
        <div class="logo-box">
          <a href="/" class="logo">
            {{-- <img src="/frontend1/img/Logo.svg" alt="logo: {{$StoreSettings['store_name']}}"> --}}
            <H1 class="product-landing-purchase-itemprice" style=" font-family: Blue-curve; ">{{$StoreSettings['store_name']}}</H1>
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
