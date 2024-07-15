<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Tiknil\BsBladeForms\Components\Traits\WithStringValue;

class Radio extends BaseFormInput
{
    use WithStringValue;

    public function __construct(
        public string $name,
        mixed $value = false,
        public ?string $label = null,

        public bool $checked = false,
    ) {

        $this->setValue($value);

        parent::__construct();
    }

    /**
     * Standard old behavior doesn't work an radios, because `value` is the string to submit, not the initial data
     *
     * @override
     */
    public function useOld(): void
    {
        $old = old($this->name, null);

        if ($old !== null) {
            $this->checked = $old === $this->value;
        }
    }

    public function render(): View
    {
        return view('bs-blade-forms::radio');
    }
}
