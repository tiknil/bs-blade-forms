@php($livewire = is_subclass_of(static::class, \Livewire\Component::class))


<div class="ss-wrapper"
     @if($livewire) wire:ignore.self data-livewire @endif
     @if(!empty($id)) id="{{ $id }}" @endif>

  {{--
    L'effettivo elemento select, nascosto dalla UI ma necessario per:
    - Inviare i dati se il search-select è incluso nel form
    - In caso di required e valore mancante, funziona il warning automatico del browser
    - Funzionano in automatico le connessioni di livewire del select (es. wire:model)
  --}}
  <select name="{{ $name }}"
          @if($livewire) wire:key="ss-{{ $name }}" @endif
          @if($required) required @endif
          {{ $attributes->filter(fn ($value, $key) => str_starts_with($key, 'wire:model')) }}
          class="ss-ghost-select">

    <option @selected(($value ?? $emptyValue) == $emptyValue) value="{{ $emptyValue }}"></option>
    @foreach($options as $key => $label)
      <option value="{{ $key }}" @selected($value == $key)>{{ $label }}</option>
    @endforeach

  </select>

  {{--
    L'elemento che viene visualizzato nella UI come se fosse un select
  --}}
  <div role="button"
       @if($livewire) wire:ignore @endif
       aria-label="{{ $placeholder }}"
    {{ $attributes->filter(fn ($value, $key) => !str_starts_with($key, 'wire:model'))->merge(['class' => 'form-select ss-box']) }}
  >
    <div class="text-muted ss-placeholder">@if(empty($placeholder))
        &nbsp;
      @else
        {{ $placeholder }}
      @endif</div>
    <div class="ss-value-label" style="display:none;"></div>
  </div>

  {{-- Il dropdown di selezione delle opzioni --}}
  <div class="ss-dropdown hidden"
       @if($livewire) wire:ignore @endif
  >

    <div class="ss-dropdown-search">
      <input type="text" class="form-control form-control-sm "
             placeholder="{{ $searchPlaceholder ?: __('components.search-select.search-placeholder') }}"/>
    </div>

    {{-- L'elemento che viene clonato tramite JS per popolare la lista di opzioni --}}
    <template class="ss-option-template">
      <div class="ss-option" data-key="">
        <span></span>
        @if($allowClear)
          <i class="bi bi-x-lg ss-remove-icon"></i>
        @endif
      </div>
    </template>

    {{-- Le opzioni create tramite JS vengono inserite qui --}}
    <div class="ss-options">

    </div>

    <div class="text-muted p-2 empty-results">
      {{ __('components.search-select.no-results') }}
    </div>

  </div>
</div>
