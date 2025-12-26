<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//SERGuse Illuminate\Support\Facades;

use App\Models\Valetudinarian;
use App\Models\Location;
use App\Models\Party;
use App\Models\Image as My_Image;
use Intervention\Image\ImageManagerStatic as Image;

use Illuminate\Support\Facades\DB;

//for validation - strtoupper()
use App\Rules\Uppercase;

use Carbon\Carbon;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
//use Illuminate\Pagination\LengthAwarePaginator;
//use Illuminate\Support\Facades\Config;
use App\Models\Category;
use App\Models\ImageGuess;
use App\Models\Event;
use App\Models\ImageEvent;
use App\Models\ValeEvent;
use App\Models\Correction;

use App\Rules\UniquePerson;

use App\Services\DataService;

use Illuminate\Support\Facades\View; // Import the View facade
use Illuminate\Support\Facades\Validator;

class ValetudinarianController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService)
    {
        //$this->middleware('guest');
        //$this->middleware ('admin', ['except' => ['index', 'show', 'valeventEvents']]);   //new role add in middleware, not in users table, set id 1 to be admin
        $this->middleware('auth', ['except' => ['index', 'show', 'valeventEvents']]);    // HERE what ya are allowed to access if not loged in
        //$this->middleware('signed', ['except' => ['index', 'show', 'valeventEvents']])->only('verify');

        $this->dataService = $dataService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$postal_get = new postal_code_location();
        //$postal_get->get_region();
        //$postal_get->load_from_files();

        //$showTemp = session('showValetudinarianInfoModal');
        if (session('showValetudinarianInfoModal') === null) {    
            session(['showValetudinarianInfoModal' => true]);     //set to true, not set, show once 
        } else {
            session(['showValetudinarianInfoModal' => false]);
        } 
        //$showModal = session()->pull('showValetudinarianInfoModal', true);    //to reset to session('showValetudinarianInfoModal') = null
        //session(['showValetudinarianInfoModal' => true]);   // for test purposes-always true

        //************non & ajax call*****************************************************************
        $regions = Location::distinct()->get(['region']);
        if ($request->region) {
            $cities = Location::select(['city_zip', 'city'])
            ->where('region', '=', $request->region)
            ->distinct()->get();
        } else {
            $cities = Location::select(['city_zip', 'city'])->distinct()->get();
        }
        $parties = Party::all();

        $paginated = true;
        $paginatedData = $this->dataService->getPaginatedData(NULL, $paginated, 'valetudinarians', $request);
        //getPaginatedData($sql = NULL, $paginated = true, $source = 'valetudinarians', Request $request, 'a.id', 'ASC')
        $paginatedData = $this->dataService->getImages($paginatedData, 'valetudinarian_id');
        
        $allRequestParams = $request->except(['_token', '_method']);
        if (!empty($allRequestParams)) {
            //set links and items selected for: 'party_ID', 'category_ID', 'region', 'location_ID', 'search_Str'
            $UrlFiltersData = $this->dataService->fetchUrlFiltersData($paginatedData, $request);
            $paginatedData = $UrlFiltersData['paginated_data'];
            $drop_item_selected = $UrlFiltersData['drop_item_selected_filters'];
        } else {
            $drop_item_selected = null;
        }

        //***** ajax call ****
        if($request->ajax()) {
            switch ($request->type) {
                case 'data-output':
                    $paginatedData->setPath('/lustratio/public/equ');
                    $valetudinarians = $paginatedData;
                    $layout = 'index';
                    $html = view('valetudinarianslist', compact('regions', 'cities', 'parties', 
                                                                'valetudinarians', 'drop_item_selected', 'layout'))->render();
                    // Return as JSON (so you can access it in AJAX success)
                    return response()->json(['html' => $html]);
                    
                break;
                case 'filter-js':
                    $paginatedData->appends(request()->except('page'))->links(); //By using this, the generated pagination links will only include the 'page' parameter and any other parameters that were explicitly not excluded by except().

                    if ((stripos($request->orgin_URI, 'equ') == false) && $request->orgin_URI) {    // orgin_URI is original page from where is
                        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                        $currentURL = $protocol . '://' . $_SERVER['HTTP_HOST'] . $request->orgin_URI;  // $_SERVER['REQUEST_URI'] change to orgin_URI
                        $paginatedData->setPath($currentURL);
                    }

                    if (auth()->user()) {
                        $user = auth()->user()->id;
                    } else {
                        $user = null;
                    }
                    
                    return response()->json([
                            'user' => (integer) $user,
                            'valetudinarians' => $paginatedData->items(),
                            'links' => (string) $paginatedData->links() // Render pagination links as string
                    ]);

                break;    
                //default:
                # ...
                //break;
            }

        } else {
            return view('valetudinarian',['regions'=> $regions,
                                    'cities' => $cities,
                                    //'locations'=> $locations,
                                    'parties'=> $parties, 
                                    'valetudinarians'=> $paginatedData,
                                    'drop_item_selected'=> $drop_item_selected,
                                    'layout'=> 'index']);
            //************non ajax call End*************************************************************
        }
    }

    /**
     * Paginate an array of items.
     * @param array|Collection $items
     * @param int $perPage
     * @param int|null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    /*public function paginateArray($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }*/

    /**
     * Show the form for creating a new resource from famous images.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_vale_event()       //add new vale and event (input for both at one page)
    {   
        if (session('showValetudinarianInfoModal') === null) {    
            session(['showValetudinarianInfoModal' => true]);     //set to true, not set, show once 
        } else {
            session(['showValetudinarianInfoModal' => false]);
        } 
        //$parties = Party::all()->orderBy('name', 'asc')->lists('name','id');
        $parties = Party::all(); 
        $regions = Location::distinct()->get(['region']);
        $locations = Location::all()->sortBy('name');
        $categories = Category::all();

        //$event_names = Event::get([  ]); //for linked the <input event_name> to the <datalist>
        $occupations = Valetudinarian::distinct()->get(['occupation']); //linked the <input occupation> to the <datalist>

	 	return view('vale_event_input',['parties'=> $parties,
                                    'regions'=> $regions, 
                                    'locations'=> $locations,
                                    'categories' => $categories,
                                    'occupations' => $occupations,
                                    //'event_names' => $event_names, 
                                    'layout'=>'create_vale_event']);
    }

    /**
     * Show the form for creating a new resource from famous images.
     *
     * @return \Illuminate\Http\Response
     */
    public function make_famous($id)
    {   
        //$parties = Party::all()->orderBy('name', 'asc')->lists('name','id');
        $parties = Party::all(); 
        $regions = Location::distinct()->get(['region']);
        $locations = Location::all()->sortBy('name');
        $categories = Category::all();
        $image_guess = ImageGuess::find($id);
        $occupations = Valetudinarian::distinct()->get(['occupation']); //linked the <input occupation> to the <datalist>
        //$desc = ImageGuess::find($id)->value('description');    //doesn't work with ->value('description'); part
        //$desc = ImageGuess::where('id', $id)->value('description');

	 	return view('vale_event_input',['parties'=> $parties,
                                    'regions'=> $regions, 
                                    'locations'=> $locations,
                                    'categories' => $categories,
                                    'occupations' => $occupations, 
                                    'image_guess' => $image_guess,
                                    'layout'=>'make_famous']);
    }

    public function exist_event_input(Request $request)   //add-store new record vale and event (input for both at one page)
    {
        $regions = Location::distinct()->get(['region']);
        $locations = Location::all();
        //***** ajax call ****
        if($request->ajax()) {
            switch ($request->type) {
                case 'data-event-main':
                    $categories = Category::all();
                    $events = Event::all();
                    $layout = 'choose';
                    $html = view('event_input-main', compact('categories', 'events', 'regions', 'locations', 'layout'))->render();

                    // Return as JSON (so you can access it in AJAX success)
                    return response()->json(['html' => $html]);
                    
                break;
                case 'data-event-rest':
                    $item_selected = Event::find($request->event_ID);
                    $layout = 'show';
                    $html = view('event_input-rest', compact('item_selected', 'layout'))->render();
                    // Return as JSON (so you can access it in AJAX success)
                    return response()->json(['html' => $html, 'item_selected' => $item_selected]);
                    
                break;
                case 'data-event-combined':
                    $categories = Category::all();
                    $layout = 'create_vale_event';
                    $html_main = view('event_input-main', compact('categories', 'regions', 'locations', 'layout'))->render();
                    $html_rest = view('event_input-rest', compact('layout'))->render();
                    return response()->json([
                        'html_main' => $html_main,
                        'html_rest' => $html_rest
                    ]);
            }
        }
    }

    /**
     * Newly created store_val_event to store valetudinarians, events and make famous person (if os source request for store)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_val_event(Request $request)   //add-store new record vale and event (input for both at one page)
    {     
        //dd($request->all());
        /************************************************************ Add Valetudinarian */   
            $request->validate([
                'first_name' => 'required|string',   //|unique:valetudinarians',
                'last_name' => 'required|string',
                'location_id' => 'required',
                'party_id' => 'required',
                //'date_of_birth' => [new UniquePerson],
                // custom rule:
                'date_of_birth' => [
                    function ($attribute, $value, $fail) use ($request) {
                        $exists = Valetudinarian::where('first_name', $request->first_name)
                            ->where('last_name', $request->last_name)
                            ->where('date_of_birth', $request->date_of_birth)
                            ->exists();

                        if ($exists) {
                            $fail('The combination of first name, last name, and date of birth already exist. Please, check existing data for the person you wanna add (if, the other person exist and is not the same as your person, please, add the number next to the first name. Like "Marko 2" or "Marko II")');
                        }
                    },
                ],
            ]);

            //if 'event_name' or'description' is not filled then we consider that user don't want to add event part and will store just val part.
            if (isset($request->event_id)) {
                $event_id = (int)$request->event_id;
            } elseif (($request->input('event_name') != NULL) || ($request->input('description') != NULL)) { 
                $with_new_event = $request->validate([
                    'category_id' => 'required',
                    'event_name' => 'required|unique:events',
                    'description' => 'required'/*,
                    'location_id2' => [
                        function ($attribute, $value, $fail) use ($request) {
                            $exists = Event::where('event_name', $request->event_name)
                                ->where('location_id', $request->location_id2)
                                ->exists();

                            if ($exists) {
                                $fail('The combination of event name and location already exist. Please, check existing data for the Evant you wanna add (if, the Evant person exist and is not the same one, please, add the number next to the first name. Like "Incident Skupstina 2")');
                            }
                        },
                    ],*/
                ]);
            }

            $valetudinarian = new Valetudinarian();
            $valetudinarian->first_name = $request->input('first_name');
            $valetudinarian->last_name = $request->input('last_name');
            $valetudinarian->sobriquet = $request->input('sobriquet');
            if ($request->input('date_of_birth')) {
                $valetudinarian->date_of_birth = Carbon::parse($request->input('date_of_birth'))->toDateString();
            }
            $valetudinarian->occupation = $request->input('occupation');
            $valetudinarian->position = $request->input('position');
            $valetudinarian->email = $request->input('email');
            
            if ($request->input('phone')) {
                $vale_phone = $request->input('phone');
                $vale_phone = preg_replace("/[^0-9]/", "", $vale_phone);
                $prefixToFind = "381";  //phone prefix for SRB
                if ((strlen($vale_phone) > 10) && (strpos($vale_phone, $prefixToFind) === 0)) {
                    $vale_phone = substr_replace($vale_phone, "0", 0, strlen($prefixToFind));
                } 
                $valetudinarian->phone = $vale_phone;
            }
            if ($request->input('local_id')) {
                $location_id = (int)$request->input('local_id');
            } elseif($request->input('location_id')) {
                $location_id = (int)$request->input('location_id');
            }
            if ($location_id) {
                $valetudinarian->location_id = $location_id;
            }
            $valetudinarian->party_id = (int)$request->input('party_id');
            $valetudinarian->owner_id = auth()->user()->id;
    //        $valetudinarian->save();

            /******* Start images */
            if($request->hasFile('image')) {

                //dd($request->all());
                $request->validate([
                    'image' => 'required|mimes:jpg,png,jpeg|max:9048',  // was 5048KB = 5.048MB  
                ]);
                //if(!$request->file('image')->getError()) {}      //or $request->file('image')->getError()->isvalid()

                $file_name_original_size = $valetudinarian->id . '-' . $request->image->getClientOriginalName();
                //$imagePath = $request->image->store('public/vale_img');     //under root dir, name is made by image cls
                $file_name = $valetudinarian->id . '.' . $request->image->extension();
                $request->image->move(public_path('storage/vale_images'), $file_name_original_size);     //name is made by image cls

                //*******resize file and delete original */
                $source_file = public_path('storage/vale_images') . '/' . $file_name_original_size;
                $destination_file = public_path('storage/vale_images') . '/' . $file_name;
                // $max_width = 800; $max_height = 600;  $jpeg_quality = 85;
                if ($this->resizeAndSaveImage($source_file, $destination_file)) {
                    echo "Image resized and saved successfully to " . $destination_file;
                    My_Image::create([   //you can use $car = Car::make([ insted but then you have to use $car->save(); before return redi...
                        'valetudinarian_id' => $valetudinarian->id,
                        'image_name' => $file_name
                    ]);
                } else {
                    echo "Failed to resize image.";
                }

                //*******delete original */
                if (file_exists($source_file)) { // Check if the file exists before attempting to delete
                    if (unlink($source_file)) {
                        echo "The file '{$source_file}' was successfully deleted.";
                    } else {
                        echo "Error: The file '{$source_file}' could not be deleted.";
                    }
                } else {
                    echo "Error: The file '{$source_file}' does not exist.";
                }
            }
            /******* End images */
        /************************************************************ End Add Valetudinarian */
        /************************************************************ Add Guess image */
            if ($request->input('id_guess')) {
                $image_guess = ImageGuess::find($request->input('id_guess'));
                $image_guess_name = $image_guess->image_name;
                $image_guess_extension = pathinfo($image_guess_name, PATHINFO_EXTENSION);

                if (My_image::where('valetudinarian_id', $valetudinarian->id)->exists()) {
                    //will never happen unless user just add new photo of subject, as well-othervise, the first is "guess" image and there is no any previous image of this subject.
                    $file_name = Carbon::parse(time())->format('Ymd') . '-' . $valetudinarian->id . '-' . rand(100, 999) . '.' . $image_guess_extension;
                } else {
                    $file_name = $valetudinarian->id . '.' . $image_guess_extension;
                }
    
                $source_file = public_path('storage/guess_images') . '/' . $image_guess_name;
                if (file_exists($source_file)) { // Check if the file exists before attempting to delete
                    
                    // Ensure the destination folder exists, create it if it doesn't
                    /*if (!is_dir(public_path('storage/vale_images'))) {
                        echo "Error, there is no folder to move.";
                    }*/

                    // Construct the full path for the new file
                    $destination_file = public_path('storage/vale_images') . '/' . $file_name;

                    // Attempt to rename and move the file
                    if (rename($source_file, $destination_file)) {
                        echo "Image successfully renamed and moved to: " . $destination_file;
                        My_Image::create([
                            'valetudinarian_id' => $valetudinarian->id,
                            'image_name' => $file_name
                        ]);

                        $event = ImageGuess::where('id', $request->input('id_guess'))->update([ 
                                'valetudinarian_id' => $valetudinarian->id
                        ]);
                    } else {
                        echo "Error renaming or moving the image.";
                    }
                    
                } else {
                    echo "Error: The file '{$source_file}' does not exist.";
                }
            }      

        /************************************************************ End Add Guess image */
        /************************************************************ Add Event */
        
        if (isset($with_new_event)) {

            $category_id = 1;   // Default category OTHERS/OSTALO (has to make default in table)
            if ($request->input('category_id') != NULL) { 
                $category_id = (int)$request->input('category_id');
            }

            $location_id = 1;   //only event location. If not specifed then is 1 = Serbia.
            if ($request->input('local_id2')) {
                $location_id = $request->input('local_id2');
            } elseif($request->input('location_id2')) {
                $location_id = $request->input('location_id2');
            }
            $event_date = NULL;
            if ($request->input('event_date')) {
                $event_date = Carbon::parse($request->input('event_date'))->format('Y-m-d');
            }
            $event = Event::create([                
                'event_name' => $request->input('event_name'),
                'owner_id' => auth()->user()->id,
                'event_date' => $event_date,
                'location_id' => $location_id,
                'description' => $request->input('description'),
                'category_id' => $category_id
            ]);
            $event_id = $event->id;

            /******** Start images */
            if($request->hasFile('image2')) {

                //dd($request->all());
                $request->validate([
                    'image2' => 'required|mimes:jpg,png,jpeg|max:9048',  // was 5048KB = 5.048MB  
                ]);
                //if(!$request->file('image')->getError()) {}      //or $request->file('image')->getError()->isvalid()

                $file_name_original_size = $event->id . '-' . $request->image2->getClientOriginalName();
                
                $file_name = Carbon::parse(time())->format('Ymd') . '-' . $event->id . '-' . rand(100, 999) . '.' . $request->image2->getClientOriginalExtension();

                $request->image2->move(public_path('storage/event_images'), $file_name_original_size);     //name is made by image cls

                //*******resize file and delete original */
                $source_file = public_path('storage/event_images') . '/' . $file_name_original_size;
                $destination_file = public_path('storage/event_images') . '/' . $file_name;
                // $max_width = 800; $max_height = 600;  $jpeg_quality = 85;
                if ($this->resizeAndSaveImage($source_file, $destination_file)) {
                    echo "Image resized and saved successfully to " . $destination_file;
                    ImageEvent::create([
                        'event_id' => $event->id,
                        'image_name' => $file_name
                    ]);
                } else {
                    echo "Failed to resize image.";
                }

                //*******delete original */
                if (file_exists($source_file)) { // Check if the file exists before attempting to delete
                    if (unlink($source_file)) {
                        echo "The file '{$source_file}' was successfully deleted.";
                    } else {
                        echo "Error: The file '{$source_file}' could not be deleted.";
                    }
                } else {
                    echo "Error: The file '{$source_file}' does not exist.";
                }
            }
            /******* End images */
        }

        if (isset($event_id)) {
            //$valetudinarian_id = $request->input('valetudinarian_id');
            if ($valetudinarian->id == NULL) {
                return redirect('/create-valeevent/'.$event_id);    //Here is continuation for ref tables vale_events
            } else {        //if we doing from start (whole input) from Vale-Event-ValeEvent
                ValeEvent::create([   //you can use $equ_events = EquEvent::make([ insted but then you have to use $car->save(); before return redi...
                    'event_id' => $event_id,
                    'valetudinarian_id' => $valetudinarian->id
                ]);
            }
        }
        /************************************************************ End Add Event */

        return redirect('/show/'.$valetudinarian->id);         //new vale input - all way around

    }
    /************************************************************************************************************************* End store ALL */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        if (session('showValetudinarianInfoModal') === null) {    
            session(['showValetudinarianInfoModal' => true]);     //set to true, not set, show once 
        } else {
            session(['showValetudinarianInfoModal' => false]);
        } 
        //$parties = Party::all()->orderBy('name', 'asc')->lists('name','id');
        $parties = Party::all(); 
        $regions = Location::distinct()->get(['region']);
        $cities = Location::select(['city_zip', 'city'])->distinct()->get();
        $locations = Location::all()->sortBy('name');
        $occupations = Valetudinarian::distinct()->get(['occupation']);

        /*$valetudinarians = DB::table('valetudinarians')
            ->leftjoin('parties', 'valetudinarians.party_id', '=', 'parties.id')
            ->leftjoin('locations', 'valetudinarians.location_id', '=', 'locations.id')
            ->select('valetudinarians.*', 'parties.name as party_name', 'locations.name as location_name')
            ->paginate(config('constants.PAGINATION_LIMIT'));   //->paginate(7);
            //->get();*/

        $paginated = true;
        $paginatedData = $this->dataService->getPaginatedData(NULL, $paginated, 'valetudinarians', $request, 'a.id');
        
        $allRequestParams = $request->except(['_token', '_method']);
        if (!empty($allRequestParams)) {
            //set links and items selected for: 'party_ID', 'category_ID', 'region', 'location_ID', 'search_Str'
            $UrlFiltersData = $this->dataService->fetchUrlFiltersData($paginatedData, $request);
            $paginatedData = $UrlFiltersData['paginated_data'];
            $drop_item_selected = $UrlFiltersData['drop_item_selected_filters'];
        } else {
            $drop_item_selected = null;
        }

	 	return view('valetudinarian',['valetudinarians'=> $paginatedData, 
                                    'drop_item_selected'=> $drop_item_selected,
                                    'parties'=> $parties,
                                    'regions'=> $regions,
                                    'cities' => $cities,
                                    'locations'=> $locations,
                                    'occupations' => $occupations, 
                                    'layout'=>'create']);
    }

    public function upload_image($id)
    {   
	 	return view('image_upload',['id'=> $id, 'layout'=>'image_upload', 'image_type'=>'valetudinarian']);  
    }

    public function store_image(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,png,jpeg|image|max:9048',    // was max:5048
        ]);

        //$imagePath = $request->file('image')->store('vale_images/uploads'); //in config/filesystems.php ==>>"'root' => storage_path('app/public'),"
                                                                      //then files are in ../storage/app/vale_images/uploads/"filename.xxx"
        if($request->hasFile('image')) {
            $image = $request->file('image');
            //$image_name = $request->file('image')->getClientOriginalName();
            $file_name_original_size = $request->parent_id . '-' . $image->getClientOriginalName();

            if (My_image::where('valetudinarian_id', $request->parent_id)->exists()) {
                $file_name = Carbon::parse(time())->format('Ymd') . '-' . $request->parent_id . '-' . rand(100, 999) . '.' . $image->getClientOriginalExtension();
            } else {
                $file_name = $request->parent_id . '.' . $image->getClientOriginalExtension();
            }

            //$imagePath = $request->image->store('public/storage/vale_images');     //under root dir, name is made by image cls
            //$imagePath->image->move(storage_path('/app'), $file_name);        //storage_path = approot/storage/

            $request->image->move(public_path('storage/vale_images'), $file_name_original_size);

            //*******resize file and delete original */
            $source_file = public_path('storage/vale_images') . '/' . $file_name_original_size;
            $destination_file = public_path('storage/vale_images') . '/' . $file_name;
            // $max_width = 800; $max_height = 600;  $jpeg_quality = 85;
            //if ($this->resizeAndSaveImage($source_file, $destination_file, $max_width, $max_height, $jpeg_quality)) {
            if ($this->resizeAndSaveImage($source_file, $destination_file)) {
                echo "Image resized and saved successfully to " . $destination_file;
                My_Image::create([   //you can use $car = Car::make([ insted but then you have to use $car->save(); before return redi...
                    'valetudinarian_id' => $request->parent_id,
                    'image_name' => $file_name
                ]);
            } else {
                echo "Failed to resize image.";
            }

            //*******delete original */
            if (file_exists($source_file)) { // Check if the file exists before attempting to delete
                if (unlink($source_file)) {
                    echo "The file '{$source_file}' was successfully deleted.";
                } else {
                    echo "Error: The file '{$source_file}' could not be deleted.";
                }
            } else {
                echo "Error: The file '{$source_file}' does not exist.";
            }
        }

        //return response()->json([])->with('success', 'Image uploaded successfully.');
        return redirect('/show/'.$request->parent_id)->with('success', 'Image uploaded successfully.');
    }

    /**
     * Party a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        //$validatedData = $request->validate([
        $request->validate([
            //'name' => new Uppercase,  // check field for uppercase tostrupper()
            //'first_name'    => ['required', 'string', new UniquePerson()],
            'first_name' => 'required|string',   //|unique:valetudinarians',
            'last_name' => 'required|string',
            'location_id' => 'required',
            'party_id' => 'required',
            //'date_of_birth' => [new UniquePerson],
            // custom rule:
            'date_of_birth' => [
                function ($attribute, $value, $fail) use ($request) {
                    $exists = Valetudinarian::where('first_name', $request->first_name)
                        ->where('last_name', $request->last_name)
                        ->where('date_of_birth', $request->date_of_birth)
                        ->exists();

                    if ($exists) {
                        $fail('The combination of first name, last name, and date of birth already exist. Please, check existing data for the person you wanna add (if, the other person exist and is not the same as your person, please, add the number next to the first name. Like "Marko 2" or "Marko II")');
                    }
                },
            ],
        ]);

        /*$validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'date_of_birth' => 'required|date',
            'location_id' => 'required',
            'party_id' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {
            $exists = Valetudinarian::where('first_name', $request->first_name)
                ->where('last_name', $request->last_name)
                ->where('date_of_birth', $request->date_of_birth)
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'first_name',
                    'The combination of first name, last name, and date of birth already exists.'
                );
            }
        });

        $validator->validate();*/

        $valetudinarian = new Valetudinarian();
        $valetudinarian->first_name = $request->input('first_name');
        $valetudinarian->last_name = $request->input('last_name');
        $valetudinarian->sobriquet = $request->input('sobriquet');
        //$valetudinarian->date_of_birth = Carbon::parse($request->input('date_of_birth'))->format('Y-m-d H:i:s');
        if ($request->input('date_of_birth')) {
            $valetudinarian->date_of_birth = Carbon::parse($request->input('date_of_birth'))->toDateString();
        }
        //$valetudinarian->date_of_birth = $request->input('date_of_birth');
        $valetudinarian->occupation = strtoupper($request->input('occupation'));
        $valetudinarian->position = $request->input('position');
        $valetudinarian->email = $request->input('email');
        
        if ($request->input('phone')) {
            $vale_phone = $request->input('phone');
            $vale_phone = preg_replace("/[^0-9]/", "", $vale_phone);
            $prefixToFind = "381";  //phone prefix for SRB
            if ((strlen($vale_phone) > 10) && (strpos($vale_phone, $prefixToFind) === 0)) {
                $vale_phone = substr_replace($vale_phone, "0", 0, strlen($prefixToFind));
            } 
            $valetudinarian->phone = $vale_phone;
        }
        //LU$valetudinarian->image_path = $request->input('image_path');
        if ($request->input('local_id')) {
            $location_id = (int)$request->input('local_id');
        } elseif($request->input('location_id')) {
            $location_id = (int)$request->input('location_id');
        }
        if ($location_id) {
            $valetudinarian->location_id = $location_id;
        }
        $valetudinarian->party_id = (int)$request->input('party_id');
        $valetudinarian->owner_id = auth()->user()->id;
        $valetudinarian->save();

        /*************************************************** Start images */
        if($request->hasFile('image')) {

            //dd($request->all());
            $request->validate([
                'image' => 'required|mimes:jpg,png,jpeg|max:9048',  // was 5048KB = 5.048MB  
            ]);
            //if(!$request->file('image')->getError()) {}      //or $request->file('image')->getError()->isvalid()

            $file_name_original_size = $valetudinarian->id . '-' . $request->image->getClientOriginalName();
            //$imagePath = $request->image->store('public/vale_img');     //under root dir, name is made by image cls
            $file_name = $valetudinarian->id . '.' . $request->image->extension();
            $request->image->move(public_path('storage/vale_images'), $file_name_original_size);     //name is made by image cls

            //*******resize file and delete original */
            $source_file = public_path('storage/vale_images') . '/' . $file_name_original_size;
            $destination_file = public_path('storage/vale_images') . '/' . $file_name;
            // $max_width = 800; $max_height = 600;  $jpeg_quality = 85;
            if ($this->resizeAndSaveImage($source_file, $destination_file)) {
                echo "Image resized and saved successfully to " . $destination_file;
                My_Image::create([   //you can use $car = Car::make([ insted but then you have to use $car->save(); before return redi...
                    'valetudinarian_id' => $valetudinarian->id,
                    'image_name' => $file_name
                ]);
            } else {
                echo "Failed to resize image.";
            }

            //*******delete original */
            if (file_exists($source_file)) { // Check if the file exists before attempting to delete
                if (unlink($source_file)) {
                    echo "The file '{$source_file}' was successfully deleted.";
                } else {
                    echo "Error: The file '{$source_file}' could not be deleted.";
                }
            } else {
                echo "Error: The file '{$source_file}' does not exist.";
            }
        }
        /************************************************************ End images */

        /*$car = Equipment::create([   //you can use $car = Car::make([ insted but then you have to use $car->save(); before return redi...
            'name' => $request->input('name'),
            'brand' => $request->input('brand'),
            'model' => $request->input('model'),
            'serial_number' => $request->input('serial_number'),
            'price' => $request->input('price'),
            'availability' => $request->input('availability'),
            'image_path' => $request->input('image_path')
        ]);*/

        return redirect('/create-event-vale-event/'.$valetudinarian->id); //go to EventController-create_event_valeid($id)-'param' => 1
        //return redirect('/create-event-valeevent/'.$valetudinarian->id);            //call to continue event 
        return redirect('/create');         //new vale input - all way around

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item_selected = Valetudinarian::find($id);
        $party = Party::find($item_selected->party_id);
        $location = Location::find($item_selected->location_id);
        
        $events = DB::table('events')->where('vale_events.valetudinarian_id', '=', $id)
            ->join('vale_events', 'events.id', '=', 'vale_events.event_id')
            ->leftjoin('categories', 'events.category_id', '=', 'categories.id')
            ->leftjoin('locations', 'events.location_id', '=', 'locations.id')
            ->select('events.*', 'categories.category_name', 'locations.zip', 'locations.name')
            ->get();
        //$images = Image::find($id);
        $images = DB::table('images')->where('valetudinarian_id', '=', $id)->get();
        /*$store_category = DB::table('equipments')
            ->join('stores', 'equipments.store_id', '=', 'stores.id')
            ->join('categories', 'equipments.category_id', '=', 'categories.id')
            ->select('equipments.*', 'stores.name as store_name', 'categories.name as category_name')
            ->where('equipments.id', '=', $id)
            ->get();*/
    
        //dd($valetudinarians); //LU
        //dd($valetudinarian->party); //LU
        //dd($equipment->location);
        $regions = Location::distinct()->get(['region']);
        $locations = Location::all()->sortBy('name');
        $parties = Party::all();
        $layout = 'show'; // or 'edit', depending
        
        return view('valetudinarian_show', compact('item_selected', 'party', 'location', 
                                'events', 'images', 'regions', 'locations', 'parties', 'layout'));

	 	/*return view('valetudinarian_show',['item_selected' => $item_selected, 'party' => $party, 'location' => $location, 
                                            'events' => $events, 'images' => $images, 'layout'=> $layout,
                                        'regions' => $regions, 'locations' => $locations, 'parties'=> $parties]); */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $regions = Location::distinct()->get(['region']);
        $cities = Location::select(['city_zip', 'city'])->distinct()->get();
        $locations = Location::all()->sortBy('name'); 
        $parties = Party::all(); 
        $occupations = Valetudinarian::distinct()->get(['occupation']);

        $item_selected = Valetudinarian::find($id);
        
        /*$valetudinarians = DB::table('valetudinarians')
            ->leftjoin('parties', 'valetudinarians.party_id', '=', 'parties.id')
            ->leftjoin('locations', 'valetudinarians.location_id', '=', 'locations.id')
            ->select('valetudinarians.*', 'parties.name as party_name', 'locations.name as location_name')
            ->paginate(config('constants.PAGINATION_LIMIT'));   //->paginate(7);
            //->get();*/

        $paginated = true;
        $paginatedData = $this->dataService->getPaginatedData(NULL, $paginated, 'valetudinarians', $request, 'a.id');
        
        $allRequestParams = $request->except(['_token', '_method']);
        if (!empty($allRequestParams)) {
            //set links and items selected for: 'party_ID', 'category_ID', 'region', 'location_ID', 'search_Str'
            $UrlFiltersData = $this->dataService->fetchUrlFiltersData($paginatedData, $request);
            $paginatedData = $UrlFiltersData['paginated_data'];
            $drop_item_selected = $UrlFiltersData['drop_item_selected_filters'];
        } else {
            $drop_item_selected = null;
        }
            
	 	return view('valetudinarian',['valetudinarians'=> $paginatedData,
                                    'drop_item_selected'=> $drop_item_selected,
                                    'item_selected'=> $item_selected, 
                                    'parties'=> $parties,
                                    'regions'=> $regions,
                                    'cities' => $cities,
                                    'locations'=> $locations,
                                    'occupations' => $occupations,
                                    'layout'=>'edit']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name'    => ['required', 'string', new UniquePerson($id)],
            //'first_name' => 'required',
            'last_name' => 'required|string',
            'location_id' => 'required',
            'party_id' => 'required',
        ]);

        $valetudinarian = Valetudinarian::find($id);
        
        if ($request->filled('first_name') && ($valetudinarian->first_name !== $request->input('first_name'))) {
            $valetudinarian->first_name = $request->input('first_name');
        }
        if ($request->filled('last_name') && ($valetudinarian->last_name !== $request->input('last_name'))) {
            $valetudinarian->last_name = $request->input('last_name');
        }
        if ($request->filled('sobriquet') && ($valetudinarian->sobriquet !== $request->input('sobriquet'))) {
            $valetudinarian->sobriquet = $request->input('sobriquet');
        }
        if ($request->filled('date_of_birth') && ($valetudinarian->date_of_birth !== $request->input('date_of_birth'))) {
            $valetudinarian->date_of_birth = Carbon::parse($request->input('date_of_birth'))->toDateString();
        }
        if ($request->filled('occupation') && ($valetudinarian->occupation !== strtoupper($request->input('occupation')))) {
            $valetudinarian->occupation = strtoupper($request->input('occupation'));
        }
        if ($request->filled('position') && ($valetudinarian->position !== $request->input('position'))) {
            $valetudinarian->position = $request->input('position');
        }
        if ($request->filled('email') && ($valetudinarian->email !== $request->input('email'))) {
            $valetudinarian->email = $request->input('email');
        }

        if ($request->filled('phone') && ($valetudinarian->phone !== $request->input('phone')) && (strpos($request->input('phone'), '***') === false)) {
            $vale_phone = $request->input('phone');
            $vale_phone = preg_replace("/[^0-9]/", "", $vale_phone);
            $prefixToFind = "381";  //phone prefix for SRB
            if ((strlen($vale_phone) > 10) && (strpos($vale_phone, $prefixToFind) === 0)) {
                $vale_phone = substr_replace($vale_phone, "0", 0, strlen($prefixToFind));
            }
            if ($valetudinarian->phone !== $vale_phone) {
                $valetudinarian->phone = $vale_phone;
            }
        }
        
        if ($request->input('local_id')) {
            $location_id = (int)$request->input('local_id');
        } elseif($request->input('location_id')) {
            $location_id = (int)$request->input('location_id');
        }
        if ($location_id && ($valetudinarian->location_id !== $location_id)) {
            $valetudinarian->location_id = $location_id;
        }
        
        if ($request->filled('party_id') && ($valetudinarian->party_id !== (int)$request->input('party_id'))) {
            $valetudinarian->party_id = (int)$request->input('party_id');
        }
        $valetudinarian->save();

        $currentPage = $request->get('page', 1);
        return redirect('/equ?page='.$currentPage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function destroy(Equipment $equipment)
    public function destroy($id)
    {
        $valetudinarian = Valetudinarian::find($id);
        $valetudinarian->delete();
        return redirect('/equ');
    }

    public function store_correction(Request $request)
    {        
        //No real Validations requied for the correction or additional data
        $request->validate([
            'valetudinarian_id' => 'required',
        ]);

        $item_selected = Valetudinarian::find($request->input('valetudinarian_id'));
        
        $vale_corr = new Correction();
        if ($request->filled('first_name') && ($item_selected->first_name !== $request->input('first_name'))) {
            $vale_corr->first_name = $request->input('first_name');
        }
        if ($request->filled('last_name') && ($item_selected->last_name !== $request->input('last_name'))) {
            $vale_corr->last_name = $request->input('last_name');
        }
        if ($request->filled('sobriquet') && ($item_selected->sobriquet !== $request->input('sobriquet'))) {
            $vale_corr->sobriquet = $request->input('sobriquet');
        }
        if ($request->filled('date_of_birth') && ($item_selected->date_of_birth !== $request->input('date_of_birth'))) {
            $vale_corr->date_of_birth = Carbon::parse($request->input('date_of_birth'))->toDateString();
        }
        if ($request->filled('occupation') && ($item_selected->occupation !== strtoupper($request->input('occupation')))) {
            $vale_corr->occupation = strtoupper($request->input('occupation'));
        }
        if ($request->filled('position') && ($item_selected->position !== $request->input('position'))) {
            $vale_corr->position = $request->input('position');
        }
        if ($request->filled('email') && ($item_selected->email !== $request->input('email'))) {
            $vale_corr->email = $request->input('email');
        }
        if ($request->filled('phone') && ($item_selected->phone !== $request->input('phone')) && (strpos($request->input('phone'), '***') === false)) {
            $vale_phone = $request->input('phone');
            $vale_phone = preg_replace("/[^0-9]/", "", $vale_phone);
            $prefixToFind = "381";  //phone prefix for SRB
            if ((strlen($vale_phone) > 10) && (strpos($vale_phone, $prefixToFind) === 0)) {
                $vale_phone = substr_replace($vale_phone, "0", 0, strlen($prefixToFind));
            }
            if ($item_selected->phone !== $vale_phone) {
                $vale_corr->phone = $vale_phone;
            }
        }
        if ($request->input('local_id')) {
            $location_id = (int)$request->input('local_id');
        } elseif($request->input('location_id')) {
            $location_id = (int)$request->input('location_id');
        }
        if ($location_id && ($item_selected->location_id !== $location_id)) {
            $vale_corr->location_id = $location_id;
        }
        if ($request->filled('party_id') && ($item_selected->party_id !== (int)$request->input('party_id'))) {
            $vale_corr->party_id = (int)$request->input('party_id');
        }
        if ($vale_corr->isDirty()) {
            $vale_corr->valetudinarian_id = (int)$request->input('valetudinarian_id');
            if ($request->filled('comment')) {
                $vale_corr->comment = $request->input('comment');
            }
            $vale_corr->owner_id = auth()->user()->id;
            $vale_corr->save();
            return redirect()->back()->with('success', 'Action completed successfully!');
        } else {
            //return redirect()->back()->withErrors(['msg' => 'There is no new data or corrections!']);
            return redirect()->back()->with('error', 'Data are same! There is no new data or corrections!');
        }
    }

    /*public function post_ajax_data(Request $request)
    {
        switch ($request->type) {
            case 'data-for-correction':
                if($request->ajax()) {  

                    $regions = Location::distinct()->get(['region']);
                    //$cities = Location::select(['city_zip', 'city'])->distinct()->get();
                    $locations = Location::all()->sortBy('name');

                    $item_selected = Valetudinarian::find($request->ID);
                    // If needed, pass any other contextual variables
                    if($item_selected->location_id) {
                        $layout = 'edit'; 
                    } else {
                        $layout = 'create';
                    }

                    $html = view('location_list-template', compact('regions', 'locations', 'layout', 'item_selected'))->render();

                    // Return as JSON (so you can access it in AJAX success)
                    return response()->json(['html' => $html]);
                   
                } 
            break;
             
            default:
             # ...
            break;
        }
    }*/

    public function valeventEvents(Request $request)
    {
        switch ($request->type) {
            case 'search':  //obsolete
                if($request->ajax()) { 
                // Search in the title and body columns from the posts table
                    if ($request->search_Str) {
                        $search = $request->search_Str;
                        $valetudinarians = Valetudinarian::query()
                        ->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('occupation', 'LIKE', "%{$search}%")
                        ->orWhere('position', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->get();

                        //$equipments->orderBy('id', 'DESC')->paginate(10);
                    }

                    /*$equipments = Equipment::query();
                    if ($search) {
                        $equipments->where('name', 'Like', '%' . $search . '%');
                    }*/
            
                return response()->json($valetudinarians);
                }
            break;

            case 'select-region-list':
 
                if($request->ajax()) { 

                    $region = $request->value_ID;
                    if ($region) {
                        //$regions = Location::distinct()->get(['region']);
                        //$regions = Location::all()->unique('region');
                        $locations = Location::select(['city_zip as id', 'city_zip as zip', 'city as name'])
                        //$locations = Location::select(['id', 'city_zip', 'city', 'zip', 'name'])
                        ->where('region', '=', $region)
                        ->distinct()->get();
                    } else{
                        $locations = Location::get(['id', 'zip', 'name']);
                        //$locations = Location::get(['id', 'city_zip', 'city']);
                    }
                    
                    return response()->json($locations);
                } 
            break;

            case 'select-local-list':
                if($request->ajax()) { 

                    $location = Location::find($request->value_ID);
                    if ($location->city_zip) {
                        //$regions = Location::distinct()->get(['region']);
                        //$regions = Location::all()->unique('region');
                        $locations = Location::select(['id', 'zip', 'name'])
                        //$locations = Location::select(['id', 'city_zip', 'city', 'zip', 'name'])
                        ->where('city_zip', '=', $location->city_zip)
                        ->get();
                    } else{
                        $locations = Location::get(['id', 'zip', 'name']);
                        //$locations = Location::get(['id', 'city_zip', 'city']);
                    }
                    
                    return response()->json($locations);
                } 
            break;
             
            default:
             # ...
            break;
        }
    }

    public function resizeAndSaveImage($source_file, $destination_path, $max_width = 800, $max_height = 600, $quality = 85) 
    {
        // $max_width = 800; $max_height = 600;  $jpeg_quality = 85;
        // Get the image data from the URL
        $image_data = file_get_contents($source_file);
        if ($image_data === false) {
            return false; // Failed to download image
        }

        // Create a GD image resource from the downloaded data
        $source_image = imagecreatefromstring($image_data);
        if ($source_image === false) {
            return false; // Invalid image data
        }

        // Get original image dimensions
        $orig_width = imagesx($source_image);
        $orig_height = imagesy($source_image);

        // Calculate the new dimensions while preserving the aspect ratio
        $width_ratio = $max_width / $orig_width;
        $height_ratio = $max_height / $orig_height;
        
        $scale_factor = min($width_ratio, $height_ratio);
        
        $new_width = floor($scale_factor * $orig_width);
        $new_height = floor($scale_factor * $orig_height);

        // Create a new true-color image with the new dimensions
        $new_image = imagecreatetruecolor($new_width, $new_height);

        // Resample the image to the new size for high-quality resizing
        imagecopyresampled($new_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);

        // Save the resized image with specified quality
        $extension = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($new_image, $destination_path, $quality);
                break;
            case 'png':
                // PNG quality is 0-9; scale 1-100 to 0-9
                $png_quality = floor(($quality / 100) * 9);
                imagepng($new_image, $destination_path, $png_quality);
                break;
            case 'gif':
                imagegif($new_image, $destination_path);
                break;
            default:
                imagedestroy($source_image);
                imagedestroy($new_image);
                return false;
        }

        // Clean up memory
        imagedestroy($source_image);
        imagedestroy($new_image);

        return true;
    }

    /**
     * Clean some param from _GET.
     * Not in use at the moment
     */
    public function cleanParamsArray()
    {
        $queryParamsArray = $_GET;
        //$queryParamsArray = $request->query();
        if (!empty($paramsArray)) {
            $parameterNames = array_keys($queryParamsArray);
            foreach ($parameterNames as $name) {
                if (strpos($name, "amp;") !== false) {
                    unset($_GET[$name]);    //example: unset($queryParamsArray['location_ID']);
                }
            }
        }
        //$queryParamsArray = $_GET;
    }
    
}
