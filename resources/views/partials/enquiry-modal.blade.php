@php $services = setting_json('services'); @endphp
<div class="modal" id="enquiryModal" aria-hidden="true">
    <div class="modal__backdrop" data-close-modal></div>
    <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="enqTitle">
        <button class="modal__close" data-close-modal aria-label="Close"><i class="bi bi-x-lg"></i></button>
        <div class="modal__side">
            <img src="{{ asset('assets/images/logo-white.png') }}" alt="">
            <h3>Free Consultation</h3>
            <p>Share a few details and our engineer will call you back within 24 hours.</p>
            <ul>
                <li><i class="bi bi-check2-circle"></i> Free site visit</li>
                <li><i class="bi bi-check2-circle"></i> Detailed estimate</li>
                <li><i class="bi bi-check2-circle"></i> 2D plan guidance</li>
            </ul>
        </div>
        <div class="modal__body">
            <h3 id="enqTitle">Get a Quote</h3>
            @include('partials.enquiry-form', ['source' => 'popup', 'compact' => true])
        </div>
    </div>
</div>
