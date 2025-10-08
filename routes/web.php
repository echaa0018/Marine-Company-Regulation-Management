<?php

use App\Livewire\Layouts\AppLayout;
use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\Auth\Login;
use App\Exports\AssetTemplateExport;
use App\Http\Controllers\DocumentPreviewController;
use Maatwebsite\Excel\Facades\Excel;

// Route bawaan login (Livewire)
Route::get('/login', Login::class)->name('login');

Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

// Redirect '/' tergantung status login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});

// Route yang butuh login & domain valid
Route::middleware(['auth', 'domainCheck'])->group(function () {
    Route::get('/home', Home::class)->name('home');


    Route::get('/user', \App\Livewire\Users\UserIndex::class)->name('user.index');

    Route::get('/role', \App\Livewire\Roles\RoleIndex::class)->name('role.index');
    Route::get('/role/{id}', \App\Livewire\Roles\RoleShow::class)->name('role.show');

    Route::get('/document/catalog', \App\Livewire\Document\DocumentList::class)->name('document.catalog');
    Route::get('/documents', \App\Livewire\Document\DocumentList::class)->name('documents.index');
    Route::get('/document/create', \App\Livewire\Document\CreateDocument::class)->name('document.create');
    Route::get('/document/{id}', \App\Livewire\Document\DocumentDetail::class)->name('document.detail');
    
    // Route for watermarked PDF preview
    Route::get('/document/{documentId}/preview/{filename}', [DocumentPreviewController::class, 'previewWatermarked'])
        ->name('document.preview-watermarked');
});
