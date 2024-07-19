<?php

use Illuminate\Support\Facades\Session;
use Tiknil\BsBladeForms\Components\MultiSelect;
use Tiknil\BsBladeForms\Tests\Data\TestModel;

include_once 'tests/Data/TestOptions.php';

describe('Resolve initial value', function () {

    it('uses default value', function () {

        $input = new MultiSelect(name: 'countries', options: TEST_OPTIONS);

        expect($input->value)->toBe([]);
    });

    it('supports collection options', function () {

        $input = new MultiSelect(name: 'countries', options: collect(TEST_OPTIONS));

        expect($input->options)->toEqual(TEST_OPTIONS);

    });

    it('reads old input', function () {

        Session::flash('_old_input', [
            'countries' => ['it'],
        ]);

        $input = new MultiSelect(name: 'countries', options: TEST_OPTIONS);

        expect($input->value)->toBe(['it']);

    });

    it('reads model field', function () {

        $testModel = new TestModel(['countries' => ['it', 'us']]);
        bindModel($testModel);

        $input = new MultiSelect(name: 'countries', options: TEST_OPTIONS);

        expect($input->value)->toBe(['it', 'us']);
    });

    it('gives priority to old', function () {

        $testModel = new TestModel(['countries' => ['it', 'us']]);
        bindModel($testModel);

        Session::flash('_old_input', [
            'countries' => ['it'],
        ]);

        $input = new MultiSelect(name: 'countries', options: TEST_OPTIONS);

        expect($input->value)->toBe(['it']);
    });

    it('gives priority to specified value', function () {

        $testModel = new TestModel(['countries' => ['us']]);
        bindModel($testModel);

        Session::flash('_old_input', [
            'countries' => ['de'],
        ]);

        $input = new MultiSelect(name: 'countries', value: ['it'], options: TEST_OPTIONS);

        expect($input->value)->toBe(['it']);
    });

});

describe('Renders correctly', function () {
    it('renders default', function () {

        $view = $this->blade(
            '<x-bs::multi-select name="countries" :options="$options" />',
            ['options' => TEST_OPTIONS]
        );

        expect($view)->assertDontSee('<label', false)
            ->and($view)->toMatchSnapshot();

    });

    it('renders with full options multiselected', function () {

        $view = $this->blade(
            '<x-bs::multi-select name="countries" :value="$values" :options="$options" label="Countries" icon="bi bi-map" placeholder="Select something" search-placeholder="Search here" />',
            ['options' => TEST_OPTIONS, 'values' => ['it', 'us']]
        );

        expect($view)
            ->assertSee('<label', false)
            ->assertSee('<option value="it" selected', false)
            ->assertSee('<option value="us" selected', false)
            ->and($view)->toMatchSnapshot();
    });

    it('renders old value when old is present', function () {

        Session::flash('_old_input', [
            'countries' => ['us'],
        ]);

        $view = $this->blade(
            '<x-bs::multi-select name="countries" :options="$options" />',
            ['options' => TEST_OPTIONS]
        );

        expect($view)->assertSee('<option value="us" selected', false);
    });

    it('renders model field when model is present', function () {

        $testModel = new TestModel(['countries' => ['de']]);
        bindModel($testModel);

        $view = $this->blade(
            '<x-bs::multi-select name="countries" :options="$options" />',
            ['options' => TEST_OPTIONS]
        );

        expect($view)->assertSee('<option value="de" selected', false);
    });

});
