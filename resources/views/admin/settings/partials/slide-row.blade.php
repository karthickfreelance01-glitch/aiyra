<div class="repeater__row repeater__row--slide" data-row>
    <div class="repeater__handle"><i class="bi bi-grip-vertical"></i><span class="repeater__num"></span></div>
    <label class="image-picker image-picker--slide {{ !empty($s['image']) ? 'has-image' : '' }}">
        <input type="file" name="hero_slides[{{ $i }}][image_file]" accept="image/jpeg,image/png,image/webp" data-preview>
        <input type="hidden" name="hero_slides[{{ $i }}][image]" value="{{ $s['image'] ?? '' }}">
        <img src="{{ !empty($s['image']) ? img_url($s['image']) : '' }}" alt="">
        <div class="image-picker__empty"><i class="bi bi-image"></i><span>Slide image</span></div>
        <span class="image-picker__change"><i class="bi bi-arrow-repeat"></i></span>
    </label>
    <div class="repeater__fields">
        <div class="form-row">
            <div class="form-group"><label class="form-label">Title (line 1)</label><input type="text" name="hero_slides[{{ $i }}][title]" value="{{ $s['title'] ?? '' }}" class="form-control" placeholder="We Build"></div>
            <div class="form-group"><label class="form-label">Highlight (line 2, orange)</label><input type="text" name="hero_slides[{{ $i }}][highlight]" value="{{ $s['highlight'] ?? '' }}" class="form-control" placeholder="Your Dream Home"></div>
        </div>
        <div class="form-group"><label class="form-label">Subtitle</label><input type="text" name="hero_slides[{{ $i }}][subtitle]" value="{{ $s['subtitle'] ?? '' }}" class="form-control"></div>
    </div>
    <button type="button" class="repeater__del" data-del-row title="Remove"><i class="bi bi-trash3"></i></button>
</div>
