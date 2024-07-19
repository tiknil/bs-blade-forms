<form method="{{ $method === 'GET' ? 'GET' : 'POST' }}" {{ $attributes }}>
  @csrf
  @method($method)

  {{ $slot }}


  {{ $endModel() }}
</form>
