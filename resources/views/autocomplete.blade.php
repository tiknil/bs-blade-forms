@php($livewire = is_subclass_of(static::class, \Livewire\Component::class))

@if(!empty($label))
<x-bs::label name="{{ $name }}">
  {{ $label }}
</x-bs::label>
@endif

<div class="ac-wrapper" @if($livewire) wire:ignore.self data-livewire wire:key="ac-wrapper-{{ $name }}" @endif
  @if(!empty($id)) id="{{ $id }}" @endif @if(!empty($fetchUrl)) data-fetchurl="{{ $fetchUrl }}" @endif>

  {{--
  The actual select element, hidden in the UI but required for easier integration with the browser:
  * Sends data along with the form
  * Browser automatic validation for missing data
  * Livewire attributes work (e.g. wire:model)
  --}}
  <select name="{{ $name }}" id="{{ $name }}" tabindex="-1" 
    @if($livewire) wire:key="ac-{{ $name }}" @endif @if($required) required @endif {{ $attributes->whereStartsWith('wire:model') }}
    class="ss-ghost-select">

    <option @selected(($value ?? $emptyValue)==$emptyValue) value="{{ $emptyValue }}"></option>

    @if($slot->hasActualContent())
    {{ $slot }}
    @else
    @foreach($options as $key => $label)
    <option value="{{ $key }}" @selected($value==$key) wire:key="{{$name}}-{{ $key}}" >{{ $label }}</option>
    @endforeach 
    @endif

  </select>

  <x-bs::icon-group icon-class="{{ $icon }}" wire:ignore>
    {{-- The element shown in the UI as the input field --}}
    <input type="text" class="form-control ac-input" placeholder="{{ $placeholder }}" autocomplete="off" />
  </x-bs::icon-group>

  {{-- Option dropdown --}}
  <div class="ss-dropdown hidden" @if($livewire) wire:ignore @endif>
    {{-- This element is cloned by JS to render an option --}}
    <template class="ss-option-template">
      <div class="ss-option" data-key="">
        <span></span>
        @if($allowClear)
        <i class="{{ config('bs-blade-forms.icons.unselect') }} ss-remove-icon"></i>
        @endif
      </div>
    </template>

    {{-- The actual options are created through JS --}}
    <div class="ss-options">
    </div>

    <div class="text-muted p-2 empty-results">
      {{ __('bs-blade-forms::components.autocomplete.no-results') }}
    </div>
  </div>
</div>
