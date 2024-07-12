<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

abstract class BaseComponent extends Component
{
    /**
     * Extract attributes for a subcomponent, such as all label attributes prefixed with label-
     */
    public function extractSubAttributes(string $prefix): ComponentAttributeBag
    {
        if (!str_ends_with($prefix, '-')) {
            $prefix = "{$prefix}-";
        }

        $prefixAttributes = $this->attributes->whereStartsWith($prefix)->all();
        $newAttributes = [];

        $len = strlen($prefix);
        foreach ($prefixAttributes as $key => $val) {
            $newKey = substr($key, $len);
            $newAttributes[$newKey] = $val;
        }

        $subBag = new ComponentAttributeBag();
        $subBag->setAttributes($newAttributes);

        $this->except = array_merge($this->except, [array_keys($prefixAttributes)]);

        return $subBag;
    }
}
