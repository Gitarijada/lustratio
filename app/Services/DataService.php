<?php
// app/Services/DataService.php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Image as My_Image;

class DataService
{
    public function getPaginatedData($sql = NULL, $paginated = true, $source = 'valetudinarians', Request $request, $order_by = NULL, $sorting_order = 'ASC')
    {   
        if ($sql === NULL) {
            if ($source == 'valetudinarians') {

                $sql = "SELECT DISTINCT a.*, c.name AS party_name, d.name AS location_name
                        FROM valetudinarians a 
                        LEFT JOIN (parties c, locations d) ON (a.party_id = c.id AND a.location_id = d.id)";
                        //"SELECT DISTINCT a.*, FLOOR(v.status / 10) AS statusGroup, ... -- Mathematical method for first digit
            } elseif ($source == 'events') {

                $sql = "SELECT a.*, d.name as location_name, b.count_valID, c.first_name, c.last_name, e.category_name FROM events a 
                        LEFT JOIN (SELECT MIN(valetudinarian_id) as valetudinarian_id, count(valetudinarian_id) as count_valID, event_id FROM vale_events GROUP BY event_id) b ON (a.id = b.event_id) 
                        LEFT JOIN valetudinarians c ON b.valetudinarian_id = c.id
                        LEFT JOIN locations d ON a.location_id = d.id
                        LEFT JOIN categories e ON a.category_id = e.id";
            }
        }

        $where = array();
        if (isset($request->location_ID)) {
            $where[] = "d.city_zip = $request->location_ID"; 
        } elseif (isset($request->region)) {
            $where[] = "d.region = '$request->region'";
        }
        if (isset($request->party_ID)) {
            $where[] = "a.party_id = $request->party_ID"; 
        }
        if (isset($request->category_ID)) {
            $where[] = "a.category_id = $request->category_ID"; 
        }
        if (isset($request->search_Str)) {
            if ($source == 'valetudinarians') {
                $where[] = "(a.first_name LIKE '%$request->search_Str%' OR
                        a.last_name LIKE '%$request->search_Str%' OR
                        a.occupation LIKE '%$request->search_Str%' OR
                        a.position LIKE '%$request->search_Str%' OR 
                        a.email LIKE '%$request->search_Str%')";
            } elseif ($source == 'events') {
                $where[] = "(a.event_name LIKE '%$request->search_Str%' OR 
                        a.event_date LIKE '%$request->search_Str%' OR
                        c.first_name LIKE '%$request->search_Str%' OR
                        c.last_name LIKE '%$request->search_Str%')";
            }
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
            if (is_array($order_by)) {
                foreach ($order_by as $key => $item) {
                    if ($key == 0) $sql .= " ORDER BY "; else $sql .= ", ";
                    if (isset($sorting_order[$key])) {
                        $sortingOrder = $sorting_order[$key];
                    }
                    $sql .= $item." ".$sortingOrder;
                }                    
            } else {
                $sql .= " ORDER BY ".$order_by." ".$sorting_order;    
            } 
        }
        $dataset_complet = DB::select($sql); 
            
        If($paginated) {
            $perPage = config('constants.PAGINATION_LIMIT');
            $currentPage = $request->get('page', 1);
            $currentItems = array_slice($dataset_complet, ($currentPage - 1) * $perPage, $perPage);

            $paginatedData = new LengthAwarePaginator(
                $currentItems,
                count($dataset_complet),
                $perPage,
                $currentPage,
                ['path' => request()->url()]
            );

            if ($source == 'valetudinarians') $paginatedData = $this->getCalculatedStatusGroup($paginatedData);

            return $paginatedData;
        } else {

            return $dataset_complet;
        }
    }

    public function getCalculatedStatusGroup($paginatedData)     //get & show images in a list od data
    {
        foreach ($paginatedData as $valetudinarian) {
            $valetudinarian->statusGroup = (int)floor($valetudinarian->status / 10);
        }

        return $paginatedData;
    }

    /**
     * get image_name to recordset.
     */
    public function getImages($paginatedData, string $where_id_ref)     //get & show images in a list od data
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

    public function fetchUrlFiltersData($paginatedData, Request $request)
    {
        //$drop_item_selected = collect(['party_ID' => null, 'category_ID' => null, 'region' => null, 'location_ID' => null, 'search_Str' => null]);
        $drop_item_selected = collect();
        if (isset($request->party_ID)) {
            $paginatedData->appends(['party_ID' => $request->party_ID])->links();
            $drop_item_selected->put('party_ID', $request->party_ID);
            //$drop_item_selected->prepend($request->party_ID);   //if collection; place $uset at first position
        }
        if (isset($request->category_ID)) {
            $paginatedData->appends(['category_ID' => $request->category_ID])->links();
            $drop_item_selected->put('category_ID', $request->category_ID);
        }
        if (isset($request->region)) {
            $paginatedData->appends(['region' => $request->region])->links();
            $drop_item_selected->put('region', $request->region);
        }
        if (isset($request->location_ID)) {
            $paginatedData->appends(['location_ID' => $request->location_ID])->links();
            $drop_item_selected->put('location_ID', $request->location_ID);
        }
        if (isset($request->search_Str)) {
            $paginatedData->appends(['search_Str' => $request->search_Str])->links();
            $drop_item_selected->put('search_Str', $request->search_Str);
        }

        return ['drop_item_selected_filters' => $drop_item_selected,
                'paginated_data' => $paginatedData,
        ];
    }

    public function fetchFiltersData(Request $request)
    {
        $drop_item_selected = collect();
        //$drop_item_selected = collect(['party_ID' => null, 'category_ID' => null, 'region' => null, 'location_ID' => null, 'search_Str' => null]);
        if (isset($request->party_ID)) {
            $drop_item_selected->put('party_ID', $request->party_ID);
        }
        if (isset($request->category_ID)) {
            $drop_item_selected->put('category_ID', $request->category_ID);
        }
        if (isset($request->region)) {
            $drop_item_selected->put('region', $request->region);
        }
        if (isset($request->location_ID)) {
            $drop_item_selected->put('location_ID', $request->location_ID);
        }
        if (isset($request->search_Str)) {
            $drop_item_selected->put('search_Str', $request->search_Str);
        }

        return ['drop_item_selected_filters' => $drop_item_selected];
    }

}