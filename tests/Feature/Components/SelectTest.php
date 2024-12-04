<?php

use Illuminate\Support\Facades\Session;
use Tiknil\BsBladeForms\Components\Select;
use Tiknil\BsBladeForms\Tests\Data\TestModel;

include_once 'tests/Data/TestOptions.php';

describe('Resolve initial value', function () {

    it('uses default value', function () {

        $input = new Select(name: 'country', options: TEST_OPTIONS);

        expect($input->value)->toBeNull();
    });

    it('supports collection options', function () {

        $input = new Select(name: 'country', options: collect(TEST_OPTIONS));

        expect($input->options)->toEqual(TEST_OPTIONS);

    });

    it('reads old input', function () {

        Session::flash('_old_input', [
            'country' => 'it',
        ]);

        $input = new Select(name: 'country', options: TEST_OPTIONS);

        expect($input->value)->toBe('it');

    });

    it('reads model field', function () {

        $testModel = new TestModel(['country' => 'it']);
        bindModel($testModel);

        $input = new Select(name: 'country', value: 'de', options: TEST_OPTIONS);

        expect($input->value)->toBe('it');
    });

    it('gives priority to old', function () {

        $testModel = new TestModel(['country' => 'us']);
        bindModel($testModel);

        Session::flash('_old_input', [
            'country' => 'it',
        ]);

        $input = new Select(name: 'country', value: 'de', options: TEST_OPTIONS);

        expect($input->value)->toBe('it');
    });

});

describe('Renders correctly', function () {
    it('renders default', function () {

        $view = $this->blade(
            '<x-bs::select name="country" :options="$options" />',
            ['options' => TEST_OPTIONS]
        );

        expect($view)->assertDontSee('<label', false)
            ->and($view)->toMatchSnapshot();

    });

    it('renders with full options selected', function () {

        $view = $this->blade(
            '<x-bs::select name="country" value="it" :options="$options" label="Country" icon="bi bi-map" empty-option="-- Select --" />',
            ['options' => TEST_OPTIONS]
        );

        expect($view)
            ->assertSee('<label', false)
            ->assertSee('<option value="it" selected', false)
            ->and($view)->toMatchSnapshot();
    });

    it('renders old value when old is present', function () {

        Session::flash('_old_input', [
            'country' => 'us',
        ]);

        $view = $this->blade(
            '<x-bs::select name="country" :options="$options" />',
            ['options' => TEST_OPTIONS]
        );

        expect($view)->assertSee('<option value="us" selected', false);
    });

    it('renders model field when model is present', function () {

        $testModel = new TestModel(['country' => 'de']);
        bindModel($testModel);

        $view = $this->blade(
            '<x-bs::select name="country" :options="$options" />',
            ['options' => TEST_OPTIONS]
        );

        expect($view)->assertSee('<option value="de" selected', false);
    });

});
