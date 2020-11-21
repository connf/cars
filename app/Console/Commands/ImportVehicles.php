<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportVehicles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehilces:import {file} {start?}';

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
        $this->info('Import process starting...');

        if ($start = $this->argument('start')) {
            if (!is_numeric($start)) {
                $this->error('Start must be an integer representing a row number to start from.');

                return 1;
            }

            $start = intval($start);

            $this->line('Starting import from row: '.$start);

            /**
             * Code for starting from a specific row goes here...
             */
            $this->line('This is yet to be implemented. Please import entire spreadsheet instead.');
        }

        

        // The code will go here

        // Get $file
        // fopen? file and readline
        // foreach readline as line 
        // if not top row
        // build array of columns (explode \t?)
        // if not empty check for make id or add to db
        // if not empty check for range id or add to db
        // if not empty check for model id or add to db
        // if not empty check for deriv id or add to db
        // if not empty check for colour id or add to db
        // if not empty check for veh type id or add to db

        // if reg is empty +1 fail and go to next row

        // if images is 2 or less +1 fail and go to next row

        // if price is not a positive number +1 fail and go to next row

        // add new array to table +1 success and go to next row



        $this->info('Import routine complete');
        return 0;
    }
}
