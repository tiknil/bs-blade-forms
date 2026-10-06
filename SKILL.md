---
name: bs-blade-forms
description: Guida all'uso della libreria tiknil/bs-blade-forms per costruire form Bootstrap in Laravel tramite componenti blade
---

## Installazione

```bash
composer require tiknil/bs-blade-forms
```

Il service provider è auto-discoverato da Laravel. Niente registrazione manuale.

## Pubblicazione asset (solo per componenti avanzati)

Se usi `SearchSelect`, `MultiSelect` o `Autocomplete`, includi gli asset JS/CSS nell'`<head>` della pagina:

```blade
<head>
    ...
    {{ BsBladeForms::assets() }}
</head>
```

Gli asset vengono pubblicati automaticamente con `php artisan vendor:publish`. In caso contrario:

```bash
php artisan vendor:publish --tag=bs-blade-forms:assets
```

> Bootstrap non è incluso: il tuo progetto deve già averlo.

## Uso base

Tutti i componenti usano il namespace `x-bs::`.

### Form (model binding facoltativo)

Puoi usare i componenti anche senza `<x-bs::form>`, passando i valori esplicitamente:

```blade
<x-bs::input name="name" label="Nome" :value="$user->name" />
```

Oppure in contesto Livewire con `wire:model`:

```blade
<x-bs::input name="name" label="Nome" wire:model="name" />
<x-bs::select name="role" label="Ruolo" :options="$roles" wire:model="role" />
```

Con `<x-bs::form :model="...">` invece i valori si legano automaticamente:

```blade
<x-bs::form :model="$user" method="PATCH" action="{{ route('users.update', $user) }}">
    <x-bs::input name="name" label="Nome" />
    <x-bs::input type="email" name="email" label="Email" />
    <x-bs::select name="role" label="Ruolo" :options="$roles" empty-option="-- Seleziona --" />
    <x-bs::checkbox name="newsletter" label="Newsletter" />
    <x-bs::button>Salva</x-bs::button>
</x-bs::form>
```

Cosa fa automaticamente:

- Aggiunge `@csrf` e `@method('PATCH')`
- Legge i valori dal model (es. `$user->name`) e li precompila
- Su validazione fallita, ripesca i valori da `old()`

> `wire:model` ha la priorità sul model binding di `<x-bs::form>`. Puoi usare entrambi, ma `wire:model` vince.

### Componenti disponibili

| Componente   | Tag                     | Descrizione                                          |
| ------------ | ----------------------- | ---------------------------------------------------- |
| Form         | `<x-bs::form>`          | Contenitore con CSRF, method spoofing, model binding |
| Input        | `<x-bs::input>`         | Input testuale. `type` default `text`                |
| Textarea     | `<x-bs::textarea>`      | Area di testo                                        |
| Select       | `<x-bs::select>`        | Select nativo. `empty-option` per opzione vuota      |
| SearchSelect | `<x-bs::search-select>` | Select con ricerca. Richiede asset JS                |
| Autocomplete | `<x-bs::autocomplete>`  | Input con suggerimenti. Richiede asset JS            |
| MultiSelect  | `<x-bs::multi-select>`  | Select multipla con ricerca. Richiede asset JS       |
| Checkbox     | `<x-bs::checkbox>`      | Checkbox. Invia sempre un valore (1/0)               |
| Radio        | `<x-bs::radio>`         | Radio button                                         |
| Label        | `<x-bs::label>`         | Etichetta standalone                                 |

### Attributi trasversali

Tutti i componenti supportano:

- `label` — mostra una label sopra il campo
- `icon` — mostra un'icona Bootstrap Icons prima del campo (es. `bi bi-envelope`)
- `icon-text` — mostra un testo libero nell'`input-group-text` al posto dell'icona (es. simboli, emoji, valuta)
- Attributi HTML arbitrari (es. `required`, `disabled`, `wire:model`) — vengono inoltrati all'elemento sottostante

```blade
<x-bs::input name="price" label="Prezzo" icon-text="€" />
<x-bs::input name="phone" label="Telefono" icon-text="📞" />
```

### Vecchio input e model binding

L'ordine di risoluzione del valore è:

1. `old('nome_campo')` — se presente (dopo validazione fallita)
2. Valore esplicito (`:value="..."`) — se passato al componente
3. Valore dal model — se racchiuso in `<x-bs::form :model="...">`

## Opzioni

`options` accetta `array` o `Collection`. Formato: `['value' => 'Label', ...]`.

```blade
<x-bs::select name="role" :options="[
    1 => 'Amministratore',
    2 => 'Utente',
]" />
```

## Fetch remoto (SearchSelect, MultiSelect, Autocomplete)

Per dataset grandi, usa `fetch-url` invece di passare `options`:

```blade
<x-bs::search-select
    name="country"
    label="Paese"
    fetch-url="/api/countries"
/>
```

La libreria chiama `{fetch-url}?q={testo}` e si aspetta una risposta JSON nel formato `{ "value": "label", ... }`.

## Livewire

I componenti avanzati (SearchSelect, MultiSelect, Autocomplete) rilevano automaticamente Livewire. Basta aggiungere `wire:model`:

```blade
<x-bs::search-select
    name="country"
    label="Paese"
    :options="$countries"
    wire:model="country"
/>
```

## Pubblicare i view per personalizzazione

```bash
php artisan vendor:publish --tag=bs-blade-forms:views
```

I blade finiscono in `resources/views/vendor/bs-blade-forms/`.
