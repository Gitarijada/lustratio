<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Valetudinarian;
use App\Models\ValeEvent;
use App\Models\Location;
use App\Models\Party;
use App\Models\Category;
use App\Models\ImageEvent;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

//use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\DataService;

class EventController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService)
    {
        $this->middleware(['auth', 'verified'], ['except' => ['index', 'show']]);    // HERE what ya are allowed to access if not loged in
        $this->dataService = $dataService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*if (session('showInfoModal')) {
            $showModal = false;
        } elseif (session('showInfoModal') === null) {    
            session(['showInfoModal' => true]);     //set to true, not set, show once
            $showModal = session('showInfoModal');  
        }*/

        if (session('showEventInfoModal') === null) {    
            session(['showEventInfoModal' => true]);     //set to true, not set, show once 
        } else {
            session(['showEventInfoModal' => false]);
        }
        //session(['showEventInfoModal' => true]);   // for test purposes-always true

        //$showTemp = session('showInfoModal');
        //$showModal = session()->pull('showInfoModal', true); // show once and delete showInfoModal

        //************non & ajax call*****************************************************************
        $regions = Location::distinct()->get(['region']);
        if ($request->region) {
            $cities = Location::select(['city_zip', 'city'])
            ->where('region', '=', $request->region)
            ->distinct()->get();
        } else {
            $cities = Location::select(['city_zip', 'city'])->distinct()->get();
        }
        $categories = Category::all();

        $paginated = true;
        $paginatedData = $this->dataService->getPaginatedData(NULL, $paginated, 'events', $request); // get DataSet and paginatedData
        //$paginatedData = $this->dataService->getPaginatedData($sql, $paginated, $source = 'events', $request, 'a.category_id', 'ASC');
        //$this->cleanParamsArray(); //clean from $_GET parametes

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
                    $paginatedData->setPath('/lustratio/public/list-event');
                    $events = $paginatedData;
                    $layout = 'index';
                    $html = view('eventslist', compact('regions', 'cities', 'categories', 
                                                                'events', 'drop_item_selected', 'layout'))->render();
                    // Return as JSON (so you can access it in AJAX success)
                    return response()->json(['html' => $html]);
                    
                break;
                case 'filter-js':
                    $paginatedData->appends(request()->except('page'))->links(); //By using this, the generated pagination links will only include the 'page' parameter and any other parameters that were explicitly not excluded by except().

                    if ((stripos($request->orgin_URI, 'list-event') == false) && $request->orgin_URI) {    // orgin_URI is original page from where is
                        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                        $currentURL = $protocol . '://' . $_SERVER['HTTP_HOST'] . $request->orgin_URI;  // $_SERVER['REQUEST_URI'] change to orgin_URI
                        $paginatedData->setPath($currentURL);
                    }

                    if (auth()->user()) {
                        $user = auth()->user()->id;
                    } else {
                        $user = null;
                    }
                    
                    //return response()->json($events);
                    return response()->json([
                            'user' => (integer) $user,
                            'events' => $paginatedData->items(),
                            'links' => (string) $paginatedData->links() // Render pagination links as string
                    ]);

                break;    
                //default:
                # ...
                //break;
            }

        } else {
            return view('event',['regions'=> $regions,
                                    'cities' => $cities,
                                    //'locations'=> $locations,
                                    'categories'=> $categories,
                                    'events'=> $paginatedData,
                                    'drop_item_selected'=> $drop_item_selected,
                                    'layout'=> 'index']);
            //************non ajax call End*************************************************************
        }
    }

    /**
     * Clean some param from _GET.
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        if (session('showEventInfoModal') === null) {    
            session(['showEventInfoModal' => true]);     //set to true, not set, show once 
        } else {
            session(['showEventInfoModal' => false]);
        }
        /*$events = DB::table('events')->get(); 
                $events = DB::select("SELECT a.*, d.name as location_name, b.count_valID, c.first_name, c.last_name, e.category_name FROM events a 
            LEFT JOIN (SELECT MIN(valetudinarian_id) as valetudinarian_id, count(valetudinarian_id) as count_valID, event_id FROM vale_events GROUP BY event_id) b ON (a.id = b.event_id) 
            LEFT JOIN valetudinarians c ON b.valetudinarian_id = c.id
            LEFT JOIN locations d ON a.location_id = d.id
            LEFT JOIN categories e ON a.category_id = e.id
            "); */

        $regions = Location::distinct()->get(['region']);
        $cities = Location::select(['city_zip', 'city'])->distinct()->get();
        $locations = Location::all()->sortBy('name');
        $categories = Category::all();

        $paginated = true;
        $paginatedData = $this->dataService->getPaginatedData(NULL, $paginated, 'events', $request); // get DataSet and paginatedData
        //$paginatedData = $this->dataService->getPaginatedData(NULL, $paginated, 'events', $request, 'a.id', 'ASC');

        $allRequestParams = $request->except(['_token', '_method']);
        if (!empty($allRequestParams)) {
            //set links and items selected for: 'party_ID', 'category_ID', 'region', 'location_ID', 'search_Str'
            $UrlFiltersData = $this->dataService->fetchUrlFiltersData($paginatedData, $request);
            $paginatedData = $UrlFiltersData['paginated_data'];
            $drop_item_selected = $UrlFiltersData['drop_item_selected_filters'];
        } else {
            $drop_item_selected = null;
        }

	 	return view('event',['regions'=> $regions,
                            'cities' => $cities, 
                            'locations'=> $locations, 
                            'categories'=> $categories, 
                            'events'=> $paginatedData,
                            'drop_item_selected'=> $drop_item_selected, 
                            'layout'=>'create']);
    }

    public function upload_image($id)
    {   
	 	return view('image_upload',['id'=> $id, 'layout'=>'image_upload', 'image_type'=>'event']);  
    }

    public function store_image(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,png,jpeg|image|max:9048',    // was max:5048
        ]);

        //$imagePath = $request->file('image')->store('event_images/uploads'); //in config/filesystems.php ==>>"'root' => storage_path('app/public'),"
                                                                      //then files are in ../storage/app/event_images/uploads/"filename.xxx"
        if($request->hasFile('image')) {
            $image = $request->file('image');
            //$image_name = $request->file('image')->getClientOriginalName();
            $file_name_original_size = $request->parent_id . '-' . $image->getClientOriginalName();

            $file_name = Carbon::parse(time())->format('Ymd') . '-' . $request->parent_id . '-' . rand(100, 999) . '.' . $image->getClientOriginalExtension();

            $request->image->move(public_path('storage/event_images'), $file_name_original_size);

            //*******resize file and delete original */
            $source_file = public_path('storage/event_images') . '/' . $file_name_original_size;
            $destination_file = public_path('storage/event_images') . '/' . $file_name;
            // $max_width = 800; $max_height = 600;  $jpeg_quality = 85;
            //if ($this->resizeAndSaveImage($source_file, $destination_file, $max_width, $max_height, $jpeg_quality)) {
            if ($this->resizeAndSaveImage($source_file, $destination_file)) {
                echo "Image resized and saved successfully to " . $destination_file;
                ImageEvent::create([   //you can use $car = Car::make([ insted but then you have to use $car->save(); before return redi...
                    'event_id' => $request->parent_id,
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
        return redirect('/show-valeevent/'.$request->parent_id)->with('success', 'Image uploaded successfully.');
    }

    public function create_event_valeid($id)    //after new vale input it's go on new event input
    {   
        //return redirect('/create-event');
        //$valetudinarians = Valetudinarian::find($id);
        //$events = Event::all();
 
        /*$valetudinarians = DB::select("SELECT DISTINCT a.*, c.name AS party_name, d.name AS location_name,
         0 as used_by_other 
        FROM valetudinarians a 
        LEFT JOIN (parties c, locations d) ON (a.party_id = c.id AND a.location_id = d.id)
        WHERE a.id = '$id'");*/

        // VALELIST_CHECK.BLADE, is used only here, and will always have one record ($id), new $id created before, and here is to get event (new or existed)
        /* DELETED $valetudinarians = DB::table('valetudinarians')->where('valetudinarians.id', '=', $id)
            ->leftjoin('parties', 'valetudinarians.party_id', '=', 'parties.id')
            ->leftjoin('locations', 'valetudinarians.location_id', '=', 'locations.id')
            ->select('valetudinarians.*', 'parties.name as party_name', 'locations.name as location_name', DB::raw("'0' as used_by_other"))
            ->get();*/

                    
        $valetudinarian = Valetudinarian::with(['party', 'location'])
            ->select('*') // Selects valetudinarians.*
            ->selectRaw("'0' as used_by_other")
            ->find($id);

        $categories = Category::all();
        $parties = Party::all();
        $regions = Location::distinct()->get(['region']);
        $locations = Location::all()->sortBy('name'); 
	 	return view('event',['parties'=> $parties,
                                    'regions'=> $regions,  
                                    'locations' => $locations,
                                    'categories'=> $categories, 
                                    //'valetudinarians'=> $valetudinarians,
                                    'valetudinarian'=> $valetudinarian, 
                                    //'events' => $events,
                                    'layout'=>'create/att_vale_event']);  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->event_id)) {
            $event_id = (int)$request->event_id;
        } else {
            $with_new_event = $request->validate([
                'category_id' => 'required',
                'event_name' => 'required|unique:events|max:150',
                'description' => 'required'
                /*'location_id' => [
                    function ($attribute, $value, $fail) use ($request) {
                        $exists = Event::where('event_name', $request->event_name)
                            ->where('location_id', $request->location_id)
                            ->exists();

                        if ($exists) {
                            $fail('The combination of event name and location already exist. Please, check existing data for the Evant you wanna add (if, the Evant person exist and is not the same one, please, add the number next to the first name. Like "Incident Skupstina 2")');
                        }
                    },
                ],*/
                //LU'event_date'  =>  'required|date_format:Y-m-d H:i:s'
                //LU'event_end' => 'required|date_format:Y-m-d H:i:s|after:event_start',
            ]);
        }

        if (isset($with_new_event)) {
            $category_id = 1;   // Default category OTHERS/OSTALO (has to make default in table)
            if ($request->input('category_id') != NULL) { 
                $category_id = (int)$request->input('category_id');
            }

            $location_id = 1;   // Default, only event location. If not specifed then is 1 = Serbia.
            if ($request->input('local_id')) {
                $location_id = (int)$request->input('local_id');
            } elseif($request->input('location_id')) {
                $location_id = (int)$request->input('location_id');
            }

            $event_data = [
                'event_name' => $request->input('event_name'),
                'owner_id' => Auth::user()->id,
                'category_id' => $category_id,
                'location_id' => $location_id,
                'description' => $request->input('description'),
            ];

            if ($request->filled('event_date')) {
                $event_data['event_date'] = Carbon::parse($request->input('event_date'))->format('Y-m-d');
                $event_data['precision_date'] = $request->input('precision_date');
            }

            $event = Event::create($event_data);

            /********************************** Start images */
            if($request->hasFile('image')) {

                //dd($request->all());
                $request->validate([
                    'image' => 'required|mimes:jpg,png,jpeg|max:9048',  // was 5048KB = 5.048MB  
                ]);
                //if(!$request->file('image')->getError()) {}      //or $request->file('image')->getError()->isvalid()

                $file_name_original_size = $event->id . '-' . $request->image->getClientOriginalName();
                
                $file_name = Carbon::parse(time())->format('Ymd') . '-' . $event->id . '-' . rand(100, 999) . '.' . $request->image->getClientOriginalExtension();

                $request->image->move(public_path('storage/event_images'), $file_name_original_size);     //name is made by image cls

                //*******resize file and delete original */
                $source_file = public_path('storage/event_images') . '/' . $file_name_original_size;
                $destination_file = public_path('storage/event_images') . '/' . $file_name;
                // $max_width = 800; $max_height = 600;  $jpeg_quality = 85;
                if ($this->resizeAndSaveImage($source_file, $destination_file)) {
                    echo "Image resized and saved successfully to " . $destination_file;
                    ImageEvent::create([   //you can use $car = Car::make([ insted but then you have to use $car->save(); before return redi...
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
        }
        /********************************************************** End images */
        if (isset($event->id)) {
            $event_id = $event->id;
        }

        if ($request->filled('valetudinarian_id')) {    //in a case 'create/att_vale_event' (NOT 'create')
            $valetudinarian_id = $request->input('valetudinarian_id');

            $val_event_data = [
                'event_id' => $event_id,
                'valetudinarian_id' => $valetudinarian_id,
                'owner_id' => auth()->user()->id,
            ];

            if ($request->filled('vev_description')) {
                $val_event_data['vev_description'] = $request->input('vev_description');
            }

            ValeEvent::create($val_event_data);

            //return redirect('/create');         //came to new Vale input --- new vale input - all way around
            return redirect('/show/'.$valetudinarian_id);
        } else {
            return redirect('/create-valeevent/'.$event_id);    //Go and find $valetudinarian for this @event (id) Here is continuation for ref tables vale_events
        } 

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        //change to ValeEventController 'show'
    }*/

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
        $categories = Category::all();
        $item_selected = Event::find($id);
        /*$events = DB::select("SELECT a.*, d.name as location_name, b.count_valID, c.first_name, c.last_name, e.category_name FROM events a 
            LEFT JOIN (SELECT MIN(valetudinarian_id) as valetudinarian_id, count(valetudinarian_id) as count_valID, event_id FROM vale_events GROUP BY event_id) b ON (a.id = b.event_id) 
            LEFT JOIN valetudinarians c ON b.valetudinarian_id = c.id
            LEFT JOIN locations d ON a.location_id = d.id
            LEFT JOIN categories e ON a.category_id = e.id
            ");*/

        $paginated = true;
        $paginatedData = $this->dataService->getPaginatedData(NULL, $paginated, 'events', $request); // get DataSet and paginatedData
        //$paginatedData = $this->dataService->getPaginatedData(NULL, $paginated, 'events', $request, 'a.id', 'ASC');

        $allRequestParams = $request->except(['_token', '_method']);
        if (!empty($allRequestParams)) {
            //set links and items selected for: 'party_ID', 'category_ID', 'region', 'location_ID', 'search_Str'
            $UrlFiltersData = $this->dataService->fetchUrlFiltersData($paginatedData, $request);
            $paginatedData = $UrlFiltersData['paginated_data'];
            $drop_item_selected = $UrlFiltersData['drop_item_selected_filters'];
        } else {
            $drop_item_selected = null;
        }

	 	return view('event',['regions'=> $regions, 
                                'cities' => $cities,
                                'locations'=> $locations,
                                'categories'=> $categories, 
                                'events'=> $paginatedData,
                                'drop_item_selected'=> $drop_item_selected,
                                'item_selected'=> $item_selected, 
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
            //'event_name' => 'required|unique:events',
            'category_id' => 'required',
            'event_name' => 'required|max:150',
            'description' => 'required'
            //'event_start'  =>  'required|date_format:Y-m-d H:i:s',
            //'event_end' => 'required|date_format:Y-m-d H:i:s|after:event_start',
        ]);

        $location_id = 1;   //only event location. If not specifed then is 1 = Serbia.
        if ($request->input('local_id')) {
            $location_id = (int)$request->input('local_id');
        } elseif($request->input('location_id')) {
            $location_id = (int)$request->input('location_id');
        }
        $event_date = NULL;
        $precision_date = NULL;
        if ($request->input('event_date')) {
            $event_date = Carbon::parse($request->input('event_date'))->format('Y-m-d');
            $precision_date = $request->input('precision_date');
        }
        $event = Event::where('id', $id)->update([ 
                //'owner_id' => Auth::user()->id,  
                'category_id' => (int)$request->input('category_id'),
                'event_name' => $request->input('event_name'),
                'event_date' => $event_date,
                'precision_date' => $precision_date,
                //'event_date' => $request->input('event_date'),
                'location_id' => $location_id,
                'description' => $request->input('description')
        ]);
        
        $currentPage = $request->get('page', 1);
        return redirect('/list-event?page='.$currentPage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();
        return redirect()->back();  
    }

    public function valeeventEvents(Request $request)
    {
 
        switch ($request->type) {
            case 'search':
                if($request->ajax()) { 
                // Search in the title and body columns from the posts table
                    if ($request ->search_Str) {
                        $search = $request->search_Str;
                        $events = Event::query()
                        ->where('event_name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%")
                        ->get();
                    }
            
                return response()->json($events);
                }
            break;

            case 'drop-box':
                if($request->ajax()) { 
                    if ($request ->event_ID) {
                        $vale = DB::table('valetudinarians')
                            ->join('vale_events', 'valetudinarians.id', '=', 'vale_events.valetudinarian_id')
                            ->where('vale_events.event_id', '=', $request ->event_ID)
                            ->select('valetudinarians.id', 'valetudinarians.first_name', 'valetudinarians.last_name')
                            ->orderBy('id', 'asc')
                            ->get();
                    }

                return response()->json($vale);
                }
            break;
             
            default:
             # ...
            break;
        }
    }

    public function resizeAndSaveImage($source_file, $destination_path, $max_width = 1000, $max_height = 750, $quality = 90) {
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

}
