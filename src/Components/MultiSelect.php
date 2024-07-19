<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tiknil\BsBladeForms\Components\Traits\WithOptions;

class MultiSelect extends BaseFormInput
{
    use WithOptions;

    public array $value = [];

    public string $fieldName;

    public function __construct(
        string $name,
        Collection|array|null $value = null,
        public bool $required = false,
        public string $placeholder = '',

        public ?string $label = null,
        public ?string $icon = null,

        array|Collection $options = [],
        public string $searchPlaceholder = '',

    ) {

        $this->setOptions($options);

        if (str_ends_with($name, '[]')) {
            $name = Str::replaceEnd('[]', '', $name);
        }

        $this->fieldName = "{$name}[]";

        $this->loadValue($value);

        parent::__construct($name);

    }

    public function loadValue(mixed $value): void
    {
        if ($this->value) {
            return;
        }

        if (is_a($value, Collection::class)) {
            $value = $value->toArray();
        }
        $this->value = $value ?: [];
    }

    public function render(): View
    {
        return view('bs-blade-forms::multi-select');
    }
}
