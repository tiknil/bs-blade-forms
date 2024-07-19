<?php

namespace Tiknil\BsBladeForms\Utils;

use Illuminate\Database\Eloquent\Model;
use Tiknil\BsBladeForms\Components\Form;

class ModelResolver
{
    public function solve(string $name): mixed
    {
        if ($this->getModel() === null) {
            return null;
        }

        $nameFields = $this->splitNameFields($name);

        $result = $this->getModel();

        foreach ($nameFields as $nameField) {
            $result = $this->solveFor($nameField, $result);

            if ($result === null) {
                break;
            }
        }

        return $result;
    }

    protected function solveFor(string $name, mixed $data): mixed
    {
        if (is_array($data)) {
            return $data[$name] ?? null;
        }

        if (is_a($data, Model::class)) {

            if (in_array($name, $data->getHidden())) {
                return null;
            }

            return $data->getAttribute($name);
        }

        return $data->{$name};
    }

    protected function splitNameFields(string $name): mixed
    {
        $fields = [];

        // Identifica ogni gruppo di caratteri diverso da [, ] o ->
        preg_match_all('/( [^\[\]\(->)]+ )/x', $name, $fields);

        return $fields[0];
    }

    private function getModel(): mixed
    {
        return Form::$model;
    }
}
