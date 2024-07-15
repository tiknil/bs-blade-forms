<?php

namespace Tiknil\BsBladeForms\Components\Traits;

use BackedEnum;
use UnitEnum;

trait WithStringValue
{
    public ?string $value;

    public function setValue(mixed $value): void
    {
        if (is_a($value, BackedEnum::class)) {
            $this->value = $value->value;
        } elseif (is_a($value, UnitEnum::class)) {
            $this->value = $value->name;
        } else {
            $this->value = $value === null ? null : strval($value);
        }

    }
}
