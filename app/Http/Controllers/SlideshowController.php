<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use App\Models\ImageGuess;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use App\Models\Valetudinarian;
use App\Models\Location;
use App\Models\Party;
use App\Models\Image as My_Image;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Services\DataService;

class SlideshowController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService)
    {
        $this->middleware(['auth', 'verified'], ['except' => ['index', 'list_all2']]);    // HERE what ya are allowed to access if not loged in

        $this->dataService = $dataService;
    }

    public function index(Request $request)
    {
        if (session('showMainInfoModal') === null) {    
            session(['showMainInfoModal' => true]);     //set to true, not set, show once 
        } else {
            session(['showMainInfoModal' => false]);
        } 
        
        $files = ImageGuess::whereNull('valetudinarian_id')->select('id', 'image_name', 'description')->get();

        //************non & ajax call*****************************************************************
        $regions = Location::distinct()->get(['region']);
        if ($request->region) {
            $cities = Location::select(['city_zip as id', 'city_zip as zip', 'city as name'])
            ->where('region', '=', $request->region)
            ->distinct()->get();
        } else {
            $cities = Location::select(['city_zip', 'city'])->distinct()->get();
        }
        $parties = Party::all();

        $paginated = true;
        $paginatedData = $this->dataService->getPaginatedData(NULL, $paginated, 'valetudinarians', $request, 'a.id');
        $paginatedData = $this->dataService->getImages($paginatedData, 'valetudinarian_id');

        $paginatedData->setPath('/lustratio/public/equ');
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
        if($request->ajax()) {  //will never happen because valetudinarians_list, taking over output
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
            return view('main_vale',['photos' => $files, 
                                    'regions'=> $regions,
                                    'cities' => $cities,
                                    //'locations'=> $locations,
                                    'parties'=> $parties, 
                                    'valetudinarians'=> $paginatedData,
                                    'drop_item_selected'=> $drop_item_selected,
                                    'layout'=> 'index']);
            //************non ajax call End*************************************************************
        }
        //return view('main_slide2x', ['photos' => $files]);
    }

    /*public function modaltest2($xyz = '')
    {
        //$path = public_path('storage/guess_images');   //C:\xampp\htdocs\lustratio\public\storage\vale_images
        $files = [];

        if ($xyz == '') {
            $files = ImageGuess::whereNull('valetudinarian_id')->pluck('id', 'image_name')->toArray();
        } else {
            $images = ImageGuess::whereNull('valetudinarian_id')->orderBy($xyz, 'desc')->get();
            $files = $images->pluck('id', 'image_name')->toArray();
        }

        return view('modaltest2', ['photos' => $files]);
    }*/

    /*public function index($index = 0)
    {
        $path = public_path('storage/guess_images');
        $files = File::files($path);

        $photos = collect($files)->map(fn($f) => 'storage/guess_images/' . $f->getFilename())->values();

        if ($photos->isEmpty()) {
            return view('photo2', ['photo' => null, 'prev' => null, 'next' => null]);
        }

        // Clamp index
        $index = max(0, min($index, $photos->count() - 1));

        $photo = $photos[$index];
        $prev = $index > 0 ? $index - 1 : null;
        $next = $index < $photos->count() - 1 ? $index + 1 : null;

        return view('photo2', compact('photo', 'prev', 'next'));
    }*/

 
/*$path = public_path('storage/folder_images');
$files = [];

if (File::exists($path)) {
$files = collect(File::files($path))
->map(fn($f) => $f->getFilename())
->values()
->all();
}

// $files is an array of filenames (e.g. ['img1.jpg', 'pic.png'])
return view('photo', ['photos' => $files]);
*/
    /*public function list_all()
    {
        $path = public_path('storage/guess_images');   //C:\xampp\htdocs\lustratio\public\storage\vale_images
        $files = File::files($path);

        // Extract file names (not full paths) -- from folder
        $photos = collect($files)->map(function ($file) {
            return 'storage/guess_images/' . $file->getFilename();
        });
        
        return view('slideshow', compact('photos'));
    }*/

    public function list_all2($sort_by = '')
    {
        //$path = public_path('storage/guess_images');   //C:\xampp\htdocs\lustratio\public\storage\vale_images
        //$files = [];

        if ($sort_by == '') {
            //$files = ImageGuess::whereNull('valetudinarian_id')->pluck('id', 'image_name', 'description')->toArray();
            $files = ImageGuess::whereNull('valetudinarian_id')->select('id', 'image_name', 'description')->get();
        } else {
            //$images = ImageGuess::whereNull('valetudinarian_id')->orderBy($sort_by, 'desc')->get();
            //$files = $images->pluck('id', 'image_name', 'description')->toArray();
            $files = ImageGuess::whereNull('valetudinarian_id')->select('id', 'image_name', 'description')->orderBy($sort_by, 'desc')->get();
        }

        //if (is_array($files)) {
            /*if (!File::exists($path)) {
                $files = collect(File::files($path))
                ->map(fn($f) => $f->getFilename())
                ->values()
                ->all();
            }*/

            //return view('slideshow2', ['photos' => $files]);
        //}
        //return view('slideshow2', compact('images'));
        return view('slideshow2', ['photos' => $files]);
    }

    public function uploadGuessImage()
    {
        return view('upload_guess_img');
    }

    // Delete photo (AJAX)
    public function delete(Request $request)
    {
        $request->validate([
            'filename' => 'required|string'
        ]);

        $filename = basename($request->input('filename'));
        $path = public_path("storage/guess_images/{$filename}");


        if (!File::exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not found.'], 404);
        }

        try {
            //File::delete($path);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    // Optional: download handled by browser via public asset link; if you prefer a controlled download
    // you can add a method that returns a response()->download(...) for better headers.
    
    /*public function guess_stuff(Request $request)   //currently not i use (del reccord from images_guess)
    {
        switch ($request->type) {
            case 'delete-images_guess':
                if($request->ajax()) { 
                
                    $filename = basename($request->input('filename'));
                    $affectedRows = ImageGuess::where('image_name', $request->filename)->delete();
                    if ($affectedRows > 0) {
                        $message = "Record deleted successfully.";
                    } else {
                        $message = "No record found with that file name.";
                    }
            
                return response()->json($message);
                }
            break;
             
            default:
             # ...
            break;
        }
    }*/


    public function upload_image()
    {   
	 	return view('image_upload',['layout'=>'image_upload', 'image_type'=>'guess']);  
    }

    public function store_image(Request $request)
    {   
        $request->validate([
            'image' => 'required|mimes:jpg,png,jpeg|image|max:9048',    // was max:5048
            'description' => 'required'
        ]);

        //$imagePath = $request->file('image')->store('event_images/uploads'); //in config/filesystems.php ==>>"'root' => storage_path('app/public'),"
                                                                      //then files are in ../storage/app/event_images/uploads/"filename.xxx"
        if($request->hasFile('image')) {
            $image = $request->file('image');
            //$image_name = $request->file('image')->getClientOriginalName();
            $file_name_original_size = $image->getClientOriginalName();

            $file_name = Carbon::parse(time())->format('Ymd') . '-' . rand(100, 999) . '.' . $image->getClientOriginalExtension();

            $request->image->move(public_path('storage/guess_images'), $file_name_original_size);

            //*******resize file and delete original */
            $source_file = public_path('storage/guess_images') . '/' . $file_name_original_size;
            $destination_file = public_path('storage/guess_images') . '/' . $file_name;
            // $max_width = 800; $max_height = 600;  $jpeg_quality = 85;
            //if ($this->resizeAndSaveImage($source_file, $destination_file, $max_width, $max_height, $jpeg_quality)) {
            if ($this->resizeAndSaveImage($source_file, $destination_file)) {
                echo "Image resized and saved successfully to " . $destination_file;
                ImageGuess::create([
                    //'valetudinarian_id' => $request->valetudinarian_id,
                    'image_name' => $file_name,
                    'description' => $request->description,
                    'owner_id' => Auth::user()->id
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
        //return redirect('/show-valeevent/'.$request->parent_id)->with('success', 'Image uploaded successfully.');
        $sort_by = 'created_at';
        return redirect('/fame-all2/'.$sort_by)->with('success', 'Image uploaded successfully.');
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

}