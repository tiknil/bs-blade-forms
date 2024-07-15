<?php

namespace Tiknil\BsBladeForms\Components;

use BackedEnum;
use Illuminate\Contracts\View\View;
use UnitEnum;

class SearchSelect extends BaseFormInput
{
    public string|int|null $value = null;

    //public array $options;

    public function __construct(
        public string $name,
        mixed $value = null,
        public bool $required = false,
        public string $placeholder = '',

        public ?string $label = null,
        public ?string $icon = null,

        public array $options = [],

        public bool $allowClear = false,
        public string $emptyValue = '',
        public string $searchPlaceholder = ''
    ) {

        if (is_a($value, BackedEnum::class)) {
            $this->value = $value->value;
        } elseif (is_a($value, UnitEnum::class)) {
            $this->value = $value->name;
        } else {
            $this->value = $value;
        }

        parent::__construct();
    }

    public function render(): View
    {
        return view('bs-blade-forms::search-select');
    }
}
