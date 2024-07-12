<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;

class SearchSelect extends BaseFormInput
{
    public function __construct(
        public string $name,
        public string|int|null $value = null,
        public bool $required = false,
        public string $placeholder = '',

        public ?string $label = null,
        public ?string $icon = null,

        public array $options = [],
        public bool $allowClear = false,
        public string $emptyValue = '',
        public string $searchPlaceholder = ''
    ) {
        parent::__construct();
    }

    public function render(): View
    {
        return view('bs-blade-forms::search-select');
    }
}
