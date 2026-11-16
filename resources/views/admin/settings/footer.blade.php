@extends('admin.layouts.app')
@section('title', 'Footer & Contact')
@section('subtitle', 'Company details used in the header, footer, contact page and WhatsApp button.')

@section('content')
<form action="{{ route('admin.settings.footer.update') }}" method="POST" class="settings-form">
    @csrf

    <div class="grid-2 grid-2--top">
        <div class="card">
            <div class="card__head"><h3><i class="bi bi-building"></i> Company</h3></div>
            <div class="card__body">
                <div class="form-group"><label class="form-label">Company Name <b>*</b></label><input type="text" name="site_name" value="{{ old('site_name', $site['site_name'] ?? '') }}" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Tagline</label><input type="text" name="tagline" value="{{ old('tagline', $site['tagline'] ?? '') }}" class="form-control"></div>
                <div class="form-group"><label class="form-label">Footer About Text</label><textarea name="footer_about" rows="4" class="form-control">{{ old('footer_about', $site['footer_about'] ?? '') }}</textarea></div>
                <div class="form-group"><label class="form-label">GSTIN</label><input type="text" name="gstin" value="{{ old('gstin', $site['gstin'] ?? '') }}" class="form-control"></div>
                <div class="form-group"><label class="form-label">Copyright Text</label><input type="text" name="copyright_text" value="{{ old('copyright_text', $site['copyright_text'] ?? '') }}" class="form-control"></div>
            </div>
        </div>

        <div class="card">
            <div class="card__head"><h3><i class="bi bi-telephone"></i> Contact Details</h3></div>
            <div class="card__body">
                <div class="form-group"><label class="form-label">Address</label><textarea name="address" rows="2" class="form-control">{{ old('address', $site['address'] ?? '') }}</textarea></div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Phone <b>*</b></label><div class="input-icon"><i class="bi bi-telephone"></i><input type="text" name="phone" value="{{ old('phone', $site['phone'] ?? '') }}" class="form-control" required></div></div>
                    <div class="form-group"><label class="form-label">Phone 2</label><div class="input-icon"><i class="bi bi-phone"></i><input type="text" name="phone2" value="{{ old('phone2', $site['phone2'] ?? '') }}" class="form-control"></div></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Email</label><div class="input-icon"><i class="bi bi-envelope"></i><input type="email" name="email" value="{{ old('email', $site['email'] ?? '') }}" class="form-control"></div></div>
                    <div class="form-group"><label class="form-label">WhatsApp Number</label><div class="input-icon"><i class="bi bi-whatsapp"></i><input type="text" name="whatsapp" value="{{ old('whatsapp', $site['whatsapp'] ?? '') }}" class="form-control" placeholder="9047046011"></div></div>
                </div>
                <div class="form-group"><label class="form-label">Working Hours</label><div class="input-icon"><i class="bi bi-clock"></i><input type="text" name="working_hours" value="{{ old('working_hours', $site['working_hours'] ?? '') }}" class="form-control"></div></div>
            </div>
        </div>
    </div>

    <div class="grid-2 grid-2--top">
        <div class="card">
            <div class="card__head"><h3><i class="bi bi-share"></i> Social Media</h3><small class="muted">Leave empty to hide</small></div>
            <div class="card__body">
                @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'linkedin' => 'LinkedIn'] as $k => $label)
                    <div class="form-group"><label class="form-label">{{ $label }}</label><div class="input-icon"><i class="bi bi-{{ $k }}"></i><input type="text" name="{{ $k }}" value="{{ old($k, $site[$k] ?? '') }}" class="form-control" placeholder="https://"></div></div>
                @endforeach
            </div>
        </div>
        <div class="card">
            <div class="card__head"><h3><i class="bi bi-geo-alt"></i> Google Map</h3></div>
            <div class="card__body">
                <div class="form-group">
                    <label class="form-label">Map Embed <small>(paste the Google Maps “Embed a map” iframe code or its src URL)</small></label>
                    <textarea name="map_embed" rows="3" class="form-control" id="mapInput">{{ old('map_embed', $site['map_embed'] ?? '') }}</textarea>
                </div>
                @if(!empty($site['map_embed']))
                    <div class="map-preview"><iframe src="{{ $site['map_embed'] }}" loading="lazy" title="Map preview"></iframe></div>
                @endif
            </div>
        </div>
    </div>

    <div class="save-bar">
        <span class="muted"><i class="bi bi-info-circle"></i> These details appear on every page.</span>
        <a href="{{ route('contact') }}" target="_blank" class="btn btn--light"><i class="bi bi-eye"></i> Preview</a>
        <button type="submit" class="btn btn--primary" data-loading-text="Saving…"><i class="bi bi-check2-circle"></i> Save Footer & Contact</button>
    </div>
</form>
@endsection
