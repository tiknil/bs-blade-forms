<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Tiknil\BsBladeForms\Components\Traits\WithStringValue;

class Input extends BaseFormInput
{
    use WithStringValue;

    public function __construct(
        public string $name,
        mixed $value = null,

        public ?string $label = null,
        public ?string $icon = null,

        public string $type = 'text'

    ) {

        $this->setValue($value);

        parent::__construct();

    }

    public function render(): View
    {
        return view('bs-blade-forms::input');
    }
}
