
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuItem\MenuItemController;

// Route::middleware(['auth:api', 'role:admin'])->group(function () {
Route::controller(MenuItemController::class)->group(function () {

  Route::post('/create-menu-item',  'store');
  Route::post('/update-menu-item/{id}',  'update');
  Route::delete('/delete-menu-item/{id}',  'destroy');
  Route::get('/get-menu-items',  'index');
});
// });