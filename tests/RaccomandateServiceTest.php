<?php

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;
use JustSolve\Raccomandate\Facades\Raccomandate;

class RaccomandateServiceTest extends TestCase
{
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
        $mittente = json_decode(
            '{
                "titolo": "mr",
                "nome": "Roberto",
                "cognome": "Iorio",
                "dug": "Via",
                "indirizzo": "Dante Alighieri",
                "civico": "1",
                "comune": "Carpi",
                "cap": "41012",
                "provincia": "MO",
                "nazione": "IT",
                "email": "john.doe@openapi.it"
            }'
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
                "autoconfirm": true
            }'
        );

        $data =[
            'mittente' => $mittente,
            'destinatari' => $destinatari,
            'documento' => $documento,
            'opzioni' => $opzioni
        ];

        $this->assertNotNull(Raccomandate::createRaccomandata($data));
    }

    public function testGetRaccomandata(): void
    {
        $id = '679b593b664e13b51b02f63a';
        $nullId = '679b593b664e13b51b02f63b';
        
        $this->assertNotNull(Raccomandate::getRaccomandata($id));

        $this->expectException(ClientException::class);
        Raccomandate::getRaccomandata($nullId);
    }
}
