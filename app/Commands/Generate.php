<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Helpers\DateHelper;
use App\Helpers\CsvHelper;

class Generate extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'generate';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate CSV file of payment date information for next 12 months.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // Instantiate DateHelper class
        $date_helper = new DateHelper();

        // Create new DateTime object to get current timestamp
        $date = new \DateTime();
        $dates_array = $date_helper->pay_date($date->getTimestamp());

        // Write the dates to the CSV
        $dates_csv = new CsvHelper();
        $result = $dates_csv->write_to_csv($dates_array);

        if($result){
            print_r("CSV Created");
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
