<div {{ $attributes->except(['icon-class', 'icon-text']) }}>
  @if($iconClass || $iconText)
  <div class="input-group">
    <span class="input-group-text">
      @if($iconClass)
        <i class="{{ $iconClass }}"></i>
      @endif
      {{ $iconText }}
    </span>

    {{ $slot }}
  </div>
@else
  {{ $slot }}
@endif
</div>

