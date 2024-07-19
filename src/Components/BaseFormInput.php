<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Tiknil\BsBladeForms\Utils\ModelResolver;

abstract class BaseFormInput extends BaseComponent
{
    public string $name;

    public string $oldReference;

    public string $modelField;

    private ModelResolver $modelResolver;

    public function __construct(string $name, ?string $oldReference = null, ?string $modelField = null)
    {
        $this->modelResolver = resolve(ModelResolver::class);

        $this->name = $name;
        $this->oldReference = $oldReference ?: $name;
        $this->modelField = $modelField ?: $name;

        $valueToLoad = $this->loadFromOld();

        if ($valueToLoad === null) {
            $valueToLoad = $this->loadFromModel();
        }

        if ($valueToLoad !== null) {
            try {
                $this->loadValue($valueToLoad);

            } catch (\TypeError $e) {
                // Wrong variable type
            }
        }

    }

    /**
     * Method called when a value is found, either from the binded model or the `old()` input
     */
    abstract public function loadValue(mixed $value): void;

    protected function loadFromModel(): mixed
    {
        return $this->modelResolver->solve($this->name);
    }

    protected function loadFromOld(): mixed
    {
        return Session::getOldInput($this->oldReference, null);
    }
}
