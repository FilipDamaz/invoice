<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domain\Invoice\Controllers\InvoiceController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Returns a list of all invoices
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');

// Returns details of a specific invoice by ID
Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');

// Approves a specific invoice by ID
//Route::post('/invoices/{id}/approve', [InvoiceController::class, 'approve'])->name('invoices.approve');
Route::get('/invoices/{id}/approve', [InvoiceController::class, 'approve'])->name('invoices.approve');

// Rejects a specific invoice by ID
Route::post('/invoices/{id}/reject', [InvoiceController::class, 'reject'])->name('invoices.reject');
