# Blade Form Components Library

![GitHub Actions](https://github.com/tiknil/bs-blade-forms/actions/workflows/main.yml/badge.svg)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/tiknil/bs-blade-forms.svg?style=flat-square)](https://packagist.org/packages/tiknil/bs-blade-forms)
[![Total Downloads](https://img.shields.io/packagist/dt/tiknil/bs-blade-forms.svg?style=flat-square)](https://packagist.org/packages/tiknil/bs-blade-forms)

Opinionated library designed to streamline the process of building forms in Laravel applications by leveraging Blade
components and Boostrap utilities.


- [Key Features](#key-features)
- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [Components](#components)
  - [SearchSelect](#searchselect)
  - [MultiSelect](#multiselect)
  - [Select](#select)
  - [Input](#input)
  - [Textarea](#textarea)
  - [Checkbox](#checkbox)
  - [Radio](#radio)


### Key Features

- **Reduced boilerplate**: Minimize repetitive code and simplify the form-building process
- **Advanced Select Components**: Utilize `SearchSelect` and `MultiSelect` for enhanced and complex selection input
  needs, providing a better user experience.
- **Automatic Old Input Handling**: Automatically manage [old input](https://laravel.com/docs/validation#repopulating-forms) based on the field name, ensuring form
  repopulation is seamless.
- - **Automatic Form model binding**: Automatically binds to a model and populate the form with the corrisponding field
- **Livewire Support**: Fully integrate with Livewire by forwarding tags (e.g., `wire:model`) to the underlying
  input/select
  elements.



### Installation

You can install the package via composer:

```bash
composer require tiknil/bs-blade-forms
```

JS/CSS assets should be automatically published alongside the default laravel libraries assets. Alternatively, publish
them using:

```bash
php artisan vendor:publish --tag=bs-blade-forms:assets
```

> [!NOTE]  
> Boostrap is not imported automatically by the library. We assume you are already using it on your page and it is
> already available

### Usage

The advanced select elements (SearchSelect / MultiSelect) requires some additional assets to be included. Add this
between your page `head` tag:

```php
<head>
    ...
    {{ BsBladeForms::assets() }}
</head>
```

In your blade templates, use the provided components:

```bladehtml

<x-bs::search-select label="Your country" name="country" :options="$countries" required/>

<x-bs::multi-select label="Pick your countries" name="countries" :options="$countries" required/>

<x-bs::input label="Full Name" name="name" :value="$user->name"/>
```

#### Examples

Go from:

```html
<form action="{{ route('users.edit', ['id' => $user->id]) }}" method="POST">
    @csrf
    @method('patch')
        
    <label for="name" class="form-label">{{ __('user.name') }}</label>
    <input type="text"
           class="form-control"
           id="name"
           name="name"
           value="{{ old('name', $user->name) }}"
    
    />
    
    <label for="role" class="form-label">{{ __('user.role') }}</label>
    <select name="role" id="role" class="form-select">
        <option value="">-- Select a role --</option>
        @foreach($roles as $key => $label)
            <option value="{{ $key }}"
                    @selected(old('role', $user->role) === $key)
            >
            {{ $label }}
            </option>
        @endforeach
    </select>
    
    <div class="form-check">
        <input type="checkbox"
               class="form-check-input" 
               value="1" 
               name="newsletter"
               id="newsletter" 
               @checked(old('newsletter', $user->newsletter) === '1') />
        <label class="form-check-label" for="newsletter">
            Subscribe to newsletter
        </label>
    </div>
</form>
```

To:

```bladehtml
<x-bs::form :model="$user" method="PATCH" action="{{ route('users.edit', ['id' => $user->id]) }}">
    <x-bs::input
        :label="__('user.name)"
        name="name"
    />

    <x-bs::select
        :label="__('user.role')"
        name="role"
        :options="$roles"
        empty-option="-- Select a role --"
    />

    <x-bs::checkbox
        name="newsletter"
        label="Subscribe to newsletter" 
    />
</x-bs::form>
```

## Components

#### Form

Renders a form, with optional modal binding.


```bladehtml

<x-bs::form :model="$model" method="PATCH" action="{{ route(...) }}">
...
</x-bs::form>
```

Automatically adds `@csrf` and `@method(...)` when required.

When a `model` is provided, `x-bs::` components will automatically use the model corresponding field as default value.

#### SearchSelect

Renders a single selection element with a research bar for filtering the options.

```bladehtml

<x-bs::search-select
    label="Your country"
    name="country"
    :value="$user->country"
    :options="$countries"
    icon="bi bi-map"
    required
    allow-clear
/>
```

> [!IMPORTANT]  
> Include `{{ BsBladeForms::assets() }}` in the page head for this component to work

| Attribute          | Type              | Description                                                                    |
|--------------------|-------------------|--------------------------------------------------------------------------------|
| name               | string            | *Required*. Name of the select element                                         |
| options            | array, Collection | The options to display on the select.                                          |
| fetch-url          | string            | An url to fetch for available options (to use with big data). The library will add a `q` querystring param with the searched string. Response should be a json in the `{ [value]: [label] }` format|
| value              | string, int       | The initial selected value                                                     |                                      |
| required           | bool              | Set the select element as required (form can't be submitted without selection) |                                      |
| placeholder        | string            | Element placeholder when no option is selected                                 |
| label              | string            | If present, renders a `Label` above the element                                |
| icon               | string            | If present, renders an `IconGroup` around the element                          |
| allow-clear        | bool              | Allows the user to clear the selected option                                   |
| empty-value        | string            | The value to submit when no option is selected                                 |
| search-placeholder | string            | The placeholder of the search input                                            |
| *                  |                   | Additional attributes will be forwarded to the underlying element.             |

#### MultiSelect

Renders a multiple selection element with a research bar for filtering the options.

```bladehtml

<x-bs::multi-select
    label="Which countries you visited?"
    name="countries"
    :value="['US', 'DE', 'IT']"
    :options="$countries"
    icon="bi bi-map"
    required
/>
```

> [!IMPORTANT]  
> Include `{{ BsBladeForms::assets() }}` in the page head for this component to work

| Attribute          | Type              | Description                                                                    |
|--------------------|-------------------|--------------------------------------------------------------------------------|
| name               | string            | *Required*. Name of the select element                                         |
| options            | array, Collection | The options to display on the select.                                          |
| fetch-url          | string            | An url to fetch for available options (to use with big data). The library will add a `q` querystring param with the searched string. Response should be a json in the `{ [value]: [label] }` format|
| value              | array             | The initial selected values                                                    |
| required           | bool              | Set the select element as required (form can't be submitted without selection) |
| placeholder        | string            | Element placeholder when no option is selected                                 |
| label              | string            | If present, renders a `Label` above the element                                |
| icon               | string            | If present, renders an `IconGroup` around the element                          |
| search-placeholder | string            | The placeholder of the search input                                            |
| select-buttons     | bool              | Whether or not to show "select all" and "unselect all" buttons (default true)  |
| *                  |                   | Additional attributes will be forwarded to the underlying element.             |

#### Select

```bladehtml

<x-bs::select
    label="Your country"
    name="country"
    :value="$user->country"
    :options="$countries"
    icon="bi bi-map"
    empty-option="No answer"
    required
/>
```

| Attribute    | Type              | Description                                                                             |
|--------------|-------------------|-----------------------------------------------------------------------------------------|
| name         | string            | *Required*. Name of the select element                                                  |
| options      | array, Collection | The options to display on the select.                                                   |
| value        | string            | The initial selected values                                                             |                                      |
| required     | bool              | Set the select element as required (form can't be submitted without selection)          |                                      |
| label        | string            | If present, renders a `Label` above the element                                         |
| icon         | string            | If present, renders an `IconGroup` around the element                                   |
| empty-option | string            | When present, an additional option with empty string as value is added with this label. |
| *            |                   | Additional attributes will be forwarded to the underlying element.                      |


#### Input

```bladehtml

<x-bs::input
    type="email"
    label="Your email"
    name="email"
    :value="$user->email"
    icon="bi bi-envelop"
    required 
/>
```

| Attribute | Type   | Description                                                        |
|-----------|--------|--------------------------------------------------------------------|
| name      | string | *Required*. Name of the input element                              |
| value     | string | The initial value                                                  |       
| label     | string | If present, renders a `Label` above the element                    |
| icon      | string | If present, renders an `IconGroup` around the element              |
| type      | string | Type of the input (`text` by default)                              |
| *         |        | Additional attributes will be forwarded to the underlying element. |


#### Textarea

```bladehtml

<x-bs::textarea
    label="Notes"
    name="notes"
    :value="$user->notes"
    required 
/>
```

| Attribute | Type   | Description                                                        |
|-----------|--------|--------------------------------------------------------------------|
| name      | string | *Required*. Name of the textarea element                           |
| value     | string | The initial value                                                  |                                      |
| label     | string | If present, renders a `Label` above the element                    |
| *         |        | Additional attributes will be forwarded to the underlying element. |


#### Checkbox

```bladehtml

<x-bs::checkbox
    label="Enable"
    name="enabled"
    @checked($user->enabled)
/>
```

> [!NOTE]  
> When the form is submitted, a parameter is submitted even when the checkbox is not checked! 
> The parameter submitted has value `1` when the checkbox is checked, `0` otherwise


| Attribute        | Type   | Description                                                            |
|------------------|--------|------------------------------------------------------------------------|
| name             | string | *Required*. Name of the element                                  |
| label            | string | If present, renders a `Label` aside the input checkbox                 |
| checked          | bool   | Initial checked value (default `false`)                                |                                      
| value            | string | The value submitted when the checkbox is checked (default `1`)         |                                      
| false-value      | string | The value submitted when the checkbox is not checked (default `0`)     |                                      
| send-false-value | bool   | Send the false value when  the checkbox is not checked (default `true`) |                                      
| *                |        | Additional attributes will be forwarded to the underlying element.     |


#### Radio

```bladehtml

<x-bs::radio
    label="Contact choice"
    name="contact_choice"
    value="email"
    @checked($user->contact_choice === 'email')
/>
```

| Attribute        | Type   | Description                                                             |
|------------------|--------|-------------------------------------------------------------------------|
| name             | string | *Required*. Name of the element                                         |
| label            | string | If present, renders a `Label` aside the input radio                     |
| checked          | bool   | Initial checked value (default `false`)                                 |                                      
| value            | string | The value submitted when the checkbox is checked                        |                                      
| *                |        | Additional attributes will be forwarded to the underlying element.      |


#### Label

All form components automatically include the `Label` component when the `label` attribute is present, but it can be used independently:


```bladehtml

<x-bs::label name="email"> 
    User email
</x-bs::label>
```


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please
email [balduzzi.giorgio@tiknil.com](mailto:balduzzi.giorgio@tiknil.com) instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

----

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com), following the
[laravelpackage.com](https://laravelpackage.com) documentation.
