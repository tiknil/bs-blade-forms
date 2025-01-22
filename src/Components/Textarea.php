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
        return view('bs-blade-forms::textarea');
    }
}
