<?php
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Vendor\StaxConnect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('webhook/staxconnect-webhook-call', [StaxConnect::class, 'handle_webhook_trigger']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::apiResource('vendors', VendorController::class);