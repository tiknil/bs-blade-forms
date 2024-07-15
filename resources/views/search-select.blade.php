@php($livewire = is_subclass_of(static::class, \Livewire\Component::class))

@if(!empty($label))
  <x-bs::label name="{{ $name }}">
    {{ $label }}
  </x-bs::label>
@endif

<div class="ss-wrapper"
     @if($livewire) wire:ignore.self data-livewire @endif
     @if(!empty($id)) id="{{ $id }}" @endif>

  {{--
    The actual select element, hidden in the UI but required for easier integration with the browser:
    * Sends data along with the form
    * Browser automatic validation for missing data
    * Livewire attributes work (e.g. wire:model)
  --}}
  <select name="{{ $name }}"
          id="{{ $name }}"
          tabindex="-1"
          @if($livewire) wire:key="ss-{{ $name }}" @endif
          @if($required) required @endif
          {{ $attributes->whereStartsWith('wire:model') }}
          class="ss-ghost-select">

    <option @selected(($value ?? $emptyValue) == $emptyValue) value="{{ $emptyValue }}"></option>

    @if($slot->hasActualContent())
      {{ $slot }}
    @else
      @foreach($options as $key => $label)
        <option value="{{ $key }}" @selected($value == $key)>{{ $label }}</option>
      @endforeach
    @endif

  </select>

  <x-bs::icon-group icon-class="{{ $icon }}">
    {{-- The element shown in the UI as if it's the actual select element --}}
    <div role="button"
         @if($livewire) wire:ignore @endif
         aria-label="{{ $placeholder }}"
         tabindex="0"
         class="form-select ss-box"
    >

      <div class="text-muted ss-placeholder">
        @if(empty($placeholder))
          &nbsp;
        @else
          {{ $placeholder }}
        @endif</div>
      <div class="ss-value-label" style="display:none;"></div>
    </div>
  </x-bs::icon-group >

  {{-- Option dropdown --}}
  <div class="ss-dropdown hidden"
       @if($livewire) wire:ignore @endif
  >

    <div class="ss-dropdown-search">
      <input type="text" class="form-control form-control-sm "
             placeholder="{{ $searchPlaceholder ?: __('bs-blade-forms::components.custom-select.search-placeholder') }}"/>
    </div>

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
      {{ __('bs-blade-forms::components.custom-select.no-results') }}
    </div>

  </div>
</div>
