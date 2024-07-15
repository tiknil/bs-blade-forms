
<div class="form-check">

  <input type="radio"
         value="{{ $value }}"
         name="{{ $name }}"
         id="{{ $name }}_{{ $value }}"
        @checked($checked) {{ $attributes->merge(['class' => 'form-check-input']) }} />

  <x-bs::label name="{{ $name }}_{{ $value }}" checkbox>
    {{ $label }}
  </x-bs::label>
</div>
