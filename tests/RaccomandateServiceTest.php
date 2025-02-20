<?php

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;
use JustSolve\Raccomandate\Facades\Raccomandate;
use JustSolve\Raccomandate\Models\DestinatarioCompanyItalia;
use JustSolve\Raccomandate\Models\DestinatarioPersonaItalia;
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

        Config::set('raccomandate.base_uri', env('RACCOMANDATE_BASE_URI', 'baba'));
        Config::set('raccomandate.api_key', env('RACCOMANDATE_API_KEY', 'bubu'));

        self::$responseFile = new SplFileObject(__DIR__ . '/../response.json', 'w');
        self::$accettazioneFile = new SplFileObject(__DIR__ . '/../accettazione.pdf', 'w');
        self::$archiviazioneFile = new SplFileObject(__DIR__ . '/../archiviazione.pdf', 'w');
    }

    public function testListRaccomandate(): void
    {
        $response = Raccomandate::listRaccomandate();
        if (!$response) {
            Raccomandate::createRaccomandata(self::$data);
            $response = Raccomandate::listRaccomandate();
        }
        $this->assertNotNull($response);
        $this->assertTrue($response['success']);
        $this->assertNotEmpty($response['data'][0]);
        self::$responseFile->fwrite(json_encode($response));
    }

    public function testCreateRaccomandata(): void
    {
        $response = Raccomandate::createRaccomandata(self::$data);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('id', $response['data'][0]);
        sleep(30);
    }

    public function testCreateRaccomandataWithMittentePersona(): void
    {
        self::$data['mittente'] = new MittentePersona('Miriam', 'Fadda', 'via', 'Boccardo', '6', 'Moncalieri', '10024', 'TO', 'IT', 'info@faddastudiolegale.it', 'Avv.');
        $response = Raccomandate::createRaccomandata(self::$data);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('id', $response['data'][0]);
        $this->assertArrayHasKey('dug', $response['data'][0]['mittente']);
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
        sleep(30);
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

    public function testGetArchiviazioneFromRaccomandata(): void
    {
        $mittente = new MittenteCompany("bububello s.r.l. di bubu bello", "Via", "Dante Alighieri", "1", "Carpi", "41012", "MO", "IT", "john.doe@openapi.it");
        $dest1 = new DestinatarioPersonaItalia('Margherita', 'Battaglia', 'via', 'posta', '25', 'Mirandola', '41037', 'mo', 'italia');
        $destinatari = [$dest1];

        $documento = ["example document"];

        $opzioni = json_decode(
            '{
                "fronteretro": false,
                "colori": false,
                "ar": true,
                "autoconfirm": false
            }'
        );

        $data = [
            'mittente' => $mittente,
            'destinatari' => $destinatari,
            'documento' => $documento,
            'opzioni' => $opzioni
        ];

        $response = Raccomandate::createRaccomandata($data);
        $requestArray = [
            'id' => $response['data'][0]['id'],
            'destinatari' => $response['data'][0]['destinatari']
        ];
        $body = Raccomandate::getArchiviazioneFromRaccomandata($requestArray);
        $this->assertStringStartsWith('%PDF-', $body);

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
        $mittente = new MittenteCompany("bububello s.r.l. di bubu bello", "Via", "Dante Alighieri", "1", "Carpi", "41012", "MO", "IT", "john.doe@openapi.it");
        $dest1 = new DestinatarioPersonaItalia('Margherita', 'Battaglia', 'via', 'posta', '25', 'Mirandola', '41037', 'mo', 'italia');
        $destinatari = [$dest1];

        $documento = ["example document"];

        $opzioni = json_decode(
            '{
                "fronteretro": false,
                "colori": false,
                "ar": true,
                "autoconfirm": false
            }'
        );

        $data = [
            'mittente' => $mittente,
            'destinatari' => $destinatari,
            'documento' => $documento,
            'opzioni' => $opzioni
        ];

        $response = Raccomandate::createRaccomandata($data);
        $validId = $response['data'][0]['id'];
        $body = Raccomandate::getArchiviazioneFromId($validId);
        $this->assertStringStartsWith('%PDF-', $body);

        $notValidId = '679a745c322036bb22069f64';
        $this->expectExceptionMessage('N. destinatari diverso da 1.');
        $body = Raccomandate::getArchiviazioneFromId($notValidId);
    }
}
