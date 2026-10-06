@if(!empty($label))
  <x-bs::label name="{{ $name }}">
    {{ $label }}
  </x-bs::label>
@endif

<x-bs::icon-group icon-class="{{ $icon }}" icon-text="{{ $iconText }}">
  <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ $value ?: '' }}" {{ $attributes->merge(['class' => 'form-control']) }} />
</x-bs::icon-group>
