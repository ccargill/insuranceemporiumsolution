<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\DateHelper;

class GenerateDatesTest extends TestCase{

    protected $dates;

    function setUp(): void
    {
        // Instantiate Class
        $this->dates = new DateHelper();
    }

    function tearDown(): void
    {
        unset($this->dates);
    }

    function generateArray(){
        // Test the date helper class function pay_date generates an array
        $result = $this->dates->pay_date(1604669835);
        $this->assertContains('Dec/20', $result);
    }
}

