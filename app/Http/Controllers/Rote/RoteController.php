<?php

namespace App\Http\Controllers\Rote;
use App\Http\Controllers\Controller;

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
use Illuminate\Pagination\LengthAwarePaginator;
//use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class RoteController extends Controller
{
    public function __construct()
    {
        //$this->middleware('guest');
        //$this->middleware ('admin', ['except' => ['index', 'show', 'valeventEvents']]);   //new role add in middleware, not in users table, set id 1 to be admin
        $this->middleware('auth', ['except' => ['index', 'show', 'valeventEvents']]);    // HERE what ya are allowed to access if not loged in
        //$this->middleware('signed', ['except' => ['index', 'show', 'valeventEvents']])->only('verify');
    }

    public $my_PAGINATION_LIMIT;

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
        //config('constants.ROTE_PAGINATION_LIMIT');

        if (!isset($this->my_PAGINATION_LIMIT)) {
            if (session('my_dynamic_perpage_variable')) {
                $this->my_PAGINATION_LIMIT = session('my_dynamic_perpage_variable');
            } else {
                $this->my_PAGINATION_LIMIT = config('constants.ROTE_PAGINATION_LIMIT');
            }
        }

        if($request->ajax()) { 

            /** ROTE code to change PAGINATION_LIMIT with enter in search_Str field "perpage" and desired PAGINATION_LIMIT */
            if($request->type == 'page-value'){
                // To set a value in the session
                session(['my_dynamic_perpage_variable' => $request->per_Page]);
                        // or
                        // use Illuminate\Support\Facades\Session;
                        // Session::put('my_variable', 'some_data');

                // To retrieve a value from the session
                $this->my_PAGINATION_LIMIT = session('my_dynamic_perpage_variable');    // config('constants.ROTE_PAGINATION_LIMIT');
                        // or
                        // use Illuminate\Support\Facades\Session;
                        // $value = Session::get('my_variable');
            }

$startTime = microtime(true);
            /*$sql = "SELECT DISTINCT a.*, c.name AS party_name, d.name AS location_name
                        FROM valetudinarians a 
                        LEFT JOIN (parties c, locations d) ON (a.party_id = c.id AND a.location_id = d.id)";*/
             
            $sql = "SELECT * FROM valetudinarians";
            
            $where = array();
            if ($request->party_ID) {
                $where[] = "party_id = $request->party_ID"; 
            }
            if ($request->location_ID) {
                $where[] = "d.city_zip = $request->location_ID"; 
            } elseif ($request->region) {
                $where[] = "d.region = '$request->region'";
            }
            if ($request->search_Str) {
                $where[] = "(first_name LIKE '%$request->search_Str%' OR
                        last_name LIKE '%$request->search_Str%' OR
                        occupation LIKE '%$request->search_Str%' OR
                        position LIKE '%$request->search_Str%' OR 
                        email LIKE '%$request->search_Str%')";
            }

            //$where_length = count($where);
            foreach ($where as $key => $item) {
                //$key = key($where);
                if ($key == 0) {
                    $sql .= " WHERE ";
                } else {
                    $sql .= " AND ";
                }
                $sql .= $item;
            }                    
                
            $sql .= " ORDER BY id ASC"; // a.id if $sql demand !!!

            $valetudinarians = DB::select($sql); 
            //$valetudinarians = $this->paginateArray($valetudinarians, 7); // Paginate with 7 items per page;
            //$valetudinarians = compact('valetudinarians');
            $perPage = $this->my_PAGINATION_LIMIT;
            $currentPage = $request->get('page', 1);

            $currentItems = array_slice($valetudinarians, ($currentPage - 1) * $perPage, $perPage);

            $paginatedData = new LengthAwarePaginator(
                $currentItems,
                count($valetudinarians),
                $perPage,
                $currentPage,
                ['path' => request()->url()]
            );

            $paginatedData = $this->getColumnData($paginatedData);
$endTime = microtime(true);
$duration = round(($endTime - $startTime) * 1000, 2);
Log::info("-Rote (Ajax-index) Transaction completed in {$duration}ms");        
            $paginatedData->appends(request()->except('page'))->links(); //By using this, the generated pagination links will only include the 'page' parameter and any other parameters that were explicitly not excluded by except().
            if ($request->party_ID) {
                $paginatedData->appends(['party_ID' => $request->party_ID])->links();
            }
            if ($request->region) {
                $paginatedData->appends(['region' => $request->region])->links();
            }
            if ($request->location_ID) {
                $paginatedData->appends(['location_ID' => $request->location_ID])->links();
            }
            if ($request->search_Str) {
                $paginatedData->appends(['search_Str' => $request->search_Str])->links();
            }

            if ((stripos($request->orgin_URI, 'equ') == false) && $request->orgin_URI) {    // orgin_URI is original page from where is
                $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                $currentURL = $protocol . '://' . $_SERVER['HTTP_HOST'] . $request->orgin_URI;  // $_SERVER['REQUEST_URI'] change to orgin_URI
                $paginatedData->setPath($currentURL);
            }

            // Append all current request parameters
            //$paginatedData->appends(request()->input());
            //$paginatedData->withQueryString();  //will intelligently handle the query parameters, preventing duplicates

            if (auth()->user()) {
                $user = auth()->user()->id;
                //array_unshift($valetudinarians, $user);   //if array; place $user at first position
                //$paginatedData->prepend($user);  //this adding +1 to $currentItems
            } else {
                $user = null;
            }
             //get Auth::user()->id to pass to ajax call
            //$valetudinarians->push($user);   //place $user at last position
            //$valetudinarians->prepend($user);   //if collection; place $uset at first position
            
            //if ($request->type == 'filter') {
                //return response()->json($valetudinarians);
                return response()->json([
                    'user' => (integer) $user,
                    'valetudinarians' => $paginatedData->items(),
                    'links' => (string) $paginatedData->links() // Render pagination links as string
                ]);
            //}
            /*if ($request->type == 'filter') {
                return response()->json($equipments);
            } elseif ($request->type == 'select-event') {
                return response()->json(array($crud_event,$equipments));
            }*/
        }

        //************non ajax call*****************************************************************
        $regions = Location::distinct()->get(['region']);
        //$regions = Location::all()->unique('region');
        if ($request->region) {
            $locations = Location::select(['city_zip as id', 'city_zip as zip', 'city as name'])
            ->where('region', '=', $request->region)
            ->distinct()->get();
        } else {
            $locations = Location::all();
        }
        $parties = Party::all();

        /*$valetudinarians = DB::table('valetudinarians')
            ->leftjoin('parties', 'valetudinarians.party_id', '=', 'parties.id')
            ->leftjoin('locations', 'valetudinarians.location_id', '=', 'locations.id')
            //->leftjoin('images', 'valetudinarians.id', '=', 'images.valetudinarian_id')
            ->select('valetudinarians.*', 'parties.name as party_name', 'locations.name as location_name')
            ->paginate(config('constants.ROTE_PAGINATION_LIMIT'));   //->paginate(7);

        return view('rote.valetudinarian',['regions'=> $regions,
                                    'locations'=> $locations,
                                    'parties'=> $parties, 
                                    'valetudinarians'=> $valetudinarians, 
                                    'layout'=>'index']);*/     
                
        //$links = (string) $paginatedData->links();    //to print links html as a text
        //$escapedHtml = htmlspecialchars($links);
        //echo $escapedHtml;
$startTime = microtime(true);
        $val_paginatedData = $this->getPaginatedData($request, 'id'); // get DataSet and paginatedData 
        $val_paginatedData = $this->getColumnData($val_paginatedData);

        $val_paginatedData = $this->getImages($val_paginatedData, 'valetudinarian_id');
$endTime = microtime(true);
$duration = round(($endTime - $startTime) * 1000, 2);
Log::info("-Rote (index) Transaction completed in {$duration}ms");        
        //$this->cleanParamsArray(); //clean from $_GET parametes

        // Append all current request parameters
        //$val_paginatedData->appends(request()->input());
        //$val_paginatedData->withQueryString();  //will intelligently handle the query parameters, preventing duplicates

        $drop_item_selected = collect(['party_ID' => null, 'location_ID' => null, 'search_Str' => null]);   //drop box item, if selected
        if ($request->party_ID) {
            $val_paginatedData->appends(['party_ID' => $request->party_ID])->links();   //ad html-<a> values to $paginatedData->link() 
            $drop_item_selected->put('party_ID', $request->party_ID);
            //$drop_item_selected->prepend($request->party_ID);   //if collection; place $uset at first position
        }
        if ($request->region) {
            $val_paginatedData->appends(['region' => $request->region])->links();
            $drop_item_selected->put('region', $request->region);
        }
        if ($request->location_ID) {
            $val_paginatedData->appends(['location_ID' => $request->location_ID])->links();
            $drop_item_selected->put('location_ID', $request->location_ID);
        }
        if ($request->search_Str) {
            $val_paginatedData->appends(['search_Str' => $request->search_Str])->links();
            $drop_item_selected->put('search_Str', $request->search_Str);
        }

            //$valetudinarians = $this->paginateArray($valetudinarians, 7); // Paginate with 7 items per page;
            //$valetudinarians = compact('valetudinarians');
            //$valetudinarians = compact((string) $paginatedData->links(), $valetudinarians);
        
        //$visitorCount = PageView::count();  //for page visits
        //$visitorCnt = compact('visitorCount'); to be passed to view, to show visits counts (if we need it)

	 	return view('rote.valetudinarian',['regions'=> $regions,
                                    'locations'=> $locations,
                                    'parties'=> $parties, 
                                    'valetudinarians'=> $val_paginatedData,
                                    'drop_item_selected'=> $drop_item_selected,
                                    'layout'=>'index']);  
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
     * get image_name to recordset.
     */
    public function getImages($paginatedData, string $where_id_ref)
    {
        foreach ($paginatedData as $data) {
            //$val->location_name = Locations::find($val->id)->name;
            $image_data = My_Image::where($where_id_ref, $data->id)->first();
            if ($image_data) {
                $data->image_name = (string) $image_data->image_name;
            } else {
                $data->image_name = NULL;
            }
        }
        return $paginatedData;
    }

    public function getColumnData($paginatedData)
    {
        foreach ($paginatedData as $data) {
            if ($data->location_id) {
                $column_data_l = Location::find($data->location_id)->name;
                if ($column_data_l) {
                    $data->location_name = (string) $column_data_l;
                } else {
                    $data->location_name = NULL;
                }
            } else {
                $data->location_name = NULL;
            }
            
            if ($data->party_id) {
                $column_data_p = Party::find($data->party_id)->name;
                if ($column_data_p) {
                    $data->party_name = (string) $column_data_p;
                } else {
                    $data->party_name = NULL;
                }
            } else {
                $data->party_name = NULL;
            }       
        }
        return $paginatedData;
    }

    /**
     * Get collection data base on criteria.
     * @param request|Request $request
     * @param str $order_by
     * @param str $sorting_order ASC and DESC
     * @return dataset $paginatedData
     */
    public function getPaginatedData(Request $request, $order_by = NULL, $sorting_order = 'ASC')
    {
        if (!isset($this->my_PAGINATION_LIMIT)) {
            if (session('my_dynamic_perpage_variable')) {
                $this->my_PAGINATION_LIMIT = session('my_dynamic_perpage_variable');
            } else {
                $this->my_PAGINATION_LIMIT = config('constants.ROTE_PAGINATION_LIMIT');
            }
        }

        $sql = "SELECT * FROM valetudinarians";

        $where = array();
        if ($request->party_ID) {
            $where[] = "party_id = $request->party_ID"; 
        }
        if ($request->location_ID) {
            $where[] = "d.city_zip = $request->location_ID"; 
        } elseif ($request->region) {
            $where[] = "d.region = '$request->region'";
        }
        if ($request->search_Str) {
            $where[] = "(first_name LIKE '%$request->search_Str%' OR
                        last_name LIKE '%$request->search_Str%' OR
                        occupation LIKE '%$request->search_Str%' OR
                        position LIKE '%$request->search_Str%' OR 
                        email LIKE '%$request->search_Str%')";
            }

        //$where_length = count($where);
        foreach ($where as $key => $item) {
            //$key = key($where);
            if ($key == 0) {
                $sql .= " WHERE ";
            } else {
                $sql .= " AND ";
            }
            $sql .= $item;
        }                    
                
        if ($order_by) {
            $sql .= " ORDER BY ".$order_by." ".$sorting_order; 
        }

        $valetudinarians = DB::select($sql);
                   
        $perPage = $this->my_PAGINATION_LIMIT;              //config('constants.ROTE_PAGINATION_LIMIT');
        $currentPage = $request->get('page', 1);
        $currentItems = array_slice($valetudinarians, ($currentPage - 1) * $perPage, $perPage);

        $paginatedData = new LengthAwarePaginator(
            $currentItems,
            count($valetudinarians),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );
        return $paginatedData;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        //$parties = Party::all()->orderBy('name', 'asc')->lists('name','id');
        $parties = Party::all(); 
        $regions = Location::distinct()->get(['region']);
        $locations = Location::all();
        
        /*$valetudinarians = DB::table('valetudinarians')
            ->leftjoin('parties', 'valetudinarians.party_id', '=', 'parties.id')
            ->leftjoin('locations', 'valetudinarians.location_id', '=', 'locations.id')
            ->select('valetudinarians.*', 'parties.name as party_name', 'locations.name as location_name')
            ->paginate(config('constants.ROTE_PAGINATION_LIMIT'));   //->paginate(7);
            //->get();*/

        $val_paginatedData = $this->getPaginatedData($request, 'id'); // get DataSet and paginatedData
        $val_paginatedData = $this->getColumnData($val_paginatedData);
        //$this->cleanParamsArray(); //clean from $_GET parametes

        $drop_item_selected = collect(['party_ID' => null, 'region' => null, 'location_ID' => null, 'search_Str' => null]);
        if ($request->party_ID) {
            $val_paginatedData->appends(['party_ID' => $request->party_ID])->links();
            $drop_item_selected->put('party_ID', $request->party_ID);
            //$drop_item_selected->prepend($request->party_ID);   //if collection; place $uset at first position
        }
        if ($request->region) {
            $val_paginatedData->appends(['region' => $request->region])->links();
            $drop_item_selected->put('region', $request->region);
        }
        if ($request->location_ID) {
            $val_paginatedData->appends(['location_ID' => $request->location_ID])->links();
            $drop_item_selected->put('location_ID', $request->location_ID);
        }
        if ($request->search_Str) {
            $val_paginatedData->appends(['search_Str' => $request->search_Str])->links();
            $drop_item_selected->put('search_Str', $request->search_Str);
        }

	 	return view('rote.valetudinarian',['valetudinarians'=> $val_paginatedData, 
                                    'drop_item_selected'=> $drop_item_selected,
                                    'parties'=> $parties,
                                    'regions'=> $regions, 
                                    'locations'=> $locations, 
                                    'layout'=>'create']);
    }

    public function upload_image($id)
    {   
	 	return view('image_upload',['id'=> $id, 'layout'=>'image_upload']);  
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
            $file_name_original_size = $request->vale_id . '-' . $image->getClientOriginalName();

            if (My_image::where('valetudinarian_id', $request->vale_id)->exists()) {
                $file_name = Carbon::parse(time())->format('Ymd') . '-' . $request->vale_id . '-' . rand(100, 999) . '.' . $image->getClientOriginalExtension();
            } else {
                $file_name = $request->vale_id . '.' . $image->getClientOriginalExtension();
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
                $valetudinarian = My_Image::create([   //you can use $car = Car::make([ insted but then you have to use $car->save(); before return redi...
                    'valetudinarian_id' => $request->vale_id,
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
        return redirect('/rote_show/'.$request->vale_id)->with('success', 'Image uploaded successfully.');
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
            'first_name' => 'required|unique:valetudinarians',
            'last_name' => 'required',
            'location_id' => 'required',
            'party_id' => 'required',
        ]);

        $valetudinarian = new Valetudinarian();
        $valetudinarian->first_name = $request->input('first_name');
        $valetudinarian->last_name = $request->input('last_name');
        $valetudinarian->sobriquet = $request->input('sobriquet');
        //$valetudinarian->date_of_birth = Carbon::parse($request->input('date_of_birth'))->format('Y-m-d H:i:s');
        $valetudinarian->date_of_birth = Carbon::parse($request->input('date_of_birth'))->toDateString();
        //$valetudinarian->date_of_birth = $request->input('date_of_birth');
        $valetudinarian->occupation = $request->input('occupation');
        $valetudinarian->position = $request->input('position');
        $valetudinarian->email = $request->input('email');
        $valetudinarian->phone = $request->input('phone');
        //LU$valetudinarian->image_path = $request->input('image_path');
        if ($request->input('local_id')) {
            $valetudinarian->location_id = $request->input('local_id');
        } elseif($request->input('location_id')) {
            $valetudinarian->location_id = $request->input('location_id');
        }
        $valetudinarian->location_id = $request->input('location_id');
        $valetudinarian->party_id = $request->input('party_id');
        $valetudinarian->owner_id = auth()->user()->id;
        $valetudinarian->save();

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

        /*$car = Equipment::create([   //you can use $car = Car::make([ insted but then you have to use $car->save(); before return redi...
            'name' => $request->input('name'),
            'brand' => $request->input('brand'),
            'model' => $request->input('model'),
            'serial_number' => $request->input('serial_number'),
            'price' => $request->input('price'),
            'availability' => $request->input('availability'),
            'image_path' => $request->input('image_path')
        ]);*/

        return redirect('/create-event-vale-event/'.$valetudinarian->id);
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
        $valetudinarian = Valetudinarian::find($id);
        $party = Party::find($valetudinarian->party_id);
        $location = Location::find($valetudinarian->location_id);
        
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

	 	return view('rote.valetudinarian_show',['valetudinarian'=> $valetudinarian, 'party' => $party, 'location' => $location, 
                                            'events' => $events, 'images' => $images, 'layout'=>'show']);  
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
        $locations = Location::all(); 
        $parties = Party::all(); 

        $item_selected = Valetudinarian::find($id);
        
        /*$valetudinarians = DB::table('valetudinarians')
            ->leftjoin('parties', 'valetudinarians.party_id', '=', 'parties.id')
            ->leftjoin('locations', 'valetudinarians.location_id', '=', 'locations.id')
            ->select('valetudinarians.*', 'parties.name as party_name', 'locations.name as location_name')
            ->paginate(config('constants.ROTE_PAGINATION_LIMIT'));   //->paginate(7);
            //->get();*/

        $val_paginatedData = $this->getPaginatedData($request, 'a.id'); // get DataSet and paginatedData
        //$this->cleanParamsArray(); //clean from $_GET parametes

        $drop_item_selected = collect(['party_ID' => null, 'region' => null, 'location_ID' => null, 'search_Str' => null]);
        if ($request->party_ID) {
            $val_paginatedData->appends(['party_ID' => $request->party_ID])->links();
            $drop_item_selected->put('party_ID', $request->party_ID);
        }
        if ($request->region) {
            $val_paginatedData->appends(['region' => $request->region])->links();
            $drop_item_selected->put('region', $request->region);
        }
        if ($request->location_ID) {
            $val_paginatedData->appends(['location_ID' => $request->location_ID])->links();
            $drop_item_selected->put('location_ID', $request->location_ID);
        }
        if ($request->search_Str) {
            $val_paginatedData->appends(['search_Str' => $request->search_Str])->links();
            $drop_item_selected->put('search_Str', $request->search_Str);
        }
            
	 	return view('rote.valetudinarian',['valetudinarians'=> $val_paginatedData,
                                    'drop_item_selected'=> $drop_item_selected,
                                    'item_selected'=> $item_selected, 
                                    'parties'=> $parties,
                                    'regions'=> $regions, 
                                    'locations'=> $locations, 
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
            'first_name' => 'required',
            'last_name' => 'required',
            'location_id' => 'required',
            'party_id' => 'required',
        ]);

        $valetudinarian = Valetudinarian::find($id);
        $valetudinarian->first_name = $request->input('first_name');
        $valetudinarian->last_name = $request->input('last_name');
        $valetudinarian->sobriquet = $request->input('sobriquet');
        $valetudinarian->date_of_birth = $request->input('date_of_birth');
        $valetudinarian->occupation = $request->input('occupation');
        $valetudinarian->position = $request->input('position');
        $valetudinarian->email = $request->input('email');
        $valetudinarian->phone = $request->input('phone');
        //LU$valetudinarian->image_path = $request->input('image_path');
        if ($request->input('local_id')) {
            $valetudinarian->location_id = $request->input('local_id');
        } elseif($request->input('location_id')) {
            $valetudinarian->location_id = $request->input('location_id');
        }
        $valetudinarian->party_id = $request->input('party_id');
        $valetudinarian->save();
    
        /*$equipment = Equipment::where('id', $id)
            ->update([   
                'name' => $request->input('name'),
                'brand' => $request->input('brand'),
                'model' => $request->input('model'),
                'serial_number' => $request->input('serial_number'),
                'price' => $request->input('price'),
                'availability' => $request->input('availability'),
                'image_path' => $request->input('image_path')
        ]);*/
        
        return redirect('/rote');
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
        return redirect('/rote');
    }

    public function valeventEvents(Request $request)
    {
 
        switch ($request->type) {
            case 'search':
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

    public function resizeAndSaveImage($source_file, $destination_path, $max_width = 800, $max_height = 600, $quality = 85) {
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

    /*********************** DER ROTE */
    /******************************** */
    function generateRandomStringWithCapitalFirst(int $length = 10, $notCapital = false): string
    {
        //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomString = '';

        // Generate the random string
        for ($i = 0; $i < $length; $i++) {
            $index = random_int(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        if (!$notCapital) {
            // Capitalize the first character
            return ucfirst($randomString);
        } else {
            return $randomString;
        }
    }

    function getRandomDateBetween($startDate, $endDate, $format = 'Y-m-d') {
        // Convert start and end dates to Unix timestamps
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);

        // Generate a random timestamp within the range
        $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);

        // Convert the random timestamp back to a formatted date
        return date($format, $randomTimestamp);
    }

    public function fill_val_withDummyData($maxLoop = 2)
    {
        $owner_id_set = [1, 2, 14, 26, 31, 32, 36, 43, 44, 45, 46, 49, 51, 52, 53];
        $location_id_set = [11000, 21000, 11010, 12223, 25250, 11050, 25230, 23213, 11000, 21000, 18000, 
                            11500, 23000, 11000, 18000, 19000, 23300, 31000, 32000, 34000, 35000, 36000, 36300, 37000, 25000];
        $min = 10000000; // Smallest 8-digit number
        $max = 99999999; // Largest 8-digit number

        for ($i = 1; $i <= $maxLoop; $i++) {

            $email = NULL;
            $phone = NULL;
        
            $valetudinarian = new Valetudinarian();
            $valetudinarian->first_name = $this->generateRandomStringWithCapitalFirst(rand(4, 10));
            $valetudinarian->last_name = $this->generateRandomStringWithCapitalFirst(rand(7, 14));
            $valetudinarian->sobriquet = '';
            $valetudinarian->date_of_birth = $this->getRandomDateBetween('1937-01-01', '2008-09-30');
            //$valetudinarian->date_of_birth = 'date_of_birth';
            $valetudinarian->occupation = null;
            $valetudinarian->position = null;
            
            if (rand(1, 7) == 7) {
                $email = $this->generateRandomStringWithCapitalFirst(rand(4, 7), true) . '@gmail.com';
            } elseif(rand(1, 10) == 7) {
                $phone = '06' . rand($min, $max);;
            }
            $valetudinarian->email = $email;
            $valetudinarian->phone = $phone;
            
            $random_key = array_rand($location_id_set);
            $valetudinarian->location_id = $location_id_set[$random_key];
            
            $valetudinarian->party_id = random_int(1, 7);
            
            $random_key = array_rand($owner_id_set);
            $valetudinarian->owner_id = $owner_id_set[$random_key]; //auth()->user()->id;
            $valetudinarian->save();
           
        }   

        return true;
    }

    public function unlimit(Request $request)
    {
        //$this->fill_val_withDummyData(1500);

        if($request->ajax()) { 

$startTime = microtime(true);
            $sql = "SELECT DISTINCT a.*, c.name AS party_name, d.name AS location_name
                        FROM valetudinarians a 
                        LEFT JOIN (parties c, locations d) ON (a.party_id = c.id AND a.location_id = d.id)";
            
            $where = array();
            if ($request->party_ID) {
                $where[] = "a.party_id = $request->party_ID"; 
            }
            if ($request->location_ID) {
                $where[] = "d.city_zip = $request->location_ID"; 
            } elseif ($request->region) {
                $where[] = "d.region = '$request->region'";
            }
            if ($request->search_Str) {
                $where[] = "(a.first_name LIKE '%$request->search_Str%' OR
                        a.last_name LIKE '%$request->search_Str%' OR
                        a.occupation LIKE '%$request->search_Str%' OR
                        a.position LIKE '%$request->search_Str%' OR 
                        a.email LIKE '%$request->search_Str%')";
            }

            //$where_length = count($where);
            foreach ($where as $key => $item) {
                //$key = key($where);
                if ($key == 0) {
                    $sql .= " WHERE ";
                } else {
                    $sql .= " AND ";
                }
                $sql .= $item;
            }                    
                
            $sql .= " ORDER BY a.id ASC";

            $valetudinarians = DB::select($sql); 
            
$endTime = microtime(true);
$duration = round(($endTime - $startTime) * 1000, 2);
Log::info("-Rote unlimit (Ajax-index) Transaction completed in {$duration}ms");        

            if (auth()->user()) {
                $user = auth()->user()->id;
            } else {
                $user = null;
            }
            
            $paginatedData = $valetudinarians;
                return response()->json([
                    'user' => (integer) $user,
                    'valetudinarians' => $paginatedData,
                    'links' => '' // Render pagination links as string
                ]);
        }

        //************non ajax call*****************************************************************
        $regions = Location::distinct()->get(['region']);
        //$regions = Location::all()->unique('region');
        if ($request->region) {
            $locations = Location::select(['city_zip as id', 'city_zip as zip', 'city as name'])
            ->where('region', '=', $request->region)
            ->distinct()->get();
        } else {
            $locations = Location::all();
        }
        $parties = Party::all();
$startTime = microtime(true);
        $valetudinarians = DB::table('valetudinarians')
            ->leftjoin('parties', 'valetudinarians.party_id', '=', 'parties.id')
            ->leftjoin('locations', 'valetudinarians.location_id', '=', 'locations.id')
            //->leftjoin('images', 'valetudinarians.id', '=', 'images.valetudinarian_id')
            ->select('valetudinarians.*', 'parties.name as party_name', 'locations.name as location_name')
            ->get();

        /*return view('rote.valetudinarian',['regions'=> $regions,
                                    'locations'=> $locations,
                                    'parties'=> $parties, 
                                    'valetudinarians'=> $valetudinarians, 
                                    'layout'=>'index']);   */

$endTime = microtime(true);
$duration = round(($endTime - $startTime) * 1000, 2);
Log::info("-Rote unlimit join (index) Transaction completed in {$duration}ms");        

        $drop_item_selected = collect(['party_ID' => null, 'region' => null, 'location_ID' => null, 'search_Str' => null]);   //drop box item, if selected

        $val_paginatedData = $valetudinarians;
	 	return view('rote.valetudinarian',['regions'=> $regions,
                                    'locations'=> $locations,
                                    'parties'=> $parties, 
                                    'valetudinarians'=> $val_paginatedData,
                                    'drop_item_selected'=> $drop_item_selected,
                                    'layout'=>'unlimit']);  
    }


}
