@props(['label', 'name', 'type', 'id', 'toggle', 'target', 'name', 'placeholder', 'disabled', 'btnTxt', 'invalid'])

<label for="{{ $id }}" class="form-label">{{ $label }}</label>
<div class="input-group has-validation">
    <button type="button" class="btn btn-primary" data-bs-toggle="{{ $toggle }}"
        data-bs-target="{{ $target }}">{{ $btnTxt }}</button>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" class="form-control"
        placeholder="{{ $placeholder }}" {{ $disabled ? 'disabled' : '' }}>
    <div class="invalid-feedback">
        {{ $invalid }}
    </div>
</div>
