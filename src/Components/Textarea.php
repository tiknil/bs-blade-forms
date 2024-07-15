<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Tiknil\BsBladeForms\Components\Traits\WithStringValue;

class Textarea extends BaseFormInput
{
    use WithStringValue;

    public function __construct(
        public string $name,
        mixed $value = null,

        public ?string $label = null,

        public bool $encodeContent = true

    ) {

        $this->setValue($value);

        parent::__construct();

    }

    public function render(): View
    {
        return view('bs-blade-forms::textarea');
    }
}
