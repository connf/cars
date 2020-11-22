<?php

namespace App\Console\Commands;

use App\Models\Vehicles;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExportVehicles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicles:export {make?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export vehicles to CSV';

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
        $this->info('Starting export process...');

        if ($make = $this->argument('make')) {
            $this->line("Exporting ".$make." vehicles only");
        } else {
            $this->line("Exporting all vehicles only");
        }

        $this->line("Setting up...");

        $vehicles = Vehicles::get();

        $data[] = [
            'registration',
            'car_tile',
            'price_ex_vat',
            'vat (20%)',
            'image'
        ];

        $this->line("Collecting vehicles...");

        foreach ($vehicles as $v) {
            if ($make) {
                if (strtoupper($v->make()->name) == strtoupper($make)) {
                    $data[] = [
                        $v->registration,
                        ($v->make()->name." ".$v->model()->name." ".$v->derivative->name),
                        number_format((float) str_replace(',', '', $v->price_inc_vat) / 6 * 5, 2),
                        number_format((float) str_replace(',', '', $v->price_inc_vat) / 6, 2),
                        unserialize($v->images)[0]
                    ];
                }
            } else {
                $data[] = [
                    $v->registration,
                    ($v->make()->name." ".$v->model()->name." ".$v->derivative->name),
                    number_format((float) str_replace(',', '', $v->price_inc_vat) / 6 * 5, 2),
                    number_format((float) str_replace(',', '', $v->price_inc_vat) / 6, 2),
                    unserialize($v->images)[0]
                ];
            }
        }

        $this->line("Vehicles collected. Saving CSV...");

        $file = fopen($filename = 'exported_data_'.str_replace(' ', '', str_replace('-', '', str_replace(':','', Carbon::now()))).'.csv', 'w');

        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        fclose($file); 

        $this->info("CSV saved to ".$filename);
        $this->line("Starting FTP upload...");

        if ($connect = ftp_connect(env('ftp_server'))) {
            $this->line("Connected to FTP Server");
        } else {
            $this->error("Unable to connect to FTP server. Checker ftp_server in env. Exiting!");

            return 1;
        }

        if ($login = ftp_login($connect, env('ftp_username'), env('ftp_password'))) {
            $this->line("Logged in successfully");
        } else {
            $this->error("Unable to login to FTP server. Check ftp_username and ftp_password in env. Exiting!");

            return 1;
        }

        $this->line("Uploading file: ".$filename."...");

        if (ftp_put($connect, $filename, $filename, FTP_ASCII)) {
            $this->info("Upload successful!");
        } else {
            $this->error("Upload failed! Exiting!");

            return 1;
        }

        $this->line("FTP connection closing...");
        ftp_close($connect);

        $this->info("Export process complete");

        return 0;
    }
}
