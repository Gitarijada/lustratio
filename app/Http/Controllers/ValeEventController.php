<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValeEvent;
use App\Models\Valetudinarian;
use App\Models\EquEvent;
use App\Models\GroupEvent;
use App\Models\CrudEvents;
use App\Models\Event;
use App\Models\Equipment;
use App\Models\Category;
use App\Models\Party;
use App\Models\Location;
use App\Models\Store;

use Illuminate\Support\Facades\DB;
use App\Services\DataService;

use Carbon\Carbon;

class ValeEventController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService)
    {
        //$this->middleware('auth');    // HERE what ya are allowed to access if not loged in
        $this->middleware(['auth', 'verified'], ['except' => ['show']]);

        $this->dataService = $dataService;
    }

    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

        $categories = Category::all();
        $events = Event::all();
        $locations = Location::all();
        
        if (isset($request->event_ID)) {
            $selected_vale_ids = $request->get('vale_selected'); 
            if (!empty($selected_vale_ids)) {
                $selected_ids = implode(',', $selected_vale_ids);
                $sql_selected = ", (CASE WHEN a.id IN ($selected_ids) THEN 1 ELSE 0 END) AS vale_selected";
                $order_by = array('used_by_other', 'vale_selected', 'a.id');
                $sorting_order = array('DESC', 'DESC', 'ASC'); 
            } else {
                $sql_selected = "";
                $order_by = array('used_by_other', 'a.id');
                $sorting_order = array('DESC', 'ASC');
            }

            /*$sql = "SELECT DISTINCT a.id, first_name, last_name, date_of_birth, party_id, c.name AS party_name, location_id, d.region, 
                d.city_zip, d.name AS location_name, owner_id, 
                (CASE WHEN event_id = " . $request->event_ID . " THEN 1 ELSE 0 END) AS used_by_other" . $sql_selected . " FROM valetudinarians a
                LEFT JOIN parties c on a.party_id = c.id
                LEFT JOIN locations d on a.location_id = d.id
                LEFT JOIN vale_events e on a.id = e.valetudinarian_id";*/

            /*$valetudinarians = DB::table('valetudinarians')
                ->leftjoin('parties', 'valetudinarians.party_id', '=', 'parties.id')
                ->leftjoin('locations', 'valetudinarians.location_id', '=', 'locations.id')
                ->leftjoin('vale_events', 'valetudinarians.id', '=', 'vale_events.valetudinarian_id')
                ->select('valetudinarians.id', 'valetudinarians.first_name', 'valetudinarians.last_name', 'date_of_birth', 'party_id', 'parties.name as party_name', 
                'location_id', 'locations.region as region', 'locations.name as location_name', 'owner_id', DB::raw('(CASE WHEN event_id = ' . $request->event_ID . ' THEN 1 ELSE 0 END) AS used_by_other'))
                ->orderBy('used_by_other', 'desc')
                ->orderBy('id', 'asc')
                ->get();
            $valetudinarians = $valetudinarians->unique('id');
            if (isset($request->party_ID)) {
                $valetudinarians = $valetudinarians->where('party_id',$request->party_ID);
            }
            if (isset($request->location_ID)) {
                $valetudinarians = $valetudinarians->where('city_zip',$request->location_ID);
            } elseif (isset($request->region)) {
                $valetudinarians = $valetudinarians->where('region',$request->region);
            }
            if (isset($request->search_Str)) {
                $searchStr = $request->search_Str;
                $valetudinarians = collect($valetudinarians)->filter(function ($item) use ($searchStr) {
                        // replace stristr with your choice of matching function
                        return false !== (stristr($item->first_name, $searchStr) 
                                        OR stristr($item->last_name, $searchStr)
                                        OR stristr($item->date_of_birth, $searchStr));
                });
            }*/
            $sql = "SELECT a.id, a.first_name, a.last_name, a.date_of_birth, a.party_id, c.name AS party_name, a.location_id, d.region, 
                        d.city_zip, d.name AS location_name, a.owner_id, status,
                        COALESCE(ev.used_by_other, 0) AS used_by_other" . $sql_selected . " FROM valetudinarians a
                    LEFT JOIN parties c on a.party_id = c.id
                    LEFT JOIN locations d on a.location_id = d.id
                    LEFT JOIN (SELECT valetudinarian_id, MAX(event_id = " . $request->event_ID . ") AS used_by_other
                        FROM vale_events
                        GROUP BY valetudinarian_id) ev ON a.id = ev.valetudinarian_id";
        } else {
            $sql = "SELECT DISTINCT a.id, first_name, last_name, date_of_birth, party_id, c.name AS party_name,
                    location_id, d.region, d.city_zip, d.name as location_name, owner_id, status, '1' AS used_by_other
                    FROM valetudinarians a
                    LEFT JOIN parties c ON a.party_id = c.id
                    LEFT JOIN locations d ON a.location_id = d.id";
            $order_by = 'a.id';
            $sorting_order = 'ASC';     
        }

        $paginated = true;
        $valetudinarians = $this->dataService->getPaginatedData($sql, $paginated, 'valetudinarians', $request, $order_by, $sorting_order);
        //getPaginatedData($sql = NULL, $paginated = true, $source = 'valetudinarians', Request $request, 'a.id', 'ASC')

        $allRequestParams = $request->except(['_token', '_method']);
        if (!empty($allRequestParams)) {
            /*$selected_vale_ids = $request->get('vale_selected');
            if (!empty($selected_vale_ids)) {   //if there some selected persons, only if ajax call                
                $selectedIds = array_map('intval', $selected_vale_ids); // ensure integers
                $selectedLookup = array_flip($selectedIds); // for O(1) lookup time

                foreach ($valetudinarians as &$vale) {
                    $vale->vale_selected = isset($selectedLookup[$vale->id]) ? 1 : 0;
                }
                unset($vale);
            }*/
            //set links and items selected for: 'party_ID', 'category_ID', 'region', 'location_ID', 'search_Str'
            if ($paginated) {
                $UrlFiltersData = $this->dataService->fetchUrlFiltersData($valetudinarians, $request);
                $paginatedData = $UrlFiltersData['paginated_data'];
                $drop_item_selected = $UrlFiltersData['drop_item_selected_filters'];
                if (isset($request->event_ID)) {
                    $paginatedData->appends(['event_ID' => $request->event_ID])->links();
                    if(!$request->ajax()) {
                        $drop_item_selected->put('event_ID', $request->event_ID);
                    }
                }
            } else {
                $UrlFiltersData = $this->dataService->fetchFiltersData($request);
                $drop_item_selected = $UrlFiltersData['drop_item_selected_filters'];
            }
        } else {
            $drop_item_selected = null;
        }

        if($request->ajax()) {          //add vale to events (when we chose event or with some event). Find vale attached to events
            switch ($request->type) {
                case 'data-output':
                    if ($paginated) {
                        $paginatedData->setPath('/lustratio/public/add-valeevent');
                        $valetudinarians = $paginatedData;
                    }
                    $layout = 'add';
                    $html = view('valetudinarianslist_check', compact('regions', 'cities', 'parties', 
                                                    'valetudinarians', 'drop_item_selected', 'layout'))->render();
                    // Return as JSON (so you can access it in AJAX success)
                    return response()->json(['html' => $html]);
                    
                break;
                case 'filter-js':
                    if (auth()->user()) {
                        $user = auth()->user()->id;
                        //$valetudinarians->prepend($user);   //not working with sql versions //place $uset at first position
                    }
                    return response()->json($valetudinarians);
                break;    
            }         
        } else {

            return view('vale_event',['parties'=> $parties,
                                        'regions'=> $regions, 
                                        'cities' => $cities,
                                        'locations' => $locations,
                                        'categories'=> $categories, 
                                        'valetudinarians'=> $valetudinarians, 
                                        'events' => $events,
                                        'drop_item_selected' => $drop_item_selected,
                                        'layout'=>'add']);  
        }

    }

    public function vale_confirmation(Request $request) {
        $request->validate([
            'vale_selected' => 'required',
            'event_ID' => 'required',       // |integer|min:1 not nessesary, it will be always int
            //'equ_start'  =>  'required|date_format:Y-m-d H:i:s',    //now is readonly, no need for validation
            //'equ_end' => 'required|date_format:Y-m-d H:i:s|after:event_start',
        ]);
        if($request->ajax()) {          //add vale to events (when we chose event or with some event). Find vale attached to events
            switch ($request->type) {
                case 'data-confirmation':
                    if (isset($request->event_ID)) {
                        $event = Event::find($request->event_ID);
                        //$category = Category::find($event->category_id);
                        //$location = Location::find($event->location_id);
                        //if ($event->event_date != NULL) $event->event_date = Carbon::parse($event->event_date)->format('Y-m-d');
                    }
                    $selected_vale_ids = $request->get('vale_selected'); 
                    if (!empty($selected_vale_ids)) {
                        $selected_ids = implode(',', $selected_vale_ids);
                    }
                    $sql = "SELECT DISTINCT a.id, first_name, last_name, date_of_birth, party_id, c.name AS party_name,
                    location_id, d.region, d.city_zip, d.name as location_name, owner_id, '0' AS used_by_other, '1' AS vale_selected
                    FROM valetudinarians a
                    LEFT JOIN parties c ON a.party_id = c.id
                    LEFT JOIN locations d ON a.location_id = d.id WHERE a.id IN ($selected_ids)";

                    $valetudinarians = DB::select($sql);

                    $layout = 'show';
                    $html = view('valelistsimple_check', compact('valetudinarians', 'event', 'layout'))->render();
                    // Return as JSON (so you can access it in AJAX success)
                    return response()->json(['html' => $html]);
                    
                break;   
            }         
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {   
        //when you create event (CREATE EVENT) to pass ID event and go to attached Vale to Event (to fill ValeEvent table)
        $event = Event::find($id);
        $category = Category::find($event->category_id);
        $regions = Location::distinct()->get(['region']);
        $cities = Location::select(['city_zip', 'city'])->distinct()->get();
        $location = Location::find($event->location_id);
        if ($event->event_date != NULL) $event->event_date = Carbon::parse($event->event_date)->format('Y-m-d');
        //$event->event_date = Carbon::parse($event->event_date)->toDateString();
        //$event->event_date = Carbon::parse($event->event_date)->format('Y-m-d H:i:s');
        //$date_end = Carbon::parse($event->event_end)->format('Y-m-d H:i:s');

        $valetudinarians = DB::select("SELECT DISTINCT a.*, c.name AS party_name, d.name AS location_name,
        case 
            when (b.event_id = $id) then 1
            else 0
        end
        as used_by_other 
        FROM valetudinarians a 
        LEFT JOIN (parties c, locations d) ON (a.party_id = c.id AND a.location_id = d.id)
        LEFT JOIN vale_events b ON a.id = b.valetudinarian_id  
        ORDER BY a.id ASC");

        $parties = Party::all();
        //$locations = Location::all(); 
	 	return view('vale_event',['valetudinarians'=> $valetudinarians,
                                    'parties'=> $parties, 
                                    'regions'=> $regions, 
                                    'cities' => $cities,                                    
                                    //'locations' => $locations,
                                    'event' => $event,
                                    'category'=> $category, 
                                    'location' => $location,
                                    'layout'=>'create_passID']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'vale_selected' => 'required',
            'event_id' => 'required',       // |integer|min:1 not nessesary, it will be always int
        ]);

        $event_id = $request->input('event_id');
        //LU$group_ID = $request->input('group_id');
         
        $selected_vale_ids = $request->get('vale_selected');
    
        foreach($selected_vale_ids as $item_id)
        {
            $existingRecord = ValeEvent::where('event_id', $event_id)
                               ->where('valetudinarian_id', $item_id)
                               ->first();

            // If a matching record is found, do not insert
            if ($existingRecord) {
                continue; // This breaks the current loop iteration and moves to the next $valetudinarian_id
            }
            
            $vale_events = ValeEvent::create([   //you can use $equ_events = EquEvent::make([ insted but then you have to use $car->save(); before return redi...
                'event_id' => $event_id,
                'valetudinarian_id' => $item_id  //$request->get('equ_selected');
            ]);
        }

        return redirect('/show-valeevent/'.$event_id);         //came to new only Event input --- and then Vales attached to that Event - all way around
        //LUreturn redirect('/create-crew/'.$equ_events->event_id);       //call crew-event input

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::find($id);
        $category = Category::find($event->category_id);
        $location = Location::find($event->location_id);
        if ($event->event_date != NULL) $event->event_date = Carbon::parse($event->event_date)->format('Y-m-d');

        $valetudinarians = DB::select("SELECT a.*, b.name as location_name, c.name as party_name FROM valetudinarians a 
            LEFT JOIN locations b ON a.location_id = b.id
            LEFT JOIN parties c ON a.party_id = c.id
            LEFT JOIN vale_events d ON (d.valetudinarian_id = a.id) 
            WHERE d.event_id = $id
            ");
        
        $images = DB::table('images_events')->where('event_id', '=', $id)->get();
        //$parties = Party::all();
        //$locations = Location::all(); 
	 	return view('vale_event',['valetudinarians'=> $valetudinarians,
                                    //'parties'=> $parties,                                      
                                    //'locations' => $locations,
                                    'event' => $event,
                                    'category'=> $category, 
                                    'location' => $location,
                                    'images' => $images,
                                    'layout'=>'show']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
        //$equ_event = EquEvent::find($id);
        //$equ_event->delete();
        //return redirect()->back();  
    }

    public function valeeventEvents(Request $request)
    {
 
        switch ($request->type) {
            case 'select-option-list':  
                if($request->ajax()) { 
                                                //events select box to be filed base on categorys or locations select box
                    $category_id = $request->category_ID;
                    $region = $request->region_NAME;
                    $location_id = $request->location_ID;   //not used at the moment
                    if ($category_id && $location_id) {
                        $events = DB::select("SELECT id, event_name FROM events 
                        WHERE category_id = $category_id AND (location_id = $location_id OR location_id IS NULL)");
                    } elseif ($location_id) {
                        $events = DB::select("SELECT id, event_name FROM events 
                        WHERE location_id = $location_id OR location_id IS NULL");
                    } elseif ($category_id) {
                        $events = Event::select(['id','event_name'])
                        ->where('category_id', $category_id)
                        ->get();
                    } else {
                        $events = Event::get(['id','event_name']);
                    }
                    
                    return response()->json($events);
                } 
            break;
             
            default:
             # ...
            break;
        }
    }

}
