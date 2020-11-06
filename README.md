## Welcome
Hello, the following CLI program is built in PHP using the new Laravel-zero Framework. A lightweight version of Laravel 8, built specifically to create CLI driven Apps.

## Usage
To use the software, simply enter the <i>php generate</i> command from within the root directory.
The generated CSV is named payroll_dates.csv and is located in the root directory.


## Features
<ul>
<li>Takes into account weekends</li>
<li>Queries gov webstie API for national holidays</li>
    <li>Easily automated using current date and up to date API</li>
</ul> 

## Technical
HTTP request performed using the guzzleHTTP plugin, which needs to be installed via composer if not already present.
<br>
Loose coupling of helper classes promotes reuse of helpers, data returned can be utilised in anyway, EG added to a DB. 

## License

This sofware and subsequent Laravel Zero are open-source softwares licensed under the MIT license.
