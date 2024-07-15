
@if(!empty($label))
  <x-bs::label name="{{ $name }}">
    {{ $label }}
  </x-bs::label>
@endif

<x-bs::icon-group icon-class="{{ $icon }}">
  <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-select']) }}>
    @if($emptyOption)
      <option value="">{{ $emptyOption }}</option>
    @endif

    @if($slot->hasActualContent())
      {{ $slot }}
    @else
      @foreach($options as $key => $label)
        <option value="{{ $key }}" @selected($key == $value)>{{ $label }}</option>
      @endforeach
    @endif
  </select>
</x-bs::icon-group>
