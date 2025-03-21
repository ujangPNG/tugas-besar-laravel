<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/auctions/create', [AuctionController::class, 'create'])->name('auctions.create');
    Route::post('/auctions', [AuctionController::class, 'store'])->name('auctions.store');
    Route::post('/auctions/{auction}/close', [AuctionController::class, 'closeAuction'])->name('auctions.close');
    Route::post('/auctions/{auction}/bids', [BidController::class, 'store'])->name('bids.store');
});

require __DIR__.'/auth.php';
