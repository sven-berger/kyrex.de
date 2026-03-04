<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WissensportalIndexController as WPIndex;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\DummyPageController;
use App\Http\Controllers\ACP\ACPIndexController as ACPIndex;
use App\Http\Controllers\ACP\app\AppCategoriesController as ACPAppCategories;
use App\Http\Controllers\ACP\app\AppPagesController as ACPAppPages;
use App\Http\Controllers\ACP\wissensportal\WissensportalCategorysController as ACPWPCategories;
use App\Http\Controllers\ACP\wissensportal\WissensportalIndexController as ACPWPIndex;
use App\Http\Controllers\ACP\wissensportal\WissensportalPagesController as ACPWPPages;

Route::get('/', [HomeController::class, 'homeAction'])->name('home');
Route::redirect('/wissenspolrtal', '/wissensportal');
Route::get('/wissensportal', [WPIndex::class, 'wissensportalIndexAction'])->name('wissensportal');
Route::get('/wissensportal/{slug}', [WPIndex::class, 'wissensportalPageAction'])->name('wissensportal.page');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/auth/github/redirect', [GithubController::class, 'redirectToGithub'])->name('github.redirect');
Route::get('/auth/github/callback', [GithubController::class, 'handleGithubCallback'])->name('github.callback');


Route::get('/dummy-page', [DummyPageController::class, 'index'])->name('dummy-page.index');
Route::post('/dummy-page', [DummyPageController::class, 'store'])->name('dummy-page.store');
Route::patch('/dummy-page/{entry}', [DummyPageController::class, 'update'])->name('dummy-page.update');
Route::delete('/dummy-page/{entry}', [DummyPageController::class, 'destroy'])->name('dummy-page.destroy');



//// ** Adminbereich ** //
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/acp', [ACPIndex::class, 'acpAction'])->name('acp');

    Route::get('/acp/app/categories', [ACPAppCategories::class, 'index'])->name('acp.app.categories');
    Route::post('/acp/app/categories', [ACPAppCategories::class, 'store'])->name('acp.app.categories.store');
    Route::get('/acp/app/category-edit/{category}', [ACPAppCategories::class, 'edit'])->name('acp.app.categories.edit');
    Route::patch('/acp/app/category-edit/{entry}', [ACPAppCategories::class, 'update'])->name('acp.app.categories.update');
    Route::patch('/acp/app/categories/{entry}/move-up', [ACPAppCategories::class, 'moveUp'])->name('acp.app.categories.move-up');
    Route::patch('/acp/app/categories/{entry}/move-down', [ACPAppCategories::class, 'moveDown'])->name('acp.app.categories.move-down');
    Route::delete('/acp/app/categories/{entry}', [ACPAppCategories::class, 'destroy'])->name('acp.app.categories.destroy');

    Route::get('/acp/app/pages', [ACPAppPages::class, 'index'])->name('acp.app.pages');
    Route::post('/acp/app/pages', [ACPAppPages::class, 'store'])->name('acp.app.pages.store');
    Route::get('/acp/app/page-edit/{page}', [ACPAppPages::class, 'edit'])->name('acp.app.pages.edit');
    Route::patch('/acp/app/page-edit/{entry}', [ACPAppPages::class, 'update'])->name('acp.app.pages.update');
    Route::delete('/acp/app/pages/{entry}', [ACPAppPages::class, 'destroy'])->name('acp.app.pages.destroy');

    Route::redirect('/acp/wissensportal/categorys', '/acp/wissensportal/categories');
    Route::get('/acp/wissensportal/wp-categories', [ACPWPCategories::class, 'wissensportalCategoriesAction'])->name('acp.wissensportal.categories');
    Route::post('/acp/wissensportal/wp-categories', [ACPWPCategories::class, 'store'])->name('acp.wissensportal.categories.store');
    Route::get('/acp/wissensportal/wp-categorie-edit/{category}', fn($category) => redirect()->route('acp.wissensportal.categories.edit', $category));
    Route::get('/acp/wissensportal/wp-category-edit/{category}', [ACPWPCategories::class, 'wpEditAction'])->name('acp.wissensportal.categories.edit');
    Route::patch('/acp/wissensportal/wp-category-edit/{entry}', [ACPWPCategories::class, 'update'])->name('acp.wissensportal.categories.update');
    Route::delete('/acp/wissensportal/wp-categories/{entry}', [ACPWPCategories::class, 'destroy'])->name('acp.wissensportal.categories.destroy');

    Route::redirect('/acp/wissensportal/pages', '/acp/wissensportal/pages');
    Route::get('/acp/wissensportal/wp-pages', [ACPWPPages::class, 'wissensportalPagesAction'])->name('acp.wissensportal.pages');
    Route::post('/acp/wissensportal/wp-pages', [ACPWPPages::class, 'store'])->name('acp.wissensportal.pages.store');
    Route::get('/acp/wissensportal/wp-page-edit/{page}', [ACPWPPages::class, 'wpEditAction'])->name('acp.wissensportal.pages.edit');
    Route::patch('/acp/wissensportal/wp-page-edit/{entry}', [ACPWPPages::class, 'update'])->name('acp.wissensportal.pages.update');
    Route::delete('/acp/wissensportal/wp-pages/{entry}', [ACPWPPages::class, 'destroy'])->name('acp.wissensportal.pages.destroy');


    Route::get('/acp/wissensportal/', [ACPWPIndex::class, 'wissensportalIndexAction'])->name('acp.wissensportal.index');
});
