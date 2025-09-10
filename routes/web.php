<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/goto', function () {
    $user = auth()->user();
    $redirects = [
        'developer' => 'developer.dashboard',
        'host'  => 'host.dashboard',
        'user'  => 'settings.profile',
    ];

    foreach ($redirects as $role => $route) {
        if ($user->hasRole($role)) {
            return redirect()->route($route);
        }
    }
    return redirect()->route('settings.profile');
})->name('goto');


Route::middleware(['auth'])->group(function () {

    Route::prefix('developer')->name('developer.')->group(function () {
        Volt::route('dashboard', 'developer.dashboard')->name('dashboard');
        Volt::route('master-data/users', 'developer.master-data.user')->name('master-data.users');
        Volt::route('master-data/roles', 'developer.master-data.role')->name('master-data.roles');
    });

    Route::prefix('host')->name('host.')->group(function () {
        Volt::route('dashboard', 'host.dashboard')->name('dashboard');
    });

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
