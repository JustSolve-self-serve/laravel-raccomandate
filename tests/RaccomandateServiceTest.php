<?php

use PHPUnit\Framework\TestCase;
use JustSolve\Raccomandate\RaccomandateService; 

class RaccomandateServiceTest extends TestCase
{
    public function testListRaccomandate()
    {
        $raccomandateService = new RaccomandateService('https://fake-url.com', 'fake-api-key');
        $this->assertNotNull($raccomandateService->listRaccomandate());
    }
}
