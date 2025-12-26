<?php 

namespace App\Models;

//use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class postal_code_location extends Model {

	public $timestamps = false; // Disable automatic timestamps, not use without `updated_at` & `created_at`
        // ...
	use HasFactory;

	//in order to have different name of table, then pluralise of model name
	protected $table = 'locations_total';
	protected $primaryKey = 'id'; 
	protected $fillable = ['region', 'division', 'city_zip', 'city', 'zip', 'name'];

    public const URL_PREFIX  = 'https://srb.postcodebase.com';
    public $regions = [];

    public function save_to_table($region, $city, $city_district, $post, $zip) 
    {
        postal_code_location::create([   
            'region' => $region,
            'division' => $city,
            'city' => $city_district,
            'zip' => $zip,
            'name' => $post
        ]);
    }

    public function get_recursive_region3($region, $contest_url, $city)
    {
        $contest_data_json = file_get_contents(self::URL_PREFIX . $contest_url);

        if ($contest_data_json === false) {
            echo "Error fetching contest data.";
        } else {
            // Process the JSON data
            $filename = storage_path($city . '_3_my.txt');
            $file_handle = fopen($filename, "a");

            // Check if the file was opened successfully
            if ($file_handle) {

                $lines = explode('tr  class', $contest_data_json);
                array_shift($lines); 
                //array_pop($lines); 
                //$regions = [];

                foreach ($lines as $line) {
                    if (str_contains($line, 'region3')) {

                        $data = explode('</td>', $line);
                        $i = 0;
                        foreach ($data as $line) {
                            $line = str_replace('first">', '', $line);
                            $line = str_replace('even">', '', $line);
                            $line = str_replace('odd">', '', $line);
                            $line = str_replace('last">', '', $line);
                            $line = str_replace('zip">', '', $line);
                            $line = str_replace('</a>', '', $line);
                            $begining = strpos($line, '">');
                            $subline = substr($line, $begining+2);
                            $subline = str_replace('"', '', $subline); 
                            $data[$i] = trim($subline);
                            $i ++;
                        }

                            $regions[] = ['region' => $region, 
                                            'city' => $city, 
                                            'region2' => $data[0], 
                                            'region3' => $data[1], 
                                            'zip' => $data[2]];

                            $this->save_to_table($region, $city, $data[0], $data[1], $data[2]);

                            echo $data[0] . '   ' . $data[1] . '   ' . $data[2] . "\n";
                            $content_to_write = $region . ',' . $city . ',' . $data[0] . ',' . $data[1] . ',' . $data[2] . "\n";
                            fwrite($file_handle, $content_to_write); 
                            
                            if (str_contains($line, ' of ') and str_contains($line, 'Go to next page')) {
                                $data = explode('Go to next page', $line);
                                array_shift($data);  
                                $begining = strpos($data[0], "href");
                                $end = strpos($data[0], ">next");
                                $next_page = substr($data[0], $begining + 6, $end - $begining - 7);
                                $next_page = str_replace('"', '', $next_page); 
                                $next_page = str_replace("'", '', $next_page);
                                $this->get_recursive_region3($region, $next_page, $city);                              
                            }
                                              
                    }
                }

                //fwrite($file_handle, $regions[]);   
                fclose($file_handle);
            } 
        }
    }

    public function get_region2($contest_url, $region)
    {
        $contest_data_json = file_get_contents(self::URL_PREFIX . $contest_url);

        if ($contest_data_json === false) {
            echo "Error fetching contest data.";
        } else {
            // Process the JSON data
            $filename = storage_path($region . '_2_my.txt');
            $file_handle = fopen($filename, "a");

            // Check if the file was opened successfully
            if ($file_handle) {

                $lines = explode('tr  class', $contest_data_json);
                array_shift($lines); 
                //array_pop($lines); 
                //$regions = [];

                foreach ($lines as $line) {
                    if (str_contains($line, '/region')) {

                        $begining = strpos($line, "href");
                        $end = strpos($line, "</a>");
                        $subline = substr($line, $begining + 6, $end - $begining - 6);
                        $line = str_replace('first">', '', $line);
                        $line = str_replace('even">', '', $line);
                        $line = str_replace('odd">', '', $line);
                        $line = str_replace('last">', '', $line);
                        $line = str_replace('</a>', '', $line);
                        $data = explode('">', $subline);
                        $data[0] = trim($data[0]);
                        $data[1] = trim($data[1]);
                            
                            echo $data[0] . '   ' . $data[1] . "\n";
                            $content_to_write = $region . ',' . $data[0] . ',' . $data[1] . "\n";
                            fwrite($file_handle, $content_to_write);
                            $this->get_recursive_region3($region, $data[0], $data[1]);  //$region, $contest_url, $city
                            if (str_contains($line, ' of ') and str_contains($line, 'Go to next page')) {
                                $data = explode('Go to next page', $line);
                                array_shift($data);  
                                $begining = strpos($data[0], "href");
                                $end = strpos($data[0], ">next");
                                $next_page = substr($data[0], $begining + 6, $end - $begining - 7);
                                $next_page = str_replace('"', '', $next_page); 
                                $next_page = str_replace("'", '', $next_page);
                                $this->get_region2($next_page, $region);    
                                                                
                            }
                    }
                }

                //fwrite($file_handle, $regions[]);   
                fclose($file_handle);

            } else {
                echo "Error: Could not open $filename for writing.";
            }
        }
    }
        
    public function get_region()
    {
        $contest_url = "https://srb.postcodebase.com/region2-text"; // Replace with the actual URL
        //$contest_url = "https://srb.postcodebase.com/region2/beograd";
        //$contest_url = "https://srb.postcodebase.com/region3/beograd";      //https://srb.postcodebase.com/region3/bor
                                                                            //https://srb.postcodebase.com/region3/golubac Golubac/Braničevski/Brnjica/12223
        //$contest_url = "https://srb.postcodebase.com/node/209"; //Borski/Bor/Bor 
        $contest_data_json = file_get_contents($contest_url);

        if ($contest_data_json === false) {
            echo "Error fetching contest data.";
        } else {
            // Process the JSON data
            $filename = storage_path('Regions_my.txt');
            $file_handle = fopen($filename, "a");

            // Check if the file was opened successfully
            if ($file_handle) {

                $lines = explode('field-content', $contest_data_json);
                array_shift($lines); 
                //array_pop($lines); 

                foreach ($lines as $line) {
                    if (str_contains($line, '/region')) {

                        $begining = strpos($line, "href");
                        $end = strpos($line, "</a>");
                        $subline = substr($line, $begining + 6, $end - $begining - 6);
                        $data = explode('">', $subline);
                        $data[0] = trim($data[0]);
                        $data[1] = trim($data[1]);
                        
                            echo $data[0] . '   ' . $data[1] . "\n";
                            $content_to_write = $data[0] . ',' . $data[1] . "\n";
                            fwrite($file_handle, $content_to_write);
                            $this->get_region2($data[0], $data[1]);     //get_region2($contest_url, $region)                        
                    }
                }

                //fwrite($file_handle, $regions[]);   
                fclose($file_handle);

            } else {
                echo "Error: Could not open $filename for writing.";
            }

        }
    }

    //***from files***//
    //
    //
    //****************//

    public $set_continue_flag = false;

    public function load_from_files3($region, $city)
    {
        $ZIP_BRAKE = 18355;     //last entered
        $POST_BRAKE = 'Zaskovci'; //last entered

        $filename = storage_path($city . '_3_my.txt');
        if (file_exists($filename)) {
            $handle = fopen($filename, 'r'); // Open file in read mode
            $i = 0;
            if ($handle) {
                while (($line = fgets($handle)) !== false) {

                    $array = explode(",", $line);

                    $region = trim($array[0]);
                    $city = trim($array[1]);
                    $city_district = trim($array[2]);
                    $post = trim($array[3]);
                    $zip = trim($array[4]);
                    $zip = preg_replace("/[^0-9]/", "", $zip);

                    if($zip == $ZIP_BRAKE && $post == $POST_BRAKE) {
                        $this->set_continue_flag = true;
                        continue;
                    }
                    if($this->set_continue_flag) {
                        $this->save_to_table($region, $city, $city_district, $post, $zip);
                    }       
                }
                fclose($handle);
            }
        }
    }

    public function load_from_files2($region)
    {
        $CITY_BRAKE = 'Pirot';

        $filename = storage_path($region . '_2_my.txt');
        if (file_exists($filename)) {
            $handle = fopen($filename, 'r'); // Open file in read mode
            $i = 0;
            if ($handle) {
                while (($line = fgets($handle)) !== false) {

                    $array = explode(",", $line);
                    $city = trim($array[2]);

                    if($city == $CITY_BRAKE or $this->set_continue_flag) {
                        $this->load_from_files3($region, $city);
                    }   
                }
                fclose($handle);
            }
        }
    }

    public function load_from_files()
    {
        $REGION_BRAKE = 'Pirotski';

        $filename = storage_path('Regions_my.txt');
        if (file_exists($filename)) {
            $handle = fopen($filename, 'r'); // Open file in read mode
            $i = 0;
            $set_flag = false;
            if ($handle) {
                $list = '';
                while (($line = fgets($handle)) !== false) {

                    $array = explode(",", $line);
                    $region = trim($array[1]);

                    if($region == $REGION_BRAKE) {
                        $set_flag = true;
                    }
                    if($set_flag) {
                        $this->load_from_files2($region);
                    }       
                }
                fclose($handle);
            }
        }
    }

    public function clean_file()
    {
        $filePath = storage_path('Region_Beograd.txt'); // Adjust path as needed
        if (file_exists($filePath)) {
            $handle = fopen($filePath, 'r'); // Open file in read mode
            $i = 0;
            if ($handle) {
                $list = '';
                while (($line = fgets($handle)) !== false) {
                    // Process each line 
                    
                    
                    $array = explode(" ", $line);

                    //$city = $array[0]; // Outputs: red
                    //$name = $array[1]; // Outputs: green
                    $zip = $array[0]; // Outputs: green
                    $zip = preg_replace("/[^0-9]/", "", $zip);
                    $zip = trim($zip);

                    if (is_numeric($zip)) {
                        $list = $list . ', ' . $zip;
                    } else  {
                        $zip = $array[1]; // Outputs: green
                        $zip = preg_replace("/[^0-9]/", "", $zip);
                        $zip = trim($zip);
                        if (is_numeric($zip)) {
                            $list = $list . ', ' . $zip;
                        } else  {
                            print_r('TROPA');
                        }
                    }
                    
                    //$list = $list . ', ' . $zip;
                    /*$zip = str_replace('"', '', $zip);
                    $name = str_replace('"', '', $name);

                    $reg2 = substr($zip, 0, 2);

                    if ($reg2 == 11) {
                        $zip_2 = substr($zip, 0, 3);
                    } else {
                        $zip_2 = substr($zip, 0, 4);
                    }*/
                }
                fclose($handle);
            }
        }
            
            $sql = 'UPDATE locations_all SET city_zip = 11000, city = "Beograd" WHERE zip IN (' . $list . ');';
            print_r($sql);

    }

}
