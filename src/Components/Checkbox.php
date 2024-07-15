<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;

class Checkbox extends BaseFormInput
{
    public bool $value;

    public function __construct(
        public string $name,
        mixed $value = false,
        public ?string $label = null,

        public bool $sendFalseValue = true,
    ) {

        $this->value = boolval($value);

        parent::__construct();
    }

    public function render(): View
    {
        return view('bs-blade-forms::checkbox');
    }
}
