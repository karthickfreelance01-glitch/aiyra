<div class="repeater__row" data-row>
    <div class="repeater__handle"><i class="bi bi-grip-vertical"></i><span class="repeater__num"></span></div>
    <div class="repeater__fields">
        <div class="form-row form-row--1-2">
            <div class="form-group"><label class="form-label">Title</label><input type="text" name="{{ $name }}[{{ $i }}][title]" value="{{ $s['title'] ?? '' }}" class="form-control"></div>
            <div class="form-group"><label class="form-label">Text</label><input type="text" name="{{ $name }}[{{ $i }}][text]" value="{{ $s['text'] ?? '' }}" class="form-control"></div>
        </div>
    </div>
    <button type="button" class="repeater__del" data-del-row title="Remove"><i class="bi bi-trash3"></i></button>
</div>
