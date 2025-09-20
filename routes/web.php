<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('goto');
})->name('home');

Route::get('/goto', function () {
    $user = auth()->user();
    $redirects = [
        'admin' => 'admin.soil',
    ];

    foreach ($redirects as $role => $route) {
        if($user){
            if ($user->hasRole($role)) {
                return redirect()->route($route);
            }
        }else{
            return redirect()->route('login');
        }
    }
    return redirect()->route('settings.profile');
})->name('goto');


Route::middleware(['auth'])->group(function () {

    Route::prefix('admin')->name('admin.')->group(function () {
        Volt::route('controller', 'admin.relay-controller')->name('controller');
        Volt::route('controller/add', 'admin.add-controller')->name('controller.create');
        Volt::route('soil', 'admin.soil-sensor')->name('soil');
        Volt::route('soil/add', 'admin.add-soil')->name('soil.create');

        Volt::route('master-data/users', 'admin.master-data.user')->name('master-data.user');
    });

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
