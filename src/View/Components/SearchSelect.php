<?php

namespace Tiknil\BsBladeForms\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchSelect extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $name,
        public array $options = [],
        public string|int|null $value = null,
        public bool $required = false,
        public bool $allowClear = false,
        public string $emptyValue = '',
        public string $placeholder = '',
        public string $searchPlaceholder = '',
        public string $id = '',
    ) {
    }

    public function render(): View
    {
        return view('bs-blade-forms::components.search-select');
    }
}
