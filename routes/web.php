<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;


use App\Http\Controllers\VehiclePdfController;

Route::middleware(['auth'])->group(function () {
    // PDF Detallado (Interno)
    Route::get('/vehicles/{vehicle}/pdf/detailed', [VehiclePdfController::class, 'downloadDetailed'])
        ->name('vehicles.pdf.detailed');

    // PDF Simple (Cliente)
    Route::get('/vehicles/{vehicle}/pdf/client', [VehiclePdfController::class, 'downloadClient'])
        ->name('vehicles.pdf.client');
});

Route::get('/',function(){
    return redirect()->route('filament.auth.login');
});
