<?php

use Illuminate\Support\Facades\Session;
use Tiknil\BsBladeForms\Components\Textarea;
use Tiknil\BsBladeForms\Tests\Data\TestModel;

describe('Resolve initial value', function () {

    it('uses default value', function () {

        $textarea = new Textarea(name: 'notes');

        expect($textarea->value)->toBeNull();
    });

    it('reads old textarea', function () {

        Session::flash('_old_input', [
            'notes' => 'Lorem Ipsum',
        ]);

        $textarea = new Textarea(name: 'notes');

        expect($textarea->value)->toBe('Lorem Ipsum');

    });

    it('reads model field', function () {

        $testModel = new TestModel(['notes' => 'Lorem Ipsum']);
        bindModel($testModel);

        $textarea = new Textarea(name: 'notes', value: 'Ipsum Lorem');

        expect($textarea->value)->toBe('Lorem Ipsum');
    });

    it('gives priority to old', function () {

        $testModel = new TestModel(['notes' => 'Lorem Ipsum model']);
        bindModel($testModel);

        Session::flash('_old_input', [
            'notes' => 'Lorem Ipsum old',
        ]);

        $textarea = new Textarea(name: 'notes', value: 'Lorem Ipsum');

        expect($textarea->value)->toBe('Lorem Ipsum old');
    });

});

describe('Renders correctly', function () {
    it('renders default', function () {

        $view = $this->blade('<x-bs::textarea name="notes" value="test@example.com" />');

        expect($view)->assertDontSee('label')
            ->and($view)->toMatchSnapshot();

    });

    it('renders with full options selected', function () {

        $view = $this->blade(
            '<x-bs::textarea type="notes" name="notes" value="Lorem Ipsum" label="Notes"  required />',
        );

        expect($view)->toMatchSnapshot();

    });

    it('renders old value when old is present', function () {

        Session::flash('_old_input', [
            'notes' => 'Lorem Ipsum',
        ]);

        $view = $this->blade(
            '<x-bs::textarea name="notes" />',
        );

        expect($view)->assertSee('Lorem Ipsum', false);
    });

    it('renders model field when model is present', function () {

        $testModel = new TestModel(['notes' => 'Lorem Ipsum']);
        bindModel($testModel);

        $view = $this->blade(
            '<x-bs::textarea name="notes" />'
        );

        expect($view)->assertSee('Lorem Ipsum', false);
    });

});
