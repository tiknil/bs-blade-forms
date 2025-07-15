# GitHub Copilot Instructions for bs-blade-forms

This is an opinionated library designed to streamline the process of building forms in Laravel applications by leveraging Blade components and Bootstrap utilities.

## Project Overview

bs-blade-forms is a Laravel package that provides a suite of form components to:

- Reduce boilerplate code in form creation
- Provide advanced select components (SearchSelect, MultiSelect)
- Handle old input and model binding automatically
- Support Livewire integration

## Key Components

### Form Component

- Base component for wrapping form elements
- Handles CSRF and method spoofing automatically
- Supports model binding for automatic field population

### Input Types

1. **Input**

- Basic input field with customizable type
- Supports labels and icons
- Props: `name`, `value`, `label`, `icon`, `type`

2. **Select**

- Standard select dropdown
- Supports empty option
- Props: `name`, `options`, `value`, `label`, `icon`, `empty-option`

3. **SearchSelect**

- Enhanced select with search capability
- Can fetch options dynamically via URL
- Props: `name`, `options`, `fetch-url`, `value`, `allow-clear`

4. **Autocomplete**

- Input element with filtering of options
- Can fetch options dynamically via URL
- Props: `name`, `options`, `fetch-url`, `value`, `allow-clear`

5. **MultiSelect**

- Multiple selection with search
- Select/unselect all functionality
- Props: `name`, `options`, `value`, `select-buttons`

6. **Textarea**

- Multiline text input
- Props: `name`, `value`, `label`

7. **Checkbox**

- Boolean input
- Supports custom true/false values
- Props: `name`, `value`, `false-value`, `send-false-value`

8. **Radio**

- Single option selection from group
- Props: `name`, `value`, `checked`

## Special Features

### Auto-binding System

- Components auto-bind to model fields when wrapped in `<x-bs::form :model="$model">`
- Resolves values in priority order:
  1. Old input (from validation)
  2. Direct value prop
  3. Model field value

### Advanced Select Features

- Dynamic option loading via fetch-url
- Built-in search filtering
- Keyboard navigation support
- Livewire integration

### Bootstrap Integration

- Components use Bootstrap classes
- Support for Bootstrap icons
- Responsive design patterns

## Development Patterns

### Component Structure

- All form components extend `BaseFormInput`
- Use `WithStringValue` trait for string value handling
- Use `WithOptions` trait for option list handling

### Value Resolution

```php
// Priority order for field values:
1. Session old input
2. Direct value prop
3. Model field value
```

### Asset Handling

- JavaScript and CSS compiled with Vite
- Requires `{{ BsBladeForms::assets() }}` in page head
- Bootstrap expected to be available in project

## Common Development Tasks

### Adding New Form Components

1. Create component class extending `BaseFormInput`
2. Create blade view in `resources/views`
3. Register in service provider
4. Add any required JavaScript/CSS

### Modifying Existing Components

- Component logic in `src/Components/`
- Views in `resources/views/`
- JavaScript in `resources/js/components/`
- Styles in `resources/css/components/`

### Testing

- Use `InteractsWithViews` trait for testing components
- Test both rendering and data binding scenarios
- Validate Livewire integration where applicable

## Best Practices

1. **Component Props**

- Make `name` required for all form inputs
- Use nullable props for optional features
- Support attribute forwarding to underlying elements

2. **Value Handling**

- Always support old input binding
- Handle null/undefined values gracefully
- Support model binding by default

3. **UI/UX**

- Maintain Bootstrap class structure
- Support responsive layouts
- Keep consistent with Laravel conventions
