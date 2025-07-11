<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\UserActionsController;
use App\Http\Middleware\CheckUserType;
use App\Livewire\Board\Catalog\Categories\CategoryDetail;
use App\Livewire\Board\Catalog\Categories\CreateCategory;
use App\Livewire\Board\Catalog\Products\CreateProduct;
use App\Livewire\Board\Catalog\Products\ProductDetail;
use App\Livewire\Board\Catalog\Products\ProductOrders;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// PUBLIC ROUTES
// -----------------------------------------------------------------------------
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/cart', function() {
    return view('cart.index');
})->name('cart.index');

// AUTHENTICATED ROUTES
// -----------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('dashboard', App\Livewire\Dashboard::class)
        ->middleware(['verified'])
        ->name('dashboard');

    // Settings
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')
        ->middleware(CheckUserType::class.':board|member|pending_member')
        ->name('settings.profile');
    Volt::route('settings/security', 'settings.security')
        ->name('settings.security');
    Volt::route('settings/appearance', 'settings.appearance')
        ->name('settings.appearance');
    Volt::route('settings/danger-zone', 'settings.danger-zone')
        ->middleware(CheckUserType::class.':board|member|pending_member')
        ->name('settings.danger-zone');

    // Receipt routes
    Route::get('/receipts/{orderId}', [App\Http\Controllers\ReceiptController::class, 'show'])
        ->name('receipts.show');
});

// PENDING MEMBER ROUTES
// -----------------------------------------------------------------------------
Route::middleware(['auth', 'verified', CheckUserType::class.':pending_member'])->group(function () {
    Route::get('dashboard/membership/pending', function () {
        return view('components.dashboard.membership.pending');
    })->name('membership.pending');
});

// MEMBER & BOARD ROUTES
// -----------------------------------------------------------------------------
Route::middleware(['auth', 'verified', CheckUserType::class.':board|member'])->group(function () {
    Route::get('/balance', App\Livewire\Balance::class)->name('balance.index');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::get('/checkout/confirmation/{order_id?}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

    Route::get('/orders', function () {
        return view('orders.index');
    })->name('orders.index');
});

// EMPLOYEE & BOARD ROUTES
// -----------------------------------------------------------------------------
Route::middleware(['auth', 'verified', CheckUserType::class.':employee|board'])->group(function () {
    Route::get('/employee/orders', function () {
        return view('employee.orders.index');
    })->name('employee.orders.index');

    Route::get('/employee/inventory', function () {
        return view('employee.inventory.index');
    })->name('employee.inventory.index');
    
    Route::get('/employee/inventory/restock', function () {
        return view('employee.inventory.restock');
    })->name('employee.inventory.restock');
    
    Route::get('/employee/inventory/supply-orders', function () {
        return view('employee.inventory.supply-orders');
    })->name('employee.inventory.supply-orders');
});

// BOARD ADMIN ROUTES
// -----------------------------------------------------------------------------
Route::middleware(['auth', CheckUserType::class.':board'])->group(function () {
    // User Management
    Route::get('/board/users', [BoardController::class, 'userManagement'])->name('board.users');
    Route::get('/board/users/{user}', [BoardController::class, 'userDetail'])->name('board.users.show');

    Route::prefix('board/users/{user}')->group(function () {
        Route::post('approve', [UserActionsController::class, 'approveMembership'])->name('board.users.approve');
        Route::post('promote', [UserActionsController::class, 'promoteToBoard'])->name('board.users.promote');
        Route::post('demote', [UserActionsController::class, 'demoteToMember'])->name('board.users.demote');
        Route::post('toggle-lock', [UserActionsController::class, 'toggleLock'])->name('board.users.toggle-lock');
        Route::post('toggle-membership', [UserActionsController::class, 'toggleMembership'])->name('board.users.toggle-membership');
    });

    // Catalog Management
    Route::get('/board/catalog/products', [BoardController::class, 'products'])->name('board.catalog.products');
    Route::get('/board/catalog/categories', [BoardController::class, 'categories'])->name('board.catalog.categories');
    Route::get('/board/catalog/products/create', CreateProduct::class)->name('board.catalog.products.create');
    Route::get('/board/catalog/categories/create', CreateCategory::class)->name('board.catalog.categories.create');
    Route::get('/board/catalog/products/{product_id}', ProductDetail::class)->name('board.catalog.products.show');
    Route::get('/board/catalog/products/{product_id}/orders', ProductOrders::class)->name('board.catalog.products.orders');
    Route::get('/board/catalog/categories/{category_id}', CategoryDetail::class)->name('board.catalog.categories.show');
    // Statistics
    Route::get('/board/statistics', [BoardController::class, 'statistics'])->name('board.statistics');

    // Business Settings
    Volt::route('board/business/settings/membership-fee', 'business-settings.membership-fee')
        ->name('board.business.settings.membership-fee');
    Volt::route('board/business/settings/shipping-costs', 'business-settings.shipping-costs')
        ->name('board.business.settings.shipping-costs');
    Volt::route('board/business/settings/caching', 'business-settings.caching')
        ->name('board.business.settings.caching');
});

// AUTH ROUTES
// -----------------------------------------------------------------------------
require __DIR__.'/auth.php';
