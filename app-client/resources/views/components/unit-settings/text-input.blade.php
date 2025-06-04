@props([
    'name',
    'label',
    'value' => '',
    'required' => false
])

<div>
    <label for="{{ $name }}" class="label-style">{{ $label }}</label>
    <input
        type="text"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        class="input-style"
        {{ $required ? 'required' : '' }}
    >
</div>
