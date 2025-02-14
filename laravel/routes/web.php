<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;

Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/auctions', function () {
    return view('lelang.index');
});*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Add these new routes for auctions
    Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
    Route::get('/auctions/create', [AuctionController::class, 'create'])->name('auctions.create');
    Route::post('/auctions', [AuctionController::class, 'store'])->name('auctions.store');
    Route::resource('auctions', AuctionController::class);
    Route::post('/auctions/{auction}/close', [AuctionController::class, 'closeAuction'])->name('auctions.close');
    
    // Add this route for bids
    Route::post('/auctions/{auction}/bids', [BidController::class, 'store'])->name('bids.store');
});


require __DIR__.'/auth.php';
