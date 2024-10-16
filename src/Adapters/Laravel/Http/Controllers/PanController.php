<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Pan\Contracts\AnalyticsRepository;
use Pan\Presentors\AnalyticPresentor;
use Pan\ValueObjects\Analytic;

final class PanController
{
    public function index(AnalyticsRepository $analytics, AnalyticPresentor $presenter): View|Factory|Application
    {
        $analytics = array_map(fn (Analytic $analytic): array => array_values($presenter->present($analytic)), $analytics->all());

        if (is_string(request()->get('q'))) {
            $analytics = array_filter($analytics, fn (array $analytic): bool => str_contains($analytic[1], request()->get('q')));
        }

        return view('pan::index')->with([
            'columns' => $presenter->tableColumns(),
            'analytics' => $analytics,
        ]);
    }
}
