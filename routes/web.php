<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BagOpenController;
use App\Http\Controllers\BagCloseController;
use App\Http\Controllers\ManifestController;
use App\Http\Controllers\BagReportController;
use App\Http\Controllers\pdfReportController;
use App\Http\Controllers\BagReceiveController;
use App\Http\Controllers\PendingBagController;
use App\Http\Controllers\ArticleDetailController;
use App\Http\Controllers\ArticleReportController;
use App\Http\Controllers\PendingArticleController;

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
    Route::put('bag-open-save/{bag}', [BagOpenController::class, 'save'])->name('bag-open.save');
    Route::delete('bag-open-article-delete-scan', [BagOpenController::class, 'articleDeleteScan'])->name('bag-open.articleDeleteScan');

    Route::get('bag-close', [BagCloseController::class, 'index'])->name('bag-close.index');
    Route::get('bag-close-bag-scan', [BagCloseController::class, 'bagScan'])->name('bag-close.bagScan');
    Route::post('bag-close-article-scan', [BagCloseController::class, 'articleScan'])->name('bag-close.articleScan');
    Route::put('bag-close-save/{bag}', [BagCloseController::class, 'save'])->name('bag-close.save');
    Route::delete('bag-close-article-delete-scan', [BagCloseController::class, 'articleDeleteScan'])->name('bag-close.articleDeleteScan');

    Route::get('manifest/{bag}', [pdfReportController::class, 'manifest'])->name('bag-manifest');

    Route::get('report', [ReportController::class, 'index'])->name('report.index');
    Route::get('manifest-report', [ManifestController::class, 'index'])->name('manifest-report.index');
    Route::get('article-detail', [ArticleDetailController::class, 'index'])->name('article-detail.index');

    Route::get('bag-report', [BagReportController::class, 'index'])->name('bag-report.index');
    Route::post('bag-report', [BagReportController::class, 'store'])->name('bag-report.store');

    Route::get('article-report', [ArticleReportController::class, 'index'])->name('article-report.index');
    Route::post('article-report', [ArticleReportController::class, 'store'])->name('article-report.store');

    Route::get('pending-bag', [PendingBagController::class, 'index'])->name('pending-bag.index');
    Route::get('pending-article', [PendingArticleController::class, 'index'])->name('pending-article.index');

    Route::get('export', [ExportController::class, 'index'])->name('export.index');
    Route::post('export', [ExportController::class, 'export'])->name('export.export');
});


require __DIR__.'/auth.php';
