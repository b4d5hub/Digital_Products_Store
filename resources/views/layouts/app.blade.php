<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $StoreSettings['store_name'] ?? 'Store by B4D5HUB' }}</title>
    <link rel="stylesheet" href="/frontend1/css//reset_min.css">
    <link rel="stylesheet" href="/frontend1/css//style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#020307">
    <meta name="msapplication-TileColor" content="#020307">
    <meta name="theme-color" content="#020307">
</head>
<style>
    :root {
        --primary-color: {{ $StoreSettings['primary_color'] ?? '#020307' }};
        --secondary-color: {{ $StoreSettings['secondary_color'] ?? '#1c1e25' }};
        --text-dark: {{ $StoreSettings['text_dark'] ?? 'var(--primary-color)' }};
        --text-light: {{ $StoreSettings['text_light'] ?? 'var(--secondary-color)' }};
    }
</style>
<body>
    <header class="header">
        @include('layouts.navbar')
    </header>

    @yield('presentation')
    @yield('information')
    @yield('productsgrid')
    @yield('single-product-view')


    <footer class="footer">
        @include('layouts.footer')
    </footer>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(".search-input").on("input", function() {
            var searchText = $(this).val().toLowerCase();
            $(".market-grid .market-card").each(function() {
                var cardText = $(this).text().toLowerCase();
                if (cardText.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    </script>
</body>

</html>
