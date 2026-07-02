<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\KapsoWebhookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('clientes.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('clientes.index');
    })->name('dashboard');

    Route::resource('templates', TemplateController::class)->except(['show']);
    Route::resource('clientes', ClienteController::class)->except(['show']);
    Route::post('clientes/{cliente}/enviar', [EnvioController::class, 'enviar'])->name('clientes.enviar');

    Route::get('/empresa', [EmpresaController::class, 'edit'])->name('empresa.edit');
    Route::put('/empresa', [EmpresaController::class, 'update'])->name('empresa.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('webhooks/kapso', [KapsoWebhookController::class, 'handle'])->name('webhooks.kapso');

require __DIR__ . '/auth.php';
