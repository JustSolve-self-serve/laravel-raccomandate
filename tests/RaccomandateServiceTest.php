<?php

use PHPUnit\Framework\TestCase;
use JustSolve\Raccomandate\RaccomandateService; 

class RaccomandateServiceTest extends TestCase
{
    public function testListRaccomandate()
    {
        $raccomandateService = new RaccomandateService('https://test.ws.ufficiopostale.com', '679381b8e784c9568800a621');
        $this->assertNull($raccomandateService->listRaccomandate());
    }
}
