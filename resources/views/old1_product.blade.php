<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <script defer="" data-domain="digiworld.cc" src="https://plausible.io/js/script.js"></script>
  <!-- TRACK USER CLICKS HEAT -->
  <script type="text/javascript">
    window.smartlook||(function(d) {
      var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
      var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
      c.charset='utf-8';c.src='https://web-sdk.smartlook.com/recorder.js';h.appendChild(c);
      })(document);
      smartlook('init', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', { region: 'eu' });
  </script>
  <script async="" type="text/javascript" charset="utf-8" src="https://web-sdk.smartlook.com/recorder.js"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verisio</title>
  <link rel="stylesheet" href="/frontend1/css//reset_min.css">
  <link rel="stylesheet" href="/frontend1/css//style.css">
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/site.webmanifest">
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#020307">
  <script src="https://embed.sellpass.io/embed.js"></script>
  <meta name="msapplication-TileColor" content="#020307">
  <meta name="theme-color" content="#020307">
</head>
<body>
  <header class="header">
    <div class="container">
      <div class="header-content">
        <div class="logo-box">
          <a href="/" class="logo">
            <img src="/frontend1/img/Logo.svg" alt="logo: Verisio">
          </a>
        </div>
        <div class="items-box">
          <a href="/terms.html" class="term-logo">
            <img src="/frontend1/img/terms-logo.svg" alt="img: terms of use">
            <p class="terms-text">Terms of use</p>
          </a>
        </div>
      </div>
    </div>
  </header>
  <section id="products" class="explore-products">
    <div class="container">
      <div class="explore-products-content">
        <div class="market-grid coast-title-card">
            {{$product}}
            </div>
        </div>
      </div>
    </div>
  </section>
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="copyright-block">
          <p class="copyright-text">© Copyright 2023</p>
        </div>
        <div class="logo-box">
          <a href="/" class="logo">
            <img src="/frontend1/img/Logo.svg" alt="logo: Verisio">
          </a>
        </div>
        <div class="items-box">
          <a href="/terms.html" class="term-logo">
            <img src="/frontend1/img/terms-logo.svg" alt="img: terms of use">
            <p class="terms-text">Terms of use</p>
          </a>
        </div>
      </div>
    </div>
  </footer>
  {{-- <script src="/frontend1/js/itc-custom-select.js"></script> --}}
  {{-- <script>
    document.addEventListener("DOMContentLoaded", () => {
      ItcCustomSelect.create("#select-1", {
        name: "market",
        targetValue: "",
        options: [
          ["bank", "Bank"],
          ["cookies", "Cookies"],
          ["emulator", "Emulator"],
          ["1337  ", "1337"],
        ],
        onSelected(select, option) {
          // selected value
          console.log(`Selected value: ${select.value}`);
          // selected option index
          console.log(`Selected option index: ${select.selectedIndex}`);
          // selected option text
          const text = option ? option.textContent : "";
          console.log(`Selected option text: ${text}`);
        },
      });
      document
        .querySelector(".itc-select")
        .addEventListener("itc.select.change", (e) => {
          const btn = e.target.querySelector(".itc-select__toggle");
          // Selected value
          console.log(`Selected value: ${btn.value}`);
          // Selected option index
          console.log(`Selected option index: ${btn.dataset.index}`);
          // Selected option text
          const selected = e.target.querySelector(
            ".itc-select__option_selected"
          );
          const text = selected ? selected.textContent : "";
          console.log(`Selected option text: ${text}`);
        });
    });
  </script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    $(".search-input").on("input", function () {
      var searchText = $(this).val().toLowerCase();
      $(".market-grid .market-card").each(function () {
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
