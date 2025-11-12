<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ProjetList;
use App\Http\Livewire\ProjetForm;
use App\Http\Livewire\TicketList;
use App\Http\Livewire\TicketForm;

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
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/projets', ProjetList::class)->name('projets.index');
    Route::get('/projets/create', ProjetForm::class)->middleware('admin')->name('projets.create');
    Route::get('/projets/{projet}/edit', ProjetForm::class)->middleware('admin')->name('projets.edit');

    Route::get('/projets/{projet}/tickets', TicketList::class)->name('tickets.index');
    Route::get('/projets/{projet}/tickets/create', TicketForm::class)->name('tickets.create');
    Route::get('/projets/{projet}/tickets/{ticket}/edit', TicketForm::class)->name('tickets.edit');
});

require __DIR__ . '/auth.php';
