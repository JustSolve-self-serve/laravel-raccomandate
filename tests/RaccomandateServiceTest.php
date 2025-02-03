<?php

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;
use JustSolve\Raccomandate\Facades\Raccomandate;
use JustSolve\Raccomandate\Models\MittenteCompany;
use JustSolve\Raccomandate\Models\MittentePersona;

class RaccomandateServiceTest extends TestCase
{
    private static array $data;

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
    }

    public function testListRaccomandate(): void
    {
        $this->assertNotNull(Raccomandate::listRaccomandate());
    }

    public function testCreateRaccomandata(): void
    {
        $data = self::$data;
        $response = Raccomandate::createRaccomandata($data);
        $this->assertTrue($response['success'], 'createRaccomandata successful, id: ' . $response['data'][0]['id'] . '\n');
    }

    public function testGetRaccomandata(): void
    {
        $id = '679b593b664e13b51b02f63a';
        $nullId = '679b593b664e13b51b02f63b';
        
        $this->assertNotNull(Raccomandate::getRaccomandata($id));

        $this->expectException(ClientException::class);
        Raccomandate::getRaccomandata($nullId);
    }

    public function testConfirmRaccomandata(): void 
    {

    }
}
