<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class MultiSelect extends BaseFormInput
{
    public array $value;

    public string $fieldName;

    public function __construct(
        public string $name,
        ?array $value = null,
        public bool $required = false,
        public string $placeholder = '',

        public ?string $label = null,
        public ?string $icon = null,

        public array $options = [],
        public string $searchPlaceholder = '',

    ) {

        if (str_ends_with($this->name, '[]')) {
            $this->name = Str::replaceEnd('[]', '', $this->name);
        }

        $this->fieldName = "{$this->name}[]";

        $this->value = $value ?: [];

        parent::__construct();

    }

    public function render(): View
    {
        return view('bs-blade-forms::multi-select');
    }
}
