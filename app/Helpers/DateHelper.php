<?php

namespace App\Helpers;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;



class DateHelper
{

    // DESCRIPTION: Check if current date is a UK holiday
    // ACCEPTS: STRING Date (Y-m-d)
    // RETURNS: BOOL
    public function is_holiday($date){

        // GET Request to UK Gov API, returns JSON array of past and future national UK holidays
        $endpoint = "https://www.gov.uk/bank-holidays.json";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $endpoint);

        $statusCode = $response->getStatusCode();
        $content = json_decode($response->getBody(), true);

        if($statusCode == 200){
            // Loop through the JSON object, to try and match the target date
            foreach($content['england-and-wales']['events'] as $event){
                $target_year = explode("-", $date);
                $current_year = explode("-", $event['date']);

                if($current_year[0] >= $target_year[0]){
                    // Ignores all the old year data returned in the response (before target date)
                    if($event['date'] == $date){
                        // If match, date is a national holiday return true
                        return true;
                    }
                }
            }
        }

        // No matches found, date is not a holiday, return false
        return false;
    }

    // DESCRIPTION: Generate dates for next 12 months for basic pay and bonus pay
    // ACCEPTS: INT Timestamp
    // RETURNS: 2D ARRAY Dates
    public function pay_date($target_date){

        // Initialise Data Store
        $year_dates_array = array();

        // SET start_date = next date to look at, date formatted Y-m-10 represents 10th of every month, eom_date represents final day of current month
        // Formatted as Y-m-t
        $next_date = date("Y-m-10", $target_date);
        $next_eom_date = date("Y-m-t", $target_date);


        // SET start month = 1 (current month)
        $current_month_counter = 1;

        // WHILE current month is not more than 12 (more than 1 year)
        while($current_month_counter <= 12){

            //////////////////////////////////////////////////////
            /// 1- Calculate Bonus Paydate
            /////////////////////////////////////////////////////

            // Initialise new date object to be able to increment or subtract days
            $bonus_pay_date = new \DateTime($next_date);

            // Ensures loop runs at least once, can be done with do-while
            $is_holiday = true;
            while($is_holiday){

                // Find what day of week date falls, returns 1-7 (MON-SUN)
                $bonus_weekday = date('N', strtotime($bonus_pay_date->format('Y-m-d')));

                // WHILE day is a weekend, (6-7)
                while($bonus_weekday >= 6){
                    if($bonus_weekday == 6){
                        // IF SAT, Modify date by 2 days to be following monday
                        $bonus_pay_date->modify("+2 Days");
                    }else if($bonus_weekday == 7){
                        // ELSE IF SUN, Modify date by one day to be following monday
                        $bonus_pay_date->modify("+1 Days");
                    }

                    // Find the weekday of the adjusted date 1-7 (MON-SUN)
                    $bonus_weekday = date('N', strtotime($bonus_pay_date->format('Y-m-d')));
            }

            // Check if the new date is a holiday before looping again
            $is_holiday = $this->is_holiday($bonus_pay_date->format('Y-m-d'));

                // IF the date is a holiday (true), increment date, next loop will check if new date falls on weekend
                if ($is_holiday) {
                    $bonus_pay_date->modify("+1 days");
                }

            }

            //////////////////////////////////////////////////////
            /// 2- Calculate Basic Paydate
            /////////////////////////////////////////////////////

            // SET basic_pay_date to be next eom date in the loop (next month)
            $basic_pay_date = new \DateTime($next_eom_date);

            $is_holiday = true;
            while($is_holiday){

                // Find weekday current date falls on (1-7) MON - SAT
                $basic_weekday = date('N', strtotime($basic_pay_date->format('Y-m-d')));

                // WHILE weekday is >= 6 (SAT or SUN)
                while($basic_weekday >= 6){
                    if($basic_weekday == 6){
                        // Date falls on a SAT, go back to FRI
                        $basic_pay_date->modify("-1 Days");
                    }else if($basic_weekday == 7){
                        // Date falls on SUN, go back to FRI
                        $basic_pay_date->modify("-2 Days");
                    }

                    // Find weekday of adjusted date
                    $basic_weekday = date('N', strtotime($basic_pay_date->format('Y-m-d')));
            }

            // Check if new date is holiday, return TRUE if so
            $is_holiday = $this->is_holiday($basic_pay_date->format('Y-m-d'));

                // IF new date is holiday, take one off date (next closest last day of month), loop again to check new date is not weekend
                if($is_holiday){
                    $basic_pay_date->modify("-1 days");
                }


            }

            // ADD new adjusted dates to array, in format Y-m-d along with current month in correct format, Array to be inserted into year_dates_array form 2D array
            $month_dates_array = array($basic_pay_date->format('M/y') ,$basic_pay_date->format('Y-m-d'), $bonus_pay_date->format('Y-m-d'));
            array_push($year_dates_array, $month_dates_array);

            // Make new date object, to modify current date increment it by one month
            $next_date = new \DateTime($next_date);
            $next_date = $next_date->modify("+1 month");
            // next_eom_date to be use in next loop
            $next_eom_date = $next_date->format('Y-m-t');
            $next_date = $next_date->format('Y-m-d');

            // Increment counter to ensure loop moves along
            $current_month_counter = $current_month_counter + 1;

        }

        // After all months calculated, RETURN 2D dates array
        return $year_dates_array;
    }
}
