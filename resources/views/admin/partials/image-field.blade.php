{{-- Usage: @include('admin.partials.image-field', ['key' => 'about_image', 'label' => 'Image', 'hint' => '...']) --}}
@php $val = $site[$key] ?? ''; @endphp
<div class="form-group">
    <label class="form-label">{{ $label }} @isset($hint)<small>{{ $hint }}</small>@endisset</label>
    <label class="image-picker image-picker--wide {{ $val ? 'has-image' : '' }}">
        <input type="file" name="{{ $key }}_file" accept="image/jpeg,image/png,image/webp" data-preview>
        <img src="{{ $val ? img_url($val) : '' }}" alt="">
        <div class="image-picker__empty"><i class="bi bi-image"></i><span>Click to upload</span></div>
        <span class="image-picker__change"><i class="bi bi-arrow-repeat"></i> Change</span>
    </label>
    @if($val && empty($required))
        <label class="check check--sm"><input type="checkbox" name="{{ $key }}_remove" value="1"><span></span> Remove image</label>
    @endif
</div>
