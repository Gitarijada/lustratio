<?php 
use Illuminate\Support\Facades\Route;   //add by me...
Route::get('/', 'WelcomeController@index');

    Route::get('/classes', function() {
    echo Paste\Pre::render(get_declared_classes(),'');
     });
                              

Route::get('/', 'HomeController@index');
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',    
]);

Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);

//-------------------------equipment route------------------------////
    // Route::model('equipment', 'Equipment');

    Route::group( array('prefix' => 'admin' ), function () {

       
    Route::get('valetudinarian/index',[
            'as' => 'admin.valetudinarian.index', 
            'uses'=>'ValetudinarianController@index'
        ]);
    Route::resource('valetudinarian', 'ValetudinarianController',[
             'only'=>[ 'create','store', 'show',
             'edit', 'update', 'destroy']
        ]);
    Route::get('/valetudinarian.search_form', 'ValetudinarianController@getSearch');
    Route::post('/valetudinarian.search_result', 'ValetudinarianController@postSearch'); 

    //----------------------------Category route------------------------////
    Route::get('categories/index', [
            'as' => 'admin.categories.index', 
            'uses'=>'CategoriesController@index'
        ]);
    Route::resource('categories', 'CategoriesController', [         
     'only'=>['index','create','store', 'show',
             'edit', 'update', 'destroy']
    ]); 

    Route::get('users/index', [
            'as' => 'admin.users.index', 
            'uses'=>'UserController@index'
        ]);
    Route::resource( 'users', 'UserController',[
            'only'=>[ 'create','show','edit', 'update', 'destroy']
        ]);  
});

// Route::resource('users', 'UsersController', [
//     'only' => ['index', 'update', 'destroy', 'edit'],
//     'names' => [
//         'index' => 'list_users',
//         'destroy' => 'delete_account',
//         'edit' => 'edit_profile'
//     ]
// ]);

     /* Shop - Front end */
//-------------------------------- Rental route------------------------////

    Route::get('/rental/index', ['as' => 'rental.index', 
        'uses' =>'RentalController@index']);  


    Route::get('/rental/getCategory/{cat}', [
        'as' => 'rental.category', 
         'uses' => 'RentalController@getCategory']);
         
    Route::get('/rental/view/{id}', ['as' => 'rental.view', 
        'uses' => 'RentalController@getView']); 

    Route::get('/rental/show/{id}', ['as' => 'rental.show', 
        'uses' => 'RentalController@show']); 

    Route::get('/rental/search', [
                'as' => 'rental.search', 
             'uses' => 'RentalController@getSearch'
               ]); 
   
    Route::get('rental/checkoutForm', [
            'as' => 'rental.checkoutForm',
            'uses' => 'RentalController@getCheckout'
            ]);  

   Route::post('rental/checkout', [
             'as' => 'rental.checkout',
              'uses' => 'RentalController@postCheckout'
               ]); 

    Route::get('/rental/contactForm', [
             'as' => 'contactForm',
              'uses' => 'RentalController@getContact'
               ]);  

    Route::post('/rental/contact', [
             'as' => 'contact',
              'uses' => 'RentalController@postContact'
               ]);

   //  Route::get('/rental/rentEquipment',[
   //              'as' => 'rentEquipment', 
   //              'uses' =>'RentalController@getRentEquipment'
   //             ]);
 
////-------------------------------- Cart route------------------------////

        Route::get('cart/index', ['as' => 'cart.index', 
        'uses' =>'CartController@index']); 

         Route::put('cart/update', ['as' => 'cart.update', 
        'uses' =>'CartController@update']);  

        Route::post('cart/store', ['as' => 'cart.store', 
        'uses' =>'CartController@store']); 
   

        Route::get('cart/delete/{rowid}', ['as' => 'cart.delete', 
        'uses' =>'CartController@delete']); 
        
        Route::get('cart/clear', ['as' => 'cart.clear', 
        'uses' =>'CartController@clear']);
//-------------------- order Routes---------------------------// 
        // Route::resource('cart', 'CartController', [         
        // 'only'=>['create','store', 'show',
        //      'edit', 'update', 'destroy']
        // ]); 
        
        //  Route::resource('cart', 'CartController', [
        // 'except' => ['show', 'create']
        // 'names' => [
        //             'index' => 'show_cart',
        //             'edit' => 'edit_cart_view',
        //             'update' => 'update_cart',
        //             'store' => 'add_to_cart',
        //             'destroy' => 'delete_item'
        //             ]
        //]);