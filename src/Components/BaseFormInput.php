<?php

namespace Tiknil\BsBladeForms\Components;

abstract class BaseFormInput extends BaseComponent
{
    protected bool $disableOld = false;

    public function __construct()
    {
        $this->useOld();
    }

    public function useOld()
    {
        if (!$this->disableOld && property_exists($this, 'name') && property_exists($this, 'value')) {
            $this->value = old($this->name, $this->value);
        }
    }
}
