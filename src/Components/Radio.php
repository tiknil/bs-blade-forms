<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Tiknil\BsBladeForms\Utils\EnumConverter;

class Radio extends BaseFormInput
{
    public function __construct(
        string $name,
        public mixed $value,
        public ?string $label = null,

        public ?bool $checked = null,
    ) {

        $this->value = EnumConverter::enumToValue($value);

        parent::__construct($name);
    }

    public function loadValue(mixed $value): void
    {
        $value = EnumConverter::enumToValue($value);

        $this->checked = $value === $this->value;
    }

    public function render(): View
    {
        return view('bs-blade-forms::radio');
    }
}
