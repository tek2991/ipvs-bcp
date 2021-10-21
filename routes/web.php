<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetController;
use App\Http\Controllers\BagOpenController;
use App\Http\Controllers\BagReceiveController;

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
    return view('welcome');
});


Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::resource('set', SetController::class)->only('index', 'store');
    Route::put('set', [SetController::class, 'update'])->name('set.update');

    Route::get('bag-receive', [BagReceiveController::class, 'index'])->name('bag-receive.index');
    Route::post('bag-receive', [BagReceiveController::class, 'store'])->name('bag-receive.store');

    Route::get('bag-open', [BagOpenController::class, 'index'])->name('bag-open.index');
    Route::get('bag-open-bag-scan', [BagOpenController::class, 'bagScan'])->name('bag-open.bagScan');
    Route::post('bag-open-article-scan', [BagOpenController::class, 'articleScan'])->name('bag-open.articleScan');
    Route::delete('bag-open-article-delete-scan', [BagOpenController::class, 'articleDeleteScan'])->name('bag-open.articleDeleteScan');
});


require __DIR__.'/auth.php';
