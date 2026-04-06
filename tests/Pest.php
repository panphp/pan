<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Pan\PanConfiguration;
use Tests\TestCase;

pest()
    ->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(fn () => PanConfiguration::reset())
    ->in('Feature', 'Unit');
