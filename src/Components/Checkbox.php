<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Tiknil\BsBladeForms\Components\Traits\WithStringValue;

class Checkbox extends BaseFormInput
{
    use WithStringValue;

    public function __construct(
        public string $name,
        string $value = '1',
        public string $falseValue = '0',
        public ?string $label = null,

        public bool $checked = false,

        public bool $sendFalseValue = true,
    ) {

        $this->setValue($value);

        parent::__construct();
    }

    /**
     * Standard old behavior doesn't work an checks, because `value` is the string to submit, not the initial data
     *
     * @override
     */
    public function useOld(): void
    {
        $this->checked = boolval(old($this->name, $this->checked));
    }

    public function render(): View
    {
        return view('bs-blade-forms::checkbox');
    }
}
