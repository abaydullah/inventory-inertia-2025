<?php

namespace App\Providers;

use App\Models\Mark;
use App\Models\Subject;
use App\Models\Test;
use App\Observers\MarkObserver;
use App\Observers\SubjectObserver;
use App\Observers\TestObserver;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use PhpParser\JsonDecoder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

    }
}
