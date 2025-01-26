<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TitleController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/add-hotel',[HotelController::class,'storehotel'])->name('store.hotel');
    Route::post('/new-hotel',[HotelController::class,'savehotel'])->name('new.hotel');
    Route::get('/manage-product',[HotelController::class,'manageProduct'])->name('manage.product');
    Route::get('/edit-product/{id}',[HotelController::class,'productEdit'])->name('edit.product');
    Route::post('/delete-product',[HotelController::class,'productDelete'])->name('delete.product');
    Route::post('/update-product',[HotelController::class,'productUpdate'])->name('update.product');
    Route::get('/product/detail/{id}',[HotelController::class,'detail'])->name('product.detail');
    //itenery

    Route::get('/add-itenary',[RoomController::class,'storeroom'])->name('store.itenary');
    Route::post('/new-itenary',[RoomController::class,'saveroom'])->name('new.itenary');
    Route::get('/manage-itenary',[RoomController::class,'manageroom'])->name('manage.itenary');
    Route::get('/edit-room/{id}',[RoomController::class,'roomEdit'])->name('edit.room');
    Route::post('/destroy',[RoomController::class,'destroy'])->name('delete.room');
    Route::post('/update-room',[RoomController::class,'roomUpdate'])->name('update.room');
    Route::get('/product/detail/{id}',[RoomController::class,'detail'])->name('product.detail');
    Route::get('/search-hotel',[RoomController::class,'searchHotel'])->name('search.hotel');

    Route::get('/product/get-subcategory-by-category',[RoomController::class,'getSubCategoryByCategory'])->name('product.get-subcategory-by-category');
    Route::get('/product/hotel',[RoomController::class,'hotel'])->name('product.hotel');
    Route::post('/get-cities', [ItenaryController::class,'getcity']); 
    Route::post('/get-hotels', [ItenaryController::class,'gethotel']);

   // Route::post('/getUpazilaInfo', [CommitteeController::class,'getUpazilaInfo']);
    // Route::get('/search-hotel', 'YourControllerName@searchHotel')->name('search.hotel');
    //User
    Route::get('/add-user',[UserController::class,'addUser'])->name('add.user');
    Route::get('/manage-user',[UserController::class,'manageUser'])->name('manage.user');
    Route::post('/new-user',[UserController::class,'saveUser'])->name('new.user');
    Route::get('/new-edit/{id}',[UserController::class,'editUser'])->name('user.edit');
    Route::post('/new-delete',[UserController::class,'deleteUser'])->name('user.delete');
    Route::post('/update-user',[UserController::class,'updateUser'])->name('update.user');

    // Route::controller(AuthController::class)->group(function () {
    //     Route::post('login', 'login');
    //     Route::post('register', 'register');
    //     Route::post('logout', 'logout');
    //     Route::post('refresh', 'refresh');
 
    // });
    // Route::get('/login',[AuthController::class,'login'])->name('login');
    // Route::get('/register',[AuthController::class,'register'])->name('register');

//    Route::get('/add-room',[RoomController::class,'storeroom'])->name('store.room');
//    Route::post('/new-room',[RoomController::class,'saveroom'])->name('new.room');
//    Route::get('/manage-room',[RoomController::class,'manageProduct'])->name('manage.room');

     Route::get('/scraper',action: [TitleController::class,'scraper'])->name('scraper');

//vhjngjmghjcbc

});




