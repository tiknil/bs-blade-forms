# Changelog

All notable changes to `bs-blade-forms` will be documented in this file

## 0.6.0 - 2025-04-04

- feat: new Autocomplete component

## 0.5.1 - 2025-04-04

- fix: mouseover handler did not work on fetched options

## 0.5.0 - 2025-04-04

- feat: support for option fetching from url in search-select and multi-select

## 0.4.1 - 2025-03-13

- fix: solved livewire rendering issue on searchselect and multiselect

## 0.4.0- 2025-03-10

- Bump dependencies for Laravel 12

## 0.3.4 - 2025-02-25

- Added additional wire:key to select options because of livewire rendering issues

## 0.3.3 - 2025-01-28

- Added wire:key to complex select wrappers

## 0.3.2 - 2025-01-22

- Direct value definition now takes precedence over model binding.

## 0.3.1 - 2024-12-05

- Improved performance on SearchSelect and MultiSelect with thousands of options

## 0.3.0 - 2024-12-04

- Values from `Session::old()` storage now take precedence over explicitly set values.

On v0.2.x, old session flash data was discarded when a value was explicitly set on the component

## 0.2.2 - 2024-08-07

- Fix: Nested array data was not resolved correctly on old inputFix: nested array data was not resolved correctly on old
  input

## 0.2.1 - 2024-07-29

- Fix: Safari always displayed an empty dropdown for search and multi select

## 0.2.0 - 2024-07-22

- Added `Form` element with automatic binding

## 0.1.0 - 2024-07-15

- Initial beta release
