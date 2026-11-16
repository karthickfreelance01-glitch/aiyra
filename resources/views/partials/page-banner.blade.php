<section class="page-banner">
    <div class="page-banner__bg" style="background-image:url('{{ $image ?? img_url($site['about_banner'] ?? '') }}')" data-parallax-bg></div>
    <div class="page-banner__overlay"></div>
    <div class="container page-banner__inner">
        <nav class="crumbs hero-anim" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a><i class="bi bi-chevron-right"></i>
            @isset($parent)<a href="{{ $parent['url'] }}">{{ $parent['label'] }}</a><i class="bi bi-chevron-right"></i>@endisset
            <span>{{ $crumb ?? $title }}</span>
        </nav>
        <h1 class="page-banner__title"><span class="line"><span class="line__inner">{{ $title }}</span></span></h1>
        @isset($subtitle)<p class="page-banner__sub hero-anim">{{ $subtitle }}</p>@endisset
    </div>
</section>
