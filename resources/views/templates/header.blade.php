<header class="site-header sticky-top">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container">
        <a class="navbar-brand" href="/" title="{{ $siteName ?? '' }}">
          <img src="{{$logo->value}}" alt="{{ $siteName ?? '' }}">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        @include('templates.header.navigation', [
            'menu' => $menu,
        ])
      </div>
    </nav>
</header>