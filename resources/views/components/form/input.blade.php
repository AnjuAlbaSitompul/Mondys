@props(['label', 'name', 'type', 'id', 'placeholder', 'valid'])
@if ($label)
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
@endif
<input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeholder }}"
    value="{{ $value }}" {{ $attributes->merge(['class' => 'form-control']) }}>
<div class="invalid-feedback">
    {{ $valid }}
</div>
