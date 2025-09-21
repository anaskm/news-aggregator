<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//run cron every fifteen minutes
Schedule::command('news:fetch')
    ->everyFifteenMinutes()
    ->withoutOverlapping();
