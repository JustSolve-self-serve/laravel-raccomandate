<?php

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;
use JustSolve\Raccomandate\Facades\Raccomandate;
use JustSolve\Raccomandate\Models\DestinatarioCompanyItalia;
use JustSolve\Raccomandate\Models\DestinatarioPersonaItalia;
use JustSolve\Raccomandate\Models\MittenteCompany;

class RaccomandateServiceTest extends TestCase
{
    private static array $data;

    public static function setupBeforeClass(): void
    {
        $mittente = new MittenteCompany("bububello s.r.l. di bubu bello", "Via", "Dante Alighieri", "1", "Carpi", "41012", "MO", "IT", "john.doe@openapi.it");

        $dest1 = new DestinatarioPersonaItalia('Margherita', 'Battaglia', 'via', 'posta', '25', 'Mirandola', '41037', 'mo', 'italia');
        $dest2 = new DestinatarioCompanyItalia('BubbaGump s.r.l. di Bubba', 'via', 'fasulla', '7', 'carpi', '41012', 'mo', 'it');

        $destinatari = [$dest1, $dest2];

        $documento = ["example document"];

        $opzioni = json_decode(
            '{
                "fronteretro": false,
                "colori": false,
                "ar": true,
                "autoconfirm": false
            }'
        );

        $callback = ['url' => 'http://127.0.0.1:8000/api/state'];

        self::$data = [
            'mittente' => $mittente,
            'destinatari' => $destinatari,
            'documento' => $documento,
            'opzioni' => $opzioni,
            'callback' => $callback
        ];
    }

    public function setUp(): void
    {
        parent::setUp();

        Config::set('raccomandate.base_uri', 'https://api.bubba.it/v1');

        // Fake all HTTP requests with deterministic responses based on URL
        Http::fake(function ($request) {
            $url = $request->url();
            $method = strtoupper($request->method());

            // Normalize to work with whatever base_uri is configured
            // Extract path after the base segment 'raccomandate'
            $path = $url;
            if (($pos = strpos($url, '/raccomandate')) !== false) {
                $path = substr($url, $pos);
            }

            // List raccomandate
            if ($method === 'GET' && $path === '/raccomandate') {
                return Http::response([
                    'success' => true,
                    'data' => [
                        ['id' => 'id-123']
                    ],
                ], 200);
            }

            // Create raccomandata
            if ($method === 'POST' && $path === '/raccomandate') {
                return Http::response([
                    'success' => true,
                    'data' => [[
                        'id' => 'id-123',
                        'confirmed' => false,
                        'state' => 'CREATED',
                        'destinatari' => [['id' => 'dest-1']],
                    ]],
                ], 201);
            }

            // Get raccomandata details
            if ($method === 'GET' && preg_match('#^/raccomandate/([^/]+)$#', $path, $m)) {
                $id = $m[1];
                if ($id === 'id-123') {
                    return Http::response([
                        'success' => true,
                        'data' => [
                            'id' => 'id-123',
                            'destinatari' => [['id' => 'dest-1']],
                        ],
                    ], 200);
                }
                if ($id === '679a745c322036bb22069f64') {
                    // Specific ID used in tests to trigger >1 destinatari
                    return Http::response([
                        'success' => true,
                        'data' => [
                            'id' => $id,
                            'destinatari' => [['id' => 'd1'], ['id' => 'd2']],
                        ],
                    ], 200);
                }
                // Unknown id -> 404 to trigger RequestException via ->throw()
                return Http::response(['message' => 'Not Found'], 404);
            }

            // Confirm raccomandata
            if ($method === 'PATCH' && preg_match('#^/raccomandate/([^/]+)$#', $path, $m)) {
                $id = $m[1];
                if ($id === 'id-123') {
                    return Http::response([
                        'success' => true,
                        'data' => [[
                            'id' => 'id-123',
                            'confirmed' => true,
                            'state' => 'CONFIRMED',
                        ]],
                    ], 200);
                }
                return Http::response(['message' => 'Not Found'], 404);
            }

            // Download accettazione
            if ($method === 'GET' && preg_match('#^/raccomandate/[^/]+/accettazione$#', $path)) {
                return Http::response('%PDF-FAKE-ACCETTAZIONE', 200, ['Content-Type' => 'application/pdf']);
            }

            // Download archiviazione
            if ($method === 'GET' && preg_match('#^/raccomandate/[^/]+/destinatari/[^/]+/archiviazione$#', $path)) {
                return Http::response('%PDF-FAKE-ARCHIVIAZIONE', 200, ['Content-Type' => 'application/pdf']);
            }

            // Fallback
            return Http::response(['message' => 'Unhandled fake endpoint'], 500);
        });
    }

    public function testListRaccomandate(): void
    {
        $response = Raccomandate::listRaccomandate();
        
        $this->assertNotNull($response);
        $this->assertTrue($response['success']);
        $this->assertNotEmpty($response['data'][0]);
        $this->assertArrayHasKey('id', $response['data'][0]);
        $this->assertEquals('id-123', $response['data'][0]['id']);
    }

    public function testCreateRaccomandata(): void
    {
        $response = Raccomandate::createRaccomandata(self::$data);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('id', $response['data'][0]);
        $this->assertEquals('id-123', $response['data'][0]['id']);
    }

    public function testGetRaccomandata(): void
    {
        $validId = 'id-123';
        $this->assertTrue(Raccomandate::getRaccomandata($validId)['success']);
        $this->assertEquals($validId, Raccomandate::getRaccomandata($validId)['data']['id']);
        
        $nullId = $validId . 'bubba';
        $this->expectException(RequestException::class);
        Raccomandate::getRaccomandata($nullId);
    }

    public function testConfirmRaccomandata(): void 
    {
        $validId = 'id-123';
        $newResponse = Raccomandate::confirmRaccomandata($validId);
        $this->assertTrue($newResponse['success']);
        $this->assertTrue($newResponse['data'][0]['confirmed']);
        $this->assertEquals('CONFIRMED', $newResponse['data'][0]['state']);
    }

    public function testDownloadAccettazione(): void 
    {
        $body = Raccomandate::downloadAccettazione('id-123');
        $this->assertEquals('%PDF-FAKE-ACCETTAZIONE', $body);
    }

    public function testDownloadArchiviazione(): void
    {
        $body = Raccomandate::downloadArchiviazione('id-123', '679a745a322036bb22069f62');
        $this->assertEquals('%PDF-FAKE-ARCHIVIAZIONE', $body);
    }

    public function testGetArchiviazioneFromRaccomandata(): void
    {
        $requestArray = [
            'id' => 'id-123',
            'destinatari' => [['id' => '679a745a322036bb22069f62']]
        ];
        $body = Raccomandate::getArchiviazioneFromRaccomandata($requestArray);
        $this->assertEquals('%PDF-FAKE-ARCHIVIAZIONE', $body);

        $requestArray['destinatari'][1] = ['id' => '0'];
        $this->expectExceptionMessage('N. destinatari diverso da 1.');
        $body = Raccomandate::getArchiviazioneFromRaccomandata($requestArray);
    }

    public function testGetArchiviazioneFromRaccomandataMissingDestinatarioId(): void
    {
        $requestArray = [
            'id' => '0',
            'destinatari' => [['di' => 'wrong id key']]
        ];
        $this->expectExceptionMessage('Id destinatario mancante.');
        Raccomandate::getArchiviazioneFromRaccomandata($requestArray);
    }

    public function testGetArchiviazioneFromRaccomandataMissingId(): void
    {
        $requestArray = [
            'destinatari' => [['di' => 'wrong id key']]
        ];
        $this->expectExceptionMessage('Chiavi "id" e/o "destinatari" mancanti.');
        Raccomandate::getArchiviazioneFromRaccomandata($requestArray);
    }

    public function testGetArchiviazioneFromRaccomandataMissingDestinatari(): void
    {
        $requestArray = [
            'id' => '0'
        ];
        $this->expectExceptionMessage('Chiavi "id" e/o "destinatari" mancanti.');
        Raccomandate::getArchiviazioneFromRaccomandata($requestArray);
    }

    public function testGetArchiviazioneFromId(): void
    {
        $body = Raccomandate::getArchiviazioneFromId('id-123');
        $this->assertEquals('%PDF-FAKE-ARCHIVIAZIONE', $body);

        $notValidId = '679a745c322036bb22069f64';
        $this->expectExceptionMessage('N. destinatari diverso da 1.');
        $body = Raccomandate::getArchiviazioneFromId($notValidId);
    }
}
