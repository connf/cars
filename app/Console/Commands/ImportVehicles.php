<?php

namespace App\Console\Commands;

use App\Mail\VehiclesImported;
use App\Models\Makes;
use App\Models\Ranges;
use App\Models\Models;
use App\Models\Derivatives;
use App\Models\Colours;
use App\Models\Vehicles;
use App\Models\VehicleTypes;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ImportVehicles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicles:import {file} {start?}';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Import vehicles from CSV';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting import process...');

        if ($start = $this->argument('start')) {
            if (!is_numeric($start)) {
                $this->error('Start must be an integer representing a row number to start from.');

                return 1;
            }
        }

        // default to row 1 (after headers) or use row number if valid
        $start = intval($start) ?: 1; 

        // initialise variables
        $row = 0; // 0 = headers, 1+ = data rows
        $errors = [];
        $success = [];

        $this->line('Starting import from row: '.$start);

        if (($handle = fopen($this->argument('file'), "r")) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                if ($row >= $start) {
                    $this->info("Importing row $row:");

                    $make = Makes::firstOrCreate(['name' => $data[1]])->id;
                    $this->line('Make logged');

                    $range = Ranges::firstOrCreate(['name' => $data[2], 'make_id' => $make], ['name' => $data[2], 'make_id' => $make])->id;
                    $this->line('Range logged');

                    $model = Models::firstOrCreate(['name' => $data[3], 'range_id' => $range])->id;
                    $this->line('Model logged');

                    $derivative = Derivatives::firstOrCreate(['name' => $data[4], 'model_id' => $model])->id;
                    $this->line('Derivative logged');

                    $colour = Colours::firstOrCreate(['name' => $data[6]])->id;
                    $this->line('Colour logged');

                    $type = VehicleTypes::firstOrCreate(['name' => $data[8]])->id;
                    $this->line('Vehicle type logged');

                    // Check for and log errors
                    if (empty($data[0])) {
                        $errors[$row][] = $err = "Vehicle requires a registration number";
                        $this->error($err); 
                    }

                    if (!(is_numeric($price = str_replace(',', '', $data[5])) && $price > 0)) {
                        $errors[$row][] = $err = "Vehicle requires a price that is a positive number";
                        $this->error($err);  
                    }

                    $images = explode(',', $data[10]);

                    if (!(is_array($images) && count($images) >= 3)) {
                        $errors[$row][] = $err = "Vehicle requires 3 images"; 
                        $this->error($err); 
                    }

                    if (!array_key_exists($row, $errors)) {
                        Vehicles::create([
                            'derivative_id' => $derivative,
                            'colour_id' => $colour,
                            'vehicle_type_id' => $type,
                            'registration' => $data[0],
                            'price_inc_vat' => $data[5],
                            'mileage' => $data[7],
                            'date_on_forecourt' => Carbon::parse($data[9]),
                            'images' => empty($images) ?: serialize($images)
                        ]);

                        $success[$row][] = $msg = "Row {$row} imported successfully";
                        $this->line('');
                        $this->info($msg);
                    } else {
                        $this->line('');
                        $this->error("Row {$row} not imported");
                    }

                    $this->line('');
                    $this->line('');
                }

                $row++;
            }
            fclose($handle);
        }

        $results = [
            'total' => (int) count($success) + (int) count($errors),
            'success' => count($success),
            'errored' => count($errors),
            'errors' => $errors
        ];

        $this->line("Processed: ".$results['total']." rows");
        $this->line("Successfully imported: ".$results['success']." rows");
        $this->error("Failed to import: ".$results['errored']." rows");
        $this->info("Import routine complete");

        Mail::send(new VehiclesImported($results));

        return 0;
    }
}
