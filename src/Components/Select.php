<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Tiknil\BsBladeForms\Components\Traits\WithOptions;
use Tiknil\BsBladeForms\Components\Traits\WithStringValue;

class Select extends BaseFormInput
{
    use WithOptions, WithStringValue;

    public ?string $value;

    public function __construct(
        public string $name,
        mixed $value = null,

        public ?string $label = null,
        public ?string $icon = null,

        array|Collection $options = [],
        public ?string $emptyOption = null,

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
        return view('bs-blade-forms::select');
    }
}
