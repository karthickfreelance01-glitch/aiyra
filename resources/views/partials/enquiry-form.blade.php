@php
    $services = $services ?? setting_json('services');
    $compact = $compact ?? false;
    $uid = 'f' . substr(md5(($source ?? 'contact') . ($projectId ?? '')), 0, 6);
@endphp
<form action="{{ route('enquiry.store') }}" method="POST" class="enq-form js-enquiry" novalidate>
    @csrf
    <input type="hidden" name="source" value="{{ $source ?? 'contact' }}">
    @if(!empty($projectId))<input type="hidden" name="project_id" value="{{ $projectId }}">@endif
    <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off">

    <div class="enq-form__grid {{ $compact ? 'is-compact' : '' }}">
        <div class="field">
            <input type="text" name="name" id="{{ $uid }}-name" placeholder=" " required maxlength="120">
            <label for="{{ $uid }}-name">Your Name *</label>
        </div>
        <div class="field">
            <input type="tel" name="phone" id="{{ $uid }}-phone" placeholder=" " required maxlength="20">
            <label for="{{ $uid }}-phone">Phone Number *</label>
        </div>
        @unless($compact)
        <div class="field">
            <input type="email" name="email" id="{{ $uid }}-email" placeholder=" " maxlength="150">
            <label for="{{ $uid }}-email">Email Address</label>
        </div>
        <div class="field">
            <input type="text" name="location" id="{{ $uid }}-loc" placeholder=" " maxlength="150">
            <label for="{{ $uid }}-loc">Site Location</label>
        </div>
        @endunless
        <div class="field field--full">
            <select name="service" id="{{ $uid }}-svc">
                <option value="">Select a service</option>
                @foreach($services as $s)
                    <option value="{{ $s['title'] }}" @selected(($selectedService ?? '') === $s['title'])>{{ $s['title'] }}</option>
                @endforeach
                <option value="Other">Other</option>
            </select>
            <label for="{{ $uid }}-svc" class="is-static">Service Required</label>
        </div>
        <div class="field field--full">
            <textarea name="message" id="{{ $uid }}-msg" rows="{{ $compact ? 3 : 4 }}" placeholder=" " maxlength="3000"></textarea>
            <label for="{{ $uid }}-msg">Tell us about your project</label>
        </div>
    </div>
    <div class="enq-form__error" role="alert"></div>
    <button type="submit" class="btn btn--primary btn--block magnetic">
        <span>Send Enquiry</span><i class="bi bi-send"></i>
    </button>
</form>
