<?php


namespace App\Helpers;


class CsvHelper
{
    // DESCRIPTION: Write array of pay dates to a CSV file
    // ACCEPTS: 2D ARRAY Dates
    // RETURNS STRING Filename
    public function write_to_csv($dates_array){

        // OPEN csv, creates from scratch if not found in write (binary) mode
        $fp = fopen('payroll_dates.csv', 'wb');
        foreach($dates_array as $dates){
            // write new line to CSV, seperating each element in current array with ,
            try {
                fputcsv($fp, $dates, ',');
            }
            catch(\Exception $e) {
                return $e->getMessage();
            }
        }

        // Close file stream once completed
        fclose($fp);

        // RETURN true / success status if writing to file didnt produce exception
        return true;
    }
}
