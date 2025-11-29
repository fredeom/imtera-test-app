<?php

use App\Http\Controllers\MySettingsController;
use App\Services\YandexWidgetParser;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('responses', function (Request $request) {
    try {
        $businessId = mb_split('/', parse_url($request->user()->mysettings->url)['path'])[4];
        if (!is_numeric($businessId)) {
            $businessId = '0';
        }
    } catch (Exception $ex) {
        $businessId = '0';
    }

    $data = ['rating' => 0, 'ratingMini' => 0, 'starsDom' => ['stars' => 0, 'is_last_half' => false], 'comments' => []];;
    if (!empty($businessId)) {
        $url = "https://yandex.ru/maps-reviews-widget/" . $businessId . "?comments";

        $response = Http::get($url);
        $html = $response->body();
        $data = (new YandexWidgetParser())->parse($html);
    }

    return Inertia::render('Responses', ['businessId' => $businessId, 'reviewsData' => $data]);
})->middleware(['auth', 'verified'])->name('Responses');

Route::get('mysettings', [MySettingsController::class, 'edit'])
    ->middleware(['auth', 'verified'])->name('MySettings.edit');

Route::patch('mysettings', [MySettingsController::class, 'update'])
    ->middleware(['auth', 'verified'])->name('MySettings.update');

require __DIR__.'/settings.php';
