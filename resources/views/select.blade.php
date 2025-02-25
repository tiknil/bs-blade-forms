@if(!empty($label))
<x-bs::label name="{{ $name }}">
  {{ $label }}
</x-bs::label>
@endif

<x-bs::icon-group icon-class="{{ $icon }}">
  <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-select']) }}>
    @if($emptyOption)
    <option value="" wire:key="{{$name}}-empty">{{ $emptyOption }}</option>
    @endif

    @if($slot->hasActualContent())
    {{ $slot }}
    @else
    @foreach($options as $key => $label)
    <option value="{{ $key }}" @selected($key==$value) wire:key="{{$name}}-{{ $key}}" >{{ $label }}</option>
    @endforeach
    @endif
  </select>
</x-bs::icon-group>