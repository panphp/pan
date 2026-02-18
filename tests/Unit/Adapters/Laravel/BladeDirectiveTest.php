<?php

use Illuminate\Support\Facades\Blade;

it('renders the @pan directive as a data-pan attribute', function (): void {
    $compiled = Blade::compileString('<button @pan(\'tab-1\')>Tab 1</button>');

    expect($compiled)->toContain('data-pan="<?php echo e(\'tab-1\'); ?>"');
});

it('renders the @pan directive with a variable', function (): void {
    $compiled = Blade::compileString('<button @pan($name)>Tab</button>');

    expect($compiled)->toContain('data-pan="<?php echo e($name); ?>"');
});

it('renders the @pan directive with a concatenated expression', function (): void {
    $compiled = Blade::compileString('<button @pan(\'tab-\' . $id)>Tab</button>');

    expect($compiled)->toContain('data-pan="<?php echo e(\'tab-\' . $id); ?>"');
});
