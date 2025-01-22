<?php

namespace Tiknil\BsBladeForms\Components;

use Illuminate\Database\Eloquent\Model;
use Tiknil\BsBladeForms\Utils\ModelResolver;
use Tiknil\BsBladeForms\Utils\OldResolver;

abstract class BaseFormInput extends BaseComponent
{
    public string $name;

    public string $oldReference;

    public string $modelField;

    private ModelResolver $modelResolver;

    private OldResolver $oldResolver;

    public function __construct(string $name, ?string $oldReference = null, ?string $modelField = null)
    {
        $this->modelResolver = resolve(ModelResolver::class);
        $this->oldResolver = resolve(OldResolver::class);

        $this->name = $name;
        $this->oldReference = $oldReference ?: $name;
        $this->modelField = $modelField ?: $name;

        $valueToLoad = $this->loadFromOld();
        $override = true;

        if ($valueToLoad === null) {
            $valueToLoad = $this->loadFromModel();
            $override = false;
        }

        if ($valueToLoad !== null) {
            try {
                $this->load($valueToLoad, $override);

            } catch (\TypeError $e) {
                // Wrong variable type
            }
        }
    }

    /**
     * Method called when a value is found, either from the binded model or the `old()` input
     */
    abstract public function load(mixed $value, bool $override): void;

    protected function loadFromModel(): mixed
    {
        return $this->modelResolver->solve($this->name);
    }

    protected function loadFromOld(): mixed
    {
        return $this->oldResolver->solve($this->oldReference);
    }
}
