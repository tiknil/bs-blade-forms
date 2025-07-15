<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Tiknil\BsBladeForms\Components\Traits\WithOptions;
use Tiknil\BsBladeForms\Components\Traits\WithStringValue;

class Autocomplete extends BaseFormInput
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
        public string $searchPlaceholder = '',

        public ?string $fetchUrl = null,
        public bool $allowCustomValues = false,
    ) {
        $this->setOptions($options);
        $this->value = $this->parse($value);

        parent::__construct($this->name);
    }

    public function load(mixed $value, bool $override): void
    {
        if (!$override && $this->value !== null) {
            return;
        }

        $this->value = $this->parse($value);
    }

    public function render(): View
    {
        return view('bs-blade-forms::autocomplete');
    }
}
