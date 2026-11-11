<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::redirect('/', '/products');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{products}/show', [ProductController::class, 'show'])->name('products.show');
Route::delete('/products/{products}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('/products/{products}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{products}', [ProductController::class, 'update'])->name('products.update');
