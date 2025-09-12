<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/goto', function () {
    $user = auth()->user();
    $redirects = [
        'admin' => 'admin.dashboard',
    ];

    foreach ($redirects as $role => $route) {
        if ($user->hasRole($role)) {
            return redirect()->route($route);
        }
    }
    return redirect()->route('settings.profile');
})->name('goto');


Route::middleware(['auth'])->group(function () {

    Route::prefix('admin')->name('admin.')->group(function () {
        Volt::route('dashboard', 'admin.dashboard')->name('dashboard');
        Volt::route('controller', 'admin.relay-controller')->name('controller');
        Volt::route('controller/add', 'admin.add-controller')->name('controller.create');
    });

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
