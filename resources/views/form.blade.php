<form method="{{ $method === 'GET' ? 'GET' : 'POST' }}" {{ $attributes }}>
  @csrf
  @method($method)

  {{ $slot }}
  @php $endModel() @endphp
</form>
