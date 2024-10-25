<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Menu\MenuController;

// Route::middleware(['auth:api', 'role:admin'])->group(function () {
Route::controller(MenuController::class)->group(function () {
  Route::get('/get-menus',  'index');
  Route::post('/create-menu',  'store');
  Route::post('/update-menu/{id}',  'update');
  Route::delete('delete-menu/{id}',  'destroy');
});
// });