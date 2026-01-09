<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\BarangList;
use App\Livewire\SelectiveMigrate;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Livewire\InvtItem\ItemIndex;
use App\Livewire\InvtItem\ItemEdit;


Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('barang-list', BarangList::class)->name('barang-list');
    Route::get('selective-migrate', SelectiveMigrate::class)->name('selective-migrate');

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

        Route::get('/items', ItemIndex::class)->name('items.index');
        Route::get('/items/{item}', ItemEdit::class)->name('items.edit');
});
