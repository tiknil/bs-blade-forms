<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Tiknil\BsBladeForms\Components\Traits\WithStringValue;

class Textarea extends BaseFormInput
{
    use WithStringValue;

    public ?string $value;

    public function __construct(
        public string $name,
        mixed $value = null,

        public ?string $label = null,

    ) {

        $this->value = $this->parseValue($value);

        parent::__construct($this->name);

    }

    public function loadValue(mixed $value): void
    {
        $this->value = $this->parseValue($value);
    }

    public function render(): View
    {
        return view('bs-blade-forms::textarea');
    }
}
