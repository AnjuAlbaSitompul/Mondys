<label for="{{ $id }}" class="form-label">{{ $label }}</label>
<div class="input-group has-validation">
    <button type="button" class="btn btn-primary">{{ $btnTxt }}</button>
    <input type="{{ $type }}" id="{{ $id }}" class="form-control" placeholder="{{ $placeholder }}"
        {{ $disabled ? 'disabled' : '' }}>
    <div class="invalid-feedback">
        {{ $invalid }}
    </div>
</div>
