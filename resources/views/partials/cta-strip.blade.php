<section class="cta-strip">
    <div class="container cta-strip__inner" data-reveal>
        <div>
            <h3>{{ $site['cta_title'] ?? 'Ready to build your dream project?' }}</h3>
            <p>{{ $site['cta_text'] ?? '' }}</p>
        </div>
        <div class="cta-strip__actions">
            <button class="btn btn--light magnetic" data-open-enquiry><span>Get a Quote</span><i class="bi bi-arrow-up-right"></i></button>
            <a href="tel:{{ preg_replace('/\s+/', '', $site['phone'] ?? '') }}" class="btn btn--ghost magnetic"><span>Call Now</span><i class="bi bi-telephone"></i></a>
        </div>
    </div>
</section>
