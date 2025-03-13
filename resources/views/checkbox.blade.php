<div class="form-check">
  @if($sendFalseValue)
    {{-- Trick per fare in modo che, anche quando il checkbox NON è selezionato, venga comunque inviato il parametro
    nella richiesta con il valore 0 (false). Di default un checkbox non selezionato non manda nulla al server --}}
    <input type="hidden" name="{{ $name }}" value="{{ $falseValue }}" />
  @endif

  <input type="checkbox"
         value="{{ $value }}"
         name="{{ $name }}"
         id="{{ $name }}"
        @checked($checked)
    {{ $attributes->merge(['class' => 'form-check-input']) }} />

  <x-bs::label name="{{ $name }}" checkbox>
    {{ $label }}
  </x-bs::label>
</div>
