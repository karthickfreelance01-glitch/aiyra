@php $services = setting_json('services'); @endphp
<footer class="footer">
    <div class="footer__glow"></div>
    <div class="container">
        <div class="footer__cta" data-reveal>
            <h2 class="footer__big"><span class="split-chars">Let's Build</span> <em class="split-chars">Together.</em></h2>
            <button class="btn btn--primary btn--lg magnetic" data-open-enquiry><span>Start Your Project</span><i class="bi bi-arrow-up-right"></i></button>
        </div>

        <div class="footer__grid">
            <div class="footer__col footer__about" data-reveal>
                <img src="{{ asset('assets/images/logo-white.png') }}" alt="{{ $site['site_name'] ?? '' }}" class="footer__logo">
                <p>{{ $site['footer_about'] ?? '' }}</p>
                <div class="footer__social">
                    @foreach(['facebook' => 'facebook', 'instagram' => 'instagram', 'youtube' => 'youtube', 'linkedin' => 'linkedin'] as $k => $icon)
                        @if(!empty($site[$k]))<a href="{{ $site[$k] }}" target="_blank" rel="noopener" class="magnetic" aria-label="{{ $k }}"><i class="bi bi-{{ $icon }}"></i></a>@endif
                    @endforeach
                    @if(!empty($site['whatsapp']))<a href="{{ wa_link($site['whatsapp']) }}" target="_blank" rel="noopener" class="magnetic" aria-label="whatsapp"><i class="bi bi-whatsapp"></i></a>@endif
                </div>
            </div>

            <div class="footer__col" data-reveal>
                <h4>Quick Links</h4>
                <ul class="footer__links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('services') }}">Services</a></li>
                    <li><a href="{{ route('projects') }}">Projects</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>

            <div class="footer__col" data-reveal>
                <h4>Our Services</h4>
                <ul class="footer__links">
                    @foreach($services as $s)
                        <li><a href="{{ route('services') }}">{{ $s['title'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="footer__col" data-reveal>
                <h4>Get In Touch</h4>
                <ul class="footer__contact">
                    @if(!empty($site['address']))<li><i class="bi bi-geo-alt"></i><span>{{ $site['address'] }}</span></li>@endif
                    @if(!empty($site['phone']))<li><i class="bi bi-telephone"></i><a href="tel:{{ preg_replace('/\s+/', '', $site['phone']) }}">{{ $site['phone'] }}</a></li>@endif
                    @if(!empty($site['phone2']))<li><i class="bi bi-phone"></i><a href="tel:{{ preg_replace('/\s+/', '', $site['phone2']) }}">{{ $site['phone2'] }}</a></li>@endif
                    @if(!empty($site['email']))<li><i class="bi bi-envelope"></i><a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></li>@endif
                    @if(!empty($site['gstin']))<li><i class="bi bi-receipt"></i><span>GSTIN : {{ $site['gstin'] }}</span></li>@endif
                </ul>
            </div>
        </div>

        <div class="footer__bottom">
            <p>{{ $site['copyright_text'] ?? ('© ' . date('Y') . ' Aiyra Constructions') }}</p>
            <p class="footer__tag">{{ $site['tagline'] ?? '' }}</p>
        </div>
    </div>
</footer>
