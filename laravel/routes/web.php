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

Route::get('/test-winner', function () {
    // Create a test user if needed
    $user1 = \App\Models\User::find(1) ?? \App\Models\User::create([
        'name' => 'Test User 1',
        'email' => 'test1@example.com',
        'password' => bcrypt('password')
    ]);
    
    $user2 = \App\Models\User::find(2) ?? \App\Models\User::create([
        'name' => 'Test User 2',
        'email' => 'test2@example.com',
        'password' => bcrypt('password')
    ]);
    
    // Create a test auction
    $auction = new \App\Models\Auction();
    $auction->title = 'Test Auction';
    $auction->description = 'Test Description';
    $auction->user_id = $user1->id;
    $auction->starting_price = 100;
    $auction->current_price = 100;
    $auction->end_date = now()->addDays(-1);
    $auction->save();
    
    // Create a test bid
    $bid = new \App\Models\Bid();
    $bid->auction_id = $auction->id;
    $bid->user_id = $user2->id;
    $bid->bid_amount = 150;
    $bid->save();
    
    // Close the auction
    $auction->close();
    
    // Refresh from the database
    $auction->refresh();
    
    // Return debug info
    return [
        'auction_id' => $auction->id,
        'is_closed' => $auction->is_closed,
        'winner_id' => $auction->winner_id,
        'winner_name' => $auction->winner ? $auction->winner->name : 'No winner',
        'highest_bid' => $auction->bids()->orderBy('bid_amount', 'desc')->first(),
        'all_columns' => \DB::getSchemaBuilder()->getColumnListing('auctions'),
        'auction_dump' => $auction->toArray()
    ];
});

require __DIR__.'/auth.php';
