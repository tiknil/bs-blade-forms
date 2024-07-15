<?php

namespace Tiknil\BsBladeForms\Components;

use Barryvdh\Reflection\DocBlock\Type\Collection;
use Illuminate\Contracts\View\View;
use Tiknil\BsBladeForms\Components\Traits\WithOptions;
use Tiknil\BsBladeForms\Components\Traits\WithStringValue;

class SearchSelect extends BaseFormInput
{
    use WithOptions, WithStringValue;

    //public array $options;

    public function __construct(
        public string $name,
        mixed $value = null,
        public bool $required = false,
        public string $placeholder = '',

        public ?string $label = null,
        public ?string $icon = null,

        array|Collection $options = [],

        public bool $allowClear = false,
        public string $emptyValue = '',
        public string $searchPlaceholder = ''
    ) {

        $this->setOptions($options);

        $this->setValue($value);

        parent::__construct();
    }

    public function render(): View
    {
        return view('bs-blade-forms::search-select');
    }
}