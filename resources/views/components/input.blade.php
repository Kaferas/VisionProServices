<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if (isset($required) && $required == true)
            <span class="text-danger">*</span>
        @endif
    </label>

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        class="form-control border border-primary @error($name) is-invalid @enderror"
    >

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
