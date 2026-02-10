<?php

use App\Http\Livewire\FonctionnaliteForm;
use App\Http\Livewire\FonctionnaliteList;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ProjetList;
use App\Http\Livewire\ProjetForm;
use App\Http\Livewire\TicketList;
use App\Http\Livewire\TicketForm;
use App\Http\Livewire\ModuleForm;
use App\Http\Livewire\ModuleList;
use App\Http\Livewire\ChangePassword;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return auth()->check() ? redirect()->route('projets.index') : redirect()->route('login');
});


// Routes protégées par auth
// Route::middleware(['auth', 'verified'])->group(function () {
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/change-password', ChangePassword::class)->name('profile.change-password');

    Route::get('/projets', ProjetList::class)->name('projets.index');
    Route::get('/projets/create', ProjetForm::class)->middleware('admin')->name('projets.create');
    Route::get('/projets/{projet}/edit', ProjetForm::class)->middleware('admin')->name('projets.edit');
    Route::get('/projets/{projet_id}/import-modules', \App\Http\Livewire\ModuleImport::class)->middleware('admin')->name('modules.import');

    Route::get('/projets/{projet}/tickets', TicketList::class)->name('tickets.index');
    Route::get('/projets/{projet}/tickets/create', TicketForm::class)->name('tickets.create');
    Route::get('/projets/{projet}/tickets/{ticket}/edit', TicketForm::class)->name('tickets.edit');

    // historique des tickets
    Route::get('/projets/{projet}/tickets/{ticket}/history', \App\Http\Livewire\TicketHistory::class)->name('tickets.history');

    Route::get('/projets/{projet}/modules', ModuleList::class)->middleware('admin')->name('modules.index');
    Route::get('/projets/{projet_id}/modules/create', ModuleForm::class)->middleware('admin')->name('modules.create');
    Route::get('/projets/{projet_id}/modules/{module_id}/edit', ModuleForm::class)->middleware('admin')->name('modules.edit');

    Route::get('/modules/{module}/fonctionnalites', FonctionnaliteList::class)->middleware('admin')->name('fonctionnalites.index');
    Route::get('/modules/{module_id}/fonctionnalites/create', FonctionnaliteForm::class)->middleware('admin')->name('fonctionnalites.create');
    Route::get('/modules/{module_id}/fonctionnalites/{fonctionnalite_id}/edit', FonctionnaliteForm::class)->middleware('admin')->name('fonctionnalites.edit');
});

require __DIR__ . '/auth.php';
