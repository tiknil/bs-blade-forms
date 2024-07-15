<label for="{{ $name }}" {{ $attributes->merge(['class' => $checkbox ? config('bs-blade-forms.checkbox_label_class') : config('bs-blade-forms.label_class')]) }}>
  @if ($slot->hasActualContent())
    {{ $slot }}
  @else
    &nbsp;
  @endif
</label>
