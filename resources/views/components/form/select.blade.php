@props(['label', 'name', 'options', 'value'])

@if ($label)
    <label class="form-label">{{ $label }}</label>
@endif
<select name="{{ $name }}" {{ $attributes->merge(['class' => 'form-select']) }}>
    @foreach ($options as $key => $option)
        <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>
            {{ $option }}
        </option>
    @endforeach
</select>

@error($name)
    <div class="text-danger">{{ $message }}</div>
@enderror
