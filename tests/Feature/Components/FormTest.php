<?php

use Tiknil\BsBladeForms\Components\Form;
use Tiknil\BsBladeForms\Tests\Data\TestModel;

describe('Binds model', function () {
    it('binds and unbinds model', function () {
        bindModel(null);

        $model = new TestModel();

        $form = new Form(model: $model);

        expect(Form::$model)->toBe($model);

        $form->endModel();

        expect(Form::$model)->toBeNull();

    });
});

describe('Renders correctly', function () {
    it('renders default', function () {

        $view = $this->blade('<x-bs::form method="patch" action="https://url.com">CONTENT</x-bs::form>');

        expect($view)->toMatchSnapshot();

    });
});
