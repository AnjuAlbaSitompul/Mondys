@props(['label', 'name', 'options', 'value', 'id'])

@if ($label)
    <label class="form-label">{{ $label }}</label>
@endif
<select name="{{ $name }}" id="{{ $id }}" {{ $attributes->merge(['class' => 'form-select']) }}>
    @foreach ($options as $key => $option)
        <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>
            {{ $option }}
        </option>
    @endforeach
</select>

@error($name)
    <div class="text-danger">{{ $message }}</div>
@enderror
