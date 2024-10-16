<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Pan\PanConfiguration;

pest()
    ->extend(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(fn () => PanConfiguration::reset())
    ->in('Feature', 'Unit');
