<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Tiknil\BsBladeForms\Components\Traits\WithStringValue;

class Input extends BaseFormInput
{
    use WithStringValue;

    public ?string $value = null;

    public function __construct(
        string $name,
        mixed $value = null,

        public ?string $label = null,
        public ?string $icon = null,

        public string $type = 'text'

    ) {
        $this->value = $this->parse($value);
        parent::__construct($name);
    }

    public function load(mixed $value): void
    {
        $this->value = $this->parse($value);
    }

    public function render(): View
    {
        return view('bs-blade-forms::input');
    }
}
