<div class="repeater__row repeater__row--compact" data-row>
    <div class="form-row form-row--3">
        <div class="form-group"><label class="form-label">Number</label><input type="number" name="stats[{{ $i }}][number]" value="{{ $s['number'] ?? '' }}" class="form-control" placeholder="150"></div>
        <div class="form-group"><label class="form-label">Suffix</label><input type="text" name="stats[{{ $i }}][suffix]" value="{{ $s['suffix'] ?? '' }}" class="form-control" placeholder="+" maxlength="4"></div>
        <div class="form-group"><label class="form-label">Label</label><input type="text" name="stats[{{ $i }}][label]" value="{{ $s['label'] ?? '' }}" class="form-control" placeholder="Projects Completed"></div>
    </div>
    <button type="button" class="repeater__del" data-del-row title="Remove"><i class="bi bi-trash3"></i></button>
</div>
