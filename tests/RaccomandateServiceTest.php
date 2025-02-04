<?php

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;
use JustSolve\Raccomandate\Facades\Raccomandate;
use JustSolve\Raccomandate\Models\MittenteCompany;
use JustSolve\Raccomandate\Models\MittentePersona;
use JustSolve\Raccomandate\Models\Raccomandata;

class RaccomandateServiceTest extends TestCase
{
    private static array $data;
    private static SplFileObject $responseFile;
    private static SplFileObject $accettazioneFile;
    private static SplFileObject $archiviazioneFile;

    public static function setupBeforeClass(): void
    {
        $mittente = new MittenteCompany(
            "bububello s.r.l. di bubu bello",
            "Via",
            "Dante Alighieri",
            "1",
            "Carpi",
            "41012",
            "MO",
            "IT",
            "john.doe@openapi.it"
        );

        $dest1 = json_decode(
            '{
                "nome": "Mario",
                "cognome": "Rossi",
                "co": "OPENAPI SRL",
                "dug": "via",
                "indirizzo": "Dante Alighieri",
                "civico": "6",
                "comune": "Roma",
                "cap": "00118",
                "provincia": "RM",
                "nazione": "Italia"
            }'
        );

        $dest2 = json_decode(
            '{
                "nome": "Mario",
                "cognome": "Rossi",
                "co": "OPENAPI SRL",
                "dug": "piazza",
                "indirizzo": "San Giovanni",
                "civico": "6",
                "comune": "roma",
                "cap": "00118",
                "provincia": "RM",
                "nazione": "IT"
            }'
        );

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

        self::$data = [
            'mittente' => $mittente,
            'destinatari' => $destinatari,
            'documento' => $documento,
            'opzioni' => $opzioni
        ];
    }

    public function setUp(): void
    {
        parent::setUp();

        Config::set('raccomandate.base_uri', env('RACCOMANDATE_BASE_URI', 'baba'));
        Config::set('raccomandate.api_key', env('RACCOMANDATE_API_KEY', 'bubu'));

        self::$responseFile = new SplFileObject(__DIR__ . '/../response.json', 'w');
        self::$accettazioneFile = new SplFileObject(__DIR__ . '/../accettazione.pdf', 'w');
        self::$archiviazioneFile = new SplFileObject(__DIR__ . '/../archiviazione.pdf', 'w');
    }

    public function testListRaccomandate(): void
    {
        $response = Raccomandate::listRaccomandate();
        $this->assertNotNull($response);
        self::$responseFile->fwrite(json_encode($response));
    }

    public function testCreateRaccomandata(): void
    {
        $data = self::$data;
        $response = Raccomandate::createRaccomandata($data);
        $this->assertTrue($response['success']);
    }

    public function testGetRaccomandata(): void
    {
        $response = Raccomandate::createRaccomandata(self::$data);
        $validId = $response['data'][0]['id'];
        $this->assertTrue(Raccomandate::getRaccomandata($validId)['success']);
        
        $nullId = $validId . 'bubba';
        $this->expectException(ClientException::class);
        Raccomandate::getRaccomandata($nullId);
    }

    public function testConfirmRaccomandata(): void 
    {
        $response = Raccomandate::createRaccomandata(self::$data);
        $this->assertFalse($response['data'][0]['confirmed']);
        $this->assertNotEquals('CONFIRMED', $response['data'][0]['state']);

        $validId = $response['data'][0]['id'];
        $newResponse = Raccomandate::confirmRaccomandata($validId);
        $this->assertTrue($newResponse['success']);
        $this->assertTrue($newResponse['data'][0]['confirmed']);
        $this->assertEquals('CONFIRMED', $newResponse['data'][0]['state']);
    }

    public function testDownloadAccettazione(): void 
    {
        $validId = '679a745c322036bb22069f64';
        $body = Raccomandate::downloadAccettazione($validId);
        $this->assertStringStartsWith('%PDF-', $body);
        self::$accettazioneFile->fwrite($body);
    }

    public function testDownloadArchiviazione(): void
    {
        $validId = '679a745c322036bb22069f64';
        $validIdDestinatario = '679a745a322036bb22069f62';
        $body = Raccomandate::downloadArchiviazione($validId, $validIdDestinatario);
        $this->assertStringStartsWith('%PDF-', $body);
        self::$archiviazioneFile->fwrite($body);
    }
}
