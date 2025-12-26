<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\CrudEventController;
use App\Http\Controllers\GroupEventController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\EquEventController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\CrewEventController;

use App\Mail\EventMail;
use Illuminate\Support\Facades\Mail;

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

/*Route::get('/', function () {
    return view('welcomen');
});*/

Route::get('/equ',[EquipmentController::class, 'index']);
Route::post('/equ', [EquipmentController::class, 'index'])->name('equipments.filter');
Route::get('/edit/{id}',[EquipmentController::class, 'edit']);
Route::get('/destroy/{id}',[EquipmentController::class, 'destroy']);
Route::get('/show/{id}',[EquipmentController::class, 'show']);
Route::get('/create',[EquipmentController::class, 'create']);
Route::post('/store',[EquipmentController::class, 'store']);
Route::post('/update/{id}',[EquipmentController::class, 'update']);
Route::get('equipment-ajax', [EquipmentController::class, 'equeventEvents']);        //search etc...

Route::get('/list-crew',[CrewController::class, 'index']);
Route::post('/list-crew', [CrewController::class, 'index'])->name('crew.filter');
Route::get('/edit-crew/{id}',[CrewController::class, 'edit']);
Route::get('/destroy-crew/{id}',[CrewController::class, 'destroy']);
Route::get('/show-crew/{id}',[CrewController::class, 'show']);
//Route::get('/create-crew',[CrewController::class, 'create']);     //user table, not to be able to create record, security
//Route::post('/store-crew',[CrewController::class, 'store']);     //user table, not to be able to create record, security
Route::post('/update-crew/{id}',[CrewController::class, 'update']);

Route::get('/list-event',[CrudEventController::class, 'index']);
Route::post('/list-event', [CrudEventController::class, 'index'])->name('events.filter');
Route::get('/edit-event/{id}',[CrudEventController::class, 'edit']);
Route::get('/destroy-event/{id}',[CrudEventController::class, 'destroy']);
Route::get('/show-event/{id}',[CrudEventController::class, 'show']);
Route::get('/create-event',[CrudEventController::class, 'create']);
Route::post('/store-event',[CrudEventController::class, 'store']);
Route::post('/update-event/{id}',[CrudEventController::class, 'update']);
Route::get('crud-event-ajax', [CrudEventController::class, 'equeventEvents']);        //search etc...

Route::get('/list-group-event',[GroupEventController::class, 'index']);
Route::get('/edit-group-event/{id}',[GroupEventController::class, 'edit']);
Route::get('/destroy-group-event/{id}',[GroupEventController::class, 'destroy']);
Route::get('/show-group-event/{id}',[GroupEventController::class, 'show']);
Route::get('/create-group-event',[GroupEventController::class, 'create']);
Route::post('/store-group-event',[GroupEventController::class, 'store']);
Route::post('/update-group-event/{id}',[GroupEventController::class, 'update']);

Route::get('/', [CalenderController::class, 'index']);      //show cal
Route::get('calendar-event', [CalenderController::class, 'index']);     //main call for cal event 
Route::get('calendar-equ/{id}', [CalenderController::class, 'index_equ']);  //all equipments cal. main call for cal event
Route::get('calendar-equ', [CalenderController::class, 'index_equs']);  //equipment cal. main call for cal event
Route::post('calendar-crud-ajax', [CalenderController::class, 'calendarEvents']);   //move, drop, se;ect in cal...

Route::get('/equevents',[EquEventController::class, 'index']);  //tmp, not use, good for nothing
Route::get('/create-equevent/{id}',[EquEventController::class, 'create'])->where('id', '[0-9]+');   //CREATE NEW EQE
Route::post('/store-equevent',[EquEventController::class, 'store']);
//Route::get('/email/{id}',[EquEventController::class, 'send_email'])->where('id', '[0-9]+');

//Route::get('/add-equevent', [EquEventController::class, 'add']);                                     //ADD NEW EQE
//Route::post('equevent-ajax', [EquEventController::class, 'equeventEvents']);        //IN USE for search
Route::get('add-equevent', [EquEventController::class, 'index']);
Route::post('add-equevent', [EquEventController::class, 'index'])->name('add-equevent.filter');
//Route::post('select-event-request', [EquEventController::class, 'selectRequestPost'])->name('selectRequest.post');

Route::get('/create-crew/{id}',[CrewEventController::class, 'create'])->where('id', '[0-9]+');   //CREATE NEW CREW
Route::get('add-crew', [CrewEventController::class, 'index']);
Route::post('add-crew', [CrewEventController::class, 'index'])->name('add-crew.filter');
Route::post('/store-crew',[CrewEventController::class, 'store']);
//Route::get('/email/{id}',[CrewEventController::class, 'send_email'])->where('id', '[0-9]+');  //change to function call $this->Sendmail()

Route::get('/show-equevent/{id}',[EquEventController::class, 'show'])->where('id', '[0-9]+');      //SHOW EQE-click in CALENDAR
Route::post('equevent-ajax', [EquEventController::class, 'equeventEvents']);
Route::get('/destroy-equevent/{id}',[EquEventController::class, 'destroy']);

Route::get('/about', function () {
    return view('about');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//------------------------------------------------------------------------------------------------------------
//Route for mailing
//Route::get('/email/{id}',[EquEventController::class, 'send_email'])->where('id', '[0-9]+');
Route::get('/***email', function () {
    $emails = ['xxx@email.cz', 'xxx@seznam.cz', 'xxx@gmail.com'];
    
    //Mail::to($emails)->send(new EventMail);  //to()-string //with use Illuminate\Support\Facades\Mail; above
    return new EventMail();       //with use App\mail\EventMail; above
    // option to go: return view('eqcalendar');
    return redirect('/calendar-event');

    //var_dump( Mail:: failures());   //if non sent error in string, with out empty string: array(0) { }
    //exit;
    
//----------------------------------------------------------------------
    //Mail::to($emails)->send(new EventMail);
//----------------------------------------------------------------------
    //Mail::to('mojweb@email.cz')->queue(new EventMail);
//----------------------------------------------------------------------
    //$when = Carbon\Carbon::now()->addMinutes(10);
    //Mail::to('mojweb@email.cz')->later($when, new EventMail);
//----------------------------------------------------------------------
    /*Mail::send(new EventMail, [], function($message) use ($emails)
    {
        $message->to($emails)->subject('This is test, to send e-mail');  
    });*/
//----------------------------------------------------------------------
    /*$data = [];
    Mail::send(new EventMail, $data, function ($message) {
        $message->from('lavietzigane@gmail.com', 'My Welcome');    
        $message->to('djordjino@seznam.cz')->cc('mojweb@email.cz');
    });*/
//----------------------------------------------------------------------
    /*//list of methods on the $message Laravel message builder instance 
    $message->from($email, $username = null);
    $message->sender($email, $username = null);
    $message->to($email, $username = null);
    $message->cc($email, $username = null);
    $message->bcc($email, $username = null);
    $message->replyTo($email, $username = null);
    $message->subject($subject);
    $message->priority($level);
    $message->attach($pathToFile, array $options = []);

    // Image or Attach a file from a some raw $data string...
    $message->attachData($data, $username, array $options = []);

    // Retrive the underlying simple Laravel SwiftMailer message instance...
    $message->getSwiftMessage();
    */
});
