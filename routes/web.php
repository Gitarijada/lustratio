<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SlideshowController;
use App\Http\Controllers\ValetudinarianController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ValeEventController;
use App\Http\Controllers\Auth\RegisterController;
//use App\Http\Controllers\HomeController;

use App\Http\Controllers\DeclineController;
use App\Http\Controllers\Rote\RoteController;

use App\Mail\EventMail;
//use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*Route::get('/test-mail', function () {
    Mail::raw('Productions e_mail testing!', function ($message) {
        $message->to('mojweb@email.cz')
                ->subject('Productions can see TRY');
    });
    return 'Test e_mail sent!';
});*/
/*Route::get('/', function () {
    return view('welcomen');
});*/
Route::get('/', [SlideshowController::class, 'index']);
Route::get('/tst', [SlideshowController::class, 'main2x']);
Route::get('/fame-all2/{sort_by?}', [SlideshowController::class, 'list_all2']);
Route::get('/upload-guess', [SlideshowController::class, 'uploadGuessImage']);
    //->middleware(['auth', 'verified']);
Route::post('/fame_all/delete', [SlideshowController::class, 'delete'])->name('photos.delete');
//Route::post('guess-ajax', [SlideshowController::class, 'guess_stuff']);    //delete from images_guess tbl //currently not i use (del reccord from images_guess)
Route::get('/make_famous/{id?}',[ValetudinarianController::class, 'make_famous']);  //to add candidate in system, make them famoust option
Route::post('/store_all',[ValetudinarianController::class, 'store_val_event']);   //store valetudinarians, events and make famous person
Route::get('/create_ve',[ValetudinarianController::class, 'create_vale_event']);    //create vale and events in one shot-window

Route::get('/fame_all', [SlideshowController::class, 'list_all']);
Route::get('/fame/{index?}', [SlideshowController::class, 'index'])->whereNumber('index');

//Route::get('/', [ValetudinarianController::class, 'index']);      //show href="{{ url('/') }}  whatever you like

Route::get('/equ',[ValetudinarianController::class, 'index'])
    ->middleware('count.views');
Route::post('/equ', [ValetudinarianController::class, 'index'])->name('valetudinarians.filter');
Route::get('/edit/{id}',[ValetudinarianController::class, 'edit']);
Route::get('/destroy/{id}',[ValetudinarianController::class, 'destroy']);
Route::get('/show/{id}',[ValetudinarianController::class, 'show']);
Route::get('/create',[ValetudinarianController::class, 'create']);
Route::post('/store',[ValetudinarianController::class, 'store']);
Route::post('/update/{id}',[ValetudinarianController::class, 'update']);
Route::get('valetudinarian-ajax', [ValetudinarianController::class, 'valeventEventsX']);        //search etc...

Route::post('/store_img',[ValetudinarianController::class, 'store_image']);
Route::get('/upload_img/{id}',[ValetudinarianController::class, 'upload_image']);

Route::post('/store_event_img',[EventController::class, 'store_image']);
Route::get('/upload_event_img/{id}',[EventController::class, 'upload_image']);

Route::get('/upload_guess_img',[SlideshowController::class, 'upload_image']);   //call image_upload.blade
Route::post('/store_guess_img',[SlideshowController::class, 'store_image']);
/*Route::get("/upload_img", function() {
  return view("image_upload");
});*/

Route::get('/list-event',[EventController::class, 'index']);
Route::post('/list-event', [EventController::class, 'index'])->name('events.filter');
Route::get('/edit-event/{id}',[EventController::class, 'edit']);
Route::get('/destroy-event/{id}',[EventController::class, 'destroy']);
//Route::get('/show-event/{id}',[EventController::class, 'show']); //we not using it, show-valeevent instead of ValeEventController
Route::post('/store-event',[EventController::class, 'store']);
Route::get('/create-event',[EventController::class, 'create']);
Route::get('/create-event-vale-event/{id}',[EventController::class, 'create_event_valeid'])->where('id', '[0-9]+');

//Route::get('/create-event-valeevent/{id}',[ValeEventController::class, 'create_event_valeid'])->where('id', '[0-9]+'); 
Route::get('/create-valeevent/{id}',[ValeEventController::class, 'create'])->where('id', '[0-9]+');   //CREATE NEW EQE
Route::post('/update-event/{id}',[EventController::class, 'update']);
//Route::get('crud-event-ajax', [EventController::class, 'equeventEvents']);        //search etc...

Route::get('add-valeevent', [ValeEventController::class, 'index']);
Route::post('add-valeevent', [ValeEventController::class, 'index'])->name('add-valeevent.filter');
Route::post('/store-valeevent',[ValeEventController::class, 'store']);

Route::get('/show-valeevent/{id}',[ValeEventController::class, 'show'])->where('id', '[0-9]+');      //SHOW LULU EQE-click in CALENDAR

Route::post('valeeventlist-ajax', [EventController::class, 'valeeventEvents']); //button on eventlist.blade table to show multiple vale attached to event_ID
Route::post('valeevent-ajax', [ValeEventController::class, 'valeeventEvents']);         //drop box on events input ajax
Route::post('/valeevent-index', [ValeEventController::class, 'index'])->name('vale.filter');      //add vale to event ajax
Route::post('/valeevent', [ValeEventController::class, 'vale_confirmation'])->name('vale.confirm');      //add vale to event ajax
Route::post('/exist-event-input', [ValetudinarianController::class, 'exist_event_input'])->name('vale.event-input');

Route::post('region-ajax', [ValetudinarianController::class, 'valeventEvents']);    //filter Region
Route::post('/store-corr',[ValetudinarianController::class, 'store_correction']);

//Route::post('output-ajax', [SlideshowController::class, 'post_ajax_data']);
//Route::post('output-ajax', [SlideshowController::class, 'main2x'])->name('vale-output.filter');

Route::get('/goal', function () {
    return view('goal');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/news', function () {
    return view('news');
});
Route::get('/decline', function () {
    return view('decline');
});
Route::get('decline',[DeclineController::class, 'showpage'])->name('decline');  //to use as return route('decline')

Route::get('/auth-register',[RegisterController::class, 'input']);      //registration menue

Route::get('/verify', function () {
    return view('auth.verify');
});

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/', [HomeController::class, 'index']);
//Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/rote',[RoteController::class, 'index']);
Route::post('/rote', [RoteController::class, 'index'])->name('valetudinarians.filter');
Route::get('/rote_show/{id}',[RoteController::class, 'show']);
Route::get('/rote_create',[RoteController::class, 'create']);
Route::get('/rote_create_ve',[RoteController::class, 'create_vale_event']);    //create vale and events in one shot-window
Route::post('/store',[RoteController::class, 'store']);
Route::post('/update/{id}',[RoteController::class, 'update']);

//****************************** without those there is no Login Registrate in menue*/
//***By simply adding Auth::routes(); to your routes/web.php file, Laravel sets up all the necessary routes, controllers, 
//***and views to provide a complete authentication system out-of-the-box.
//Auth::routes();
Auth::routes(['verify' => true]); //LUST-verification calling Auth.php -> in the end AuthRouteMethods.php
//****************************** */

//------------------------------------------------------------------------------------------------------------
//Route for mailing
//Route::get('/email/{id}',[EquEventController::class, 'send_email'])->where('id', '[0-9]+');
Route::get('/***email', function () {
    $emails = ['xxx@email.cz', 'xxx@seznam.cz', 'xxx@gmail.com'];
    
    //Mail::to($emails)->send(new EventMail);  //to()-string //with use Illuminate\Support\Facades\Mail; above
    return new EventMail();       //with use App\mail\EventMail; above
    // option to go: return view('eqcalendar');
    return redirect('/calendar-event');

});
