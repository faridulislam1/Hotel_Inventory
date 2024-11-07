<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AuthController;
use App\Helpers\Routes\RouteHelper;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\middleware\RoleMiddleware;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\BookingController;

Route::get('/countries', [CountriesController::class, 'getAllCountries']);
Route::get('/convertXmlToJson', [AdminController::class, 'convertXmlToJson']);
Route::get('/scraper',action: [TitleController::class,'scraper'])->name('scraper');
 
Route::get('/user', function(Request $request){
    return $request->user();
})->middleware('auth:scantum');
Route::get('/admin', function(Request $request){
    return $request->user();
})->middleware('auth:scantum',RoleMiddleware::class);

// Route::middleware(['auth:scantum', RoleMiddleware::class])->group(function() {
//     Route::get('/admin', function(Request $request){
//         return $request->user();
//     });
// });

 
Route::post('/login', [AuthController::class, 'login']);
//Route::post('/register', [AuthController::class, 'register']);
Route::post('/register1', [AuthController::class, 'register1']);
Route::post('/login1', [AuthController::class, 'login1']);


//booking
Route::post('/bookings', [BookingController::class, 'store'])->name('');
Route::get('/show', [BookingController::class, 'show'])->name('');
//visa_booking
Route::post('/visa_bookings', [BookingController::class, 'visa_store'])->name('');
Route::get('/visa_show', [BookingController::class, 'visa_show'])->name('');
//activity booking
Route::post('/activity_bookings', [BookingController::class, 'activity_store'])->name('');
Route::get('/activity_show', [BookingController::class, 'activity_show'])->name('');
//Rail booking
Route::post('/rail_bookings', [BookingController::class, 'rail_store'])->name('');
Route::get('/rail_show', [BookingController::class, 'rail_show'])->name('');
//Hotel booking
Route::post('/hotel_bookings', [BookingController::class, 'hotel_store'])->name('');
Route::get('/hotel_show', [BookingController::class, 'hotel_show'])->name('');
//Agent booking
Route::post('/agent_bookings', [BookingController::class, 'agent_store'])->name(''); 
Route::get('/agent_show', action: [BookingController::class, 'agent_show'])->name('');
//Tour booking
Route::post('/tour_bookings', [BookingController::class, 'tour_store'])->name('');
Route::get('/tour_show', [BookingController::class, 'tour_show'])->name('');

Route::post('/store-passengers', [BookingController::class, 'stores']);
Route::get('/get-passengers', [BookingController::class, 'getPassengers']);
// Route::get('/get-shows/{id}', [BookingController::class, 'shows']);
Route::get('/get-shows', [BookingController::class, 'shows']);




Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});
Route::middleware('role:super_admin')->group(function () {
    Route::get('/super-admin/dashboard', [SuperAdminController::class, 'index']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
//Route::post('/login', [TitleController::class, 'login']);
//Route::post('/register', [TitleController::class, 'register']);
Route::post('/create',[TitleController::class,'create'])->name('category.new');
Route::get('/manage',[TitleController::class,'store'])->name('category.manage');
Route::get('/category/edit/{id}',[TitleController::class,'edit'])->name('category.edit');
Route::post('/delete/{id}',[TitleController::class,'destroy'])->name('category.delete');
Route::put('/update/{id}',[TitleController::class,'updates'])->name('category.update');

});
 
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/admin', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum', RoleMiddleware::class);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/add-room', [RoomController::class, 'storeroom'])->name('store.room');
    Route::post('/new-room', [RoomController::class, 'saveroom'])->name('new.room');
    Route::get('/manage-room', [RoomController::class, 'manageProduct'])->name('manage.room');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
     Route::post('/login', [AuthController::class, 'login']);
     Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::delete('/delete/{id}/', [AuthController::class, 'delete']);
    Route::delete('/destroy/{id}/', [AuthController::class, 'destroy']);
    Route::get('show/{id}', [AuthController::class, 'show']);
    Route::get('showroom/{id}', [AuthController::class, 'showroom']);
    Route::get('/search', [AuthController::class, 'search']);
    Route::get('/searchhotel', [AuthController::class, 'searchhotel']); 

    //room info
    Route::get('/add-itenary', [AuthController::class, 'storeroom'])->name('store.itenary');
    Route::post('/new-itenary', [AuthController::class, 'saveroom'])->name('new.itenary');
    Route::get('/manage-itenary', [AuthController::class, 'manageroom'])->name('manage.itenary');
    Route::get('/edit-room/{id}', [AuthController::class, 'roomEdit'])->name('edit.room');
    Route::post('/destroy', [AuthController::class, 'destroy'])->name('delete.room');
    Route::post('/update-room', [AuthController::class, 'roomUpdate'])->name('update.room');
    Route::get('/product/detail/{id}', [AuthController::class, 'detail'])->name('product.detail');
    Route::get('/search-hotel', [AuthController::class, 'searchHotel'])->name('search.hotel');
    Route::get('/product/get-subcategory-by-category', [AuthController::class, 'getSubCategoryByCategory'])->name('product.get-subcategory-by-category');
    Route::get('/product/hotel', [AuthController::class, 'hotel'])->name('product.hotel');
    Route::delete('/delete_room/{id}/', [AuthController::class, 'deleteroom']);
    Route::put('/update/{id}', [AuthController::class, 'update'])->name('');

  //hotel info
    Route::get('/add-hotel', [AuthController::class, 'storehotel'])->name('store.hotel');
    Route::post('/new-hotel', [AuthController::class, 'savehotel'])->name('new.hotel'); 
    Route::get('/manage-product', [AuthController::class, 'manageProduct'])->name('manage.product');
    Route::get('/edit-product/{id}', [AuthController::class, 'productEdit'])->name('edit.product');
    Route::post('/delete-product/{id}', [AuthController::class, 'productDelete'])->name('delete.product');
    Route::post('/update-product', [AuthController::class, 'productUpdate'])->name('update.product');
    Route::get('/restricted-route', [AuthController::class, 'getBooks']);

    // passenger info
    Route::post('/create-passenger', [AuthController::class, 'createPassenger']);
    Route::get('/manage-passenger', [AuthController::class, 'managepassenger']);
    Route::get('/index', [AuthController::class, 'index']);
    Route::get('/gethotel', [AuthController::class, 'gethotel']);
    Route::get('/apisearch', [AuthController::class, 'apisearch']);
});

Route::post('/insure_db_order_create', [AuthController::class, 'insure_db_order_create']);
Route::get('/api', [AuthController::class, 'api']);
Route::post('/newlogin', [AuthController::class, 'newlogin']);
Route::get('/country', [AuthController::class, 'country']);
