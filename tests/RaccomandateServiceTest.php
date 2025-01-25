<?php

use PHPUnit\Framework\TestCase;
use JustSolve\Raccomandate\RaccomandateService; 

class RaccomandateServiceTest extends TestCase
{
    public function testListRaccomandate()
    {
        $raccomandateService = new RaccomandateService();
        $this->assertNull($raccomandateService->listRaccomandate());
    }
}
