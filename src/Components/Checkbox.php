<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Tiknil\BsBladeForms\Utils\EnumConverter;

class Checkbox extends BaseFormInput
{
    public mixed $value;

    public mixed $falseValue;

    public function __construct(
        string $name,
        mixed $value = '1',
        mixed $falseValue = '0',
        public ?string $label = null,

        public ?bool $checked = null,

        public bool $sendFalseValue = true,
    ) {

        $this->value = EnumConverter::enumToValue($value);
        $this->falseValue = EnumConverter::enumToValue($falseValue);

        parent::__construct($name);
    }

    public function loadValue(mixed $value): void
    {
        $this->checked = boolval($value);
    }

    public function render(): View
    {
        return view('bs-blade-forms::checkbox');
    }
}
