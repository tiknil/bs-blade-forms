@php($livewire = is_subclass_of(static::class, \Livewire\Component::class))

@if(!empty($label))
  <x-bs::label name="{{ $fieldName }}">
    {{ $label }}
  </x-bs::label>
@endif

<div class="ms-wrapper"
     @if($livewire) wire:ignore.self data-livewire wire:key="ms-wrapper-{{ $fieldName }}" @endif
     @if(!empty($id)) id="{{ $id }}" @endif @if(!empty($fetchUrl)) data-fetchurl="{{ $fetchUrl }}" @endif>

  {{--
    The actual select element, hidden in the UI but required for easier integration with the browser:
    * Sends data along with the form
    * Browser automatic validation for missing data
    * Livewire attributes work (e.g. wire:model)
  --}}
  <select name="{{ $fieldName }}"
          tabindex="-1"
          multiple
          id="{{ $fieldName }}"
          @if($livewire) wire:key="ms-{{ $fieldName }}" @endif
          @if($required) required @endif
          {{ $attributes->whereStartsWith('wire:model') }}
          class="ss-ghost-select">

    @if($slot->hasActualContent())
      {{ $slot }}
    @else
      @foreach($options as $key => $label)
        <option value="{{ $key }}" @selected(in_array($key, $value)) wire:key="{{$fieldName}}-{{ $key}}" >{{ $label }}</option>
      @endforeach
    @endif

  </select>

  <x-bs::icon-group icon-class="{{ $icon }}" wire:ignore>

    {{-- The element shown in the UI as if it's the actual select element --}}
    <div role="button"
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
  </x-bs::icon-group>

  {{-- Option dropdown --}}
  <div class="ss-dropdown hidden"
       @if($livewire) wire:ignore @endif
  >

    <div class="ss-dropdown-search">

      <input type="text" class="form-control form-control-sm"
             autocomplete="off"
             placeholder="{{ $searchPlaceholder ?: __('bs-blade-forms::components.custom-select.search-placeholder') }}"/>
      
      @if($selectButtons)
        <button class="btn btn-light ss-select-all"
                type="button"
                title="{{ __('bs-blade-forms::components.custom-select.select-all') }}">
          <i class="{{ config('bs-blade-forms.icons.select-all') }}"></i>
        </button>

        <button class="btn btn-light ss-unselect-all"
                type="button"
                title="{{ __('bs-blade-forms::components.custom-select.unselect-all') }}">
          <i class="{{ config('bs-blade-forms.icons.unselect-all') }}"></i>
        </button>
      @endif
    </div>

    {{-- This element is cloned by JS to render an option --}}
    <template class="ss-option-template">
      <div class="ss-option" data-key="">
        <span></span>

        <i class="{{ config('bs-blade-forms.icons.select') }} ss-check-icon"></i>
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
