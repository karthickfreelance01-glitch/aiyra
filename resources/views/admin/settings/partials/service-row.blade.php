<div class="repeater__row" data-row>
    <div class="repeater__handle"><i class="bi bi-grip-vertical"></i><span class="repeater__num"></span></div>
    <div class="icon-select">
        <span class="icon-select__preview"><i class="bi {{ $s['icon'] ?? 'bi-buildings' }}"></i></span>
        <select name="services[{{ $i }}][icon]" class="form-control" data-icon-select>
            @foreach($icons as $ic)<option value="{{ $ic }}" @selected(($s['icon'] ?? 'bi-buildings') === $ic)>{{ str_replace('bi-', '', $ic) }}</option>@endforeach
        </select>
    </div>
    <div class="repeater__fields">
        <div class="form-group"><label class="form-label">Title</label><input type="text" name="services[{{ $i }}][title]" value="{{ $s['title'] ?? '' }}" class="form-control"></div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="services[{{ $i }}][text]" rows="2" class="form-control">{{ $s['text'] ?? '' }}</textarea></div>
    </div>
    <button type="button" class="repeater__del" data-del-row title="Remove"><i class="bi bi-trash3"></i></button>
</div>
