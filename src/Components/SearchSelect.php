<?php

namespace Tiknil\BsBladeForms\Components;

use Barryvdh\Reflection\DocBlock\Type\Collection;
use Illuminate\Contracts\View\View;
use Tiknil\BsBladeForms\Components\Traits\WithOptions;
use Tiknil\BsBladeForms\Components\Traits\WithStringValue;

class SearchSelect extends BaseFormInput
{
    use WithOptions, WithStringValue;

    public ?string $value;

    public function __construct(
        public string $name,
        mixed $value = null,
        public bool $required = false,
        public string $placeholder = '',

        public ?string $label = null,
        public ?string $icon = null,

        array|Collection $options = [],

        public bool $allowClear = false,
        public string $emptyValue = '',
        public string $searchPlaceholder = ''
    ) {

        $this->setOptions($options);

        $this->value = $this->parseValue($value);

        parent::__construct($this->name);

    }

    public function loadValue(mixed $value): void
    {
        if ($this->value !== null) {
            return;
        }

        $this->value = $this->parseValue($value);
    }

    public function render(): View
    {
        return view('bs-blade-forms::search-select');
    }
}
