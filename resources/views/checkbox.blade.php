
<div class="form-check">
  @if($sendFalseValue)
    {{-- Trick per fare in modo che, anche quando il checkbox NON è selezionato, venga comunque inviato il parametro
    nella richiesta con il valore 0 (false). Di default un checkbox non selezionato non manda nulla al server --}}
    <input type="hidden" name="{{ $name }}" value="0" />
  @endif

  <input type="checkbox"
         value="1"
         name="{{ $name }}"
         id="{{ $name }}"
        @checked($value)
    {{ $attributes->merge(['class' => 'form-check-input']) }} />

  <x-bs::label name="{{ $name }}" checkbox>
    {{ $label }}
  </x-bs::label>
</div>
