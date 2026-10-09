<?php
2
3	use Illuminate\Foundation\Inspiring;
4	use Illuminate\Support\Facades\Artisan;
5	use Illuminate\Support\Facades\Schedule;
6
7	Artisan::command('inspire', function () {
8	    $this->comment(Inspiring::quote());
9	})->purpose('Display an inspiring quote');
10
11	Schedule::command('erp:backup:db')->dailyAt('02:00');
12	Schedule::command('erp:backup:purge')->weekly();
