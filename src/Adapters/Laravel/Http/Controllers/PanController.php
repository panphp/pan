<?php

namespace Pan\Adapters\Laravel\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Pan\Contracts\AnalyticsRepository;
use Pan\Presentors\AnalyticPresentor;
use Pan\ValueObjects\Analytic;

class PanController
{
    public function index(AnalyticsRepository $analytics, AnalyticPresentor $presenter): View|Factory|Application
    {
        $analytics = $analytics->all();
        $analytics = array_map(fn (Analytic $analytic): array => array_values($presenter->present($analytic)), $analytics);

        return view('pan::index')->with('analytics', $analytics);
    }
}
