<label for="{{ $name }}" {{ $attributes->merge(['class' => config('bs-blade-forms.label_class')]) }}>
  @if ($slot->hasActualContent())
    {{ $slot }}
  @else
    &nbsp;
  @endif
</label>
