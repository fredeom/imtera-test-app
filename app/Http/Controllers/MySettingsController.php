<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MySettingsUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Inertia\Inertia;
use Inertia\Response;

class MySettingsController extends Controller
{
    /**
     * Show mysettings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('MySettings', ['url' => $request->user()->mysettings->url]);
    }

    /**
     * Update mysettings information.
     */
    public function update(MySettingsUpdateRequest $request): RedirectResponse
    {
        $mysettings = $request->user()->mysettings;
        $mysettings->fill($request->validated());
        $mysettings->save();

        return to_route('MySettings.edit');
    }

}
