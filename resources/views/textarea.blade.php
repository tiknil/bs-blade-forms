
@if(!empty($label))
  <x-bs::label name="{{ $name }}">
    {{ $label }}
  </x-bs::label>
@endif

<textarea id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control']) }}>{{ $value ?: '' }}</textarea>

