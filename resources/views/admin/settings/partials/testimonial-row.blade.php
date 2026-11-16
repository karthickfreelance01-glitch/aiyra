<div class="repeater__row" data-row>
    <div class="repeater__handle"><i class="bi bi-grip-vertical"></i><span class="repeater__num"></span></div>
    <div class="repeater__fields">
        <div class="form-row">
            <div class="form-group"><label class="form-label">Client Name</label><input type="text" name="testimonials[{{ $i }}][name]" value="{{ $s['name'] ?? '' }}" class="form-control"></div>
            <div class="form-group"><label class="form-label">Role / Place</label><input type="text" name="testimonials[{{ $i }}][role]" value="{{ $s['role'] ?? '' }}" class="form-control" placeholder="Home Owner, Pollachi"></div>
        </div>
        <div class="form-group"><label class="form-label">Review</label><textarea name="testimonials[{{ $i }}][text]" rows="2" class="form-control">{{ $s['text'] ?? '' }}</textarea></div>
    </div>
    <button type="button" class="repeater__del" data-del-row title="Remove"><i class="bi bi-trash3"></i></button>
</div>
