@extends('layouts.app')

@section('productsgrid')
    <section class="explore-products">
        <div class="main-container">
            <div class="main-content">
                <div class="titles-group">
                    <p class="chime-terms-title">{{ $StoreSettings['store_name'] ?? 'Store' }}</p>
                    <h1 class="terms-of-servise-title">Terms of Use</h1>
                </div>

                <div class="main-text-block">
                    <p class="chime-terms-text">{!! nl2br(e($StoreSettings['terms_of_use'] ?: 'Terms of use have not been published yet. Please contact us before making a purchase.')) !!}</p>
                </div>
            </div>
        </div>
    </section>
@endsection
