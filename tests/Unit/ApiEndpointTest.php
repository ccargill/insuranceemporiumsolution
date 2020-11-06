<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

// TEST - Check API Endpoint is reachable for gathering holiday dates
class ApiEndpointTest extends TestCase{

    private $http;

    public function testBasicTest()
    {
        $this->http = new Client(['base_uri' => 'https://www.gov.uk/bank-holidays.json']);

        $response = $this->http->request('GET');

        $this->assertEquals(200, $response->getStatusCode());

    }

}
