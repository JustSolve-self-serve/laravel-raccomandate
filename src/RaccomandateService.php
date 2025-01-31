<?php

namespace JustSolve\Raccomandate;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RaccomandateService
{
    protected Client $client;
    protected string $baseUri;
    protected ?string $apiKey;

    public function __construct(string $baseUri, ?string $apiKey)
    {
        $this->baseUri = $baseUri;
        $this->apiKey = $apiKey;

        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'timeout'  => 30.0,
            'verify'   => false,
        ]);
    }

    /**
     * Example of how you might attach the API Key 
     * or Bearer token to each request (if required).
     */
    private function headers(): array
    {
        return [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            // If your API key is a bearer token, you might do:
            'Authorization' => 'Bearer ' . $this->apiKey,
            // or if it is a custom header:
            // 'x-api-key' => $this->apiKey,
        ];
    }

    /**
     * List Raccomandate (GET /raccomandate).
     */
    public function listRaccomandate(): array | null
    {
        try {
            $response = $this->client->get('/raccomandate', [
                'headers' => $this->headers(),
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            // Here you could throw a custom exception or handle it
            throw $e;
        }
    }

    /**
     * Create a new Raccomandata (POST /raccomandate).
     * $data must follow the schema required by the API.
     */
    public function createRaccomandata(array $data): array
    {
        try {
            $response = $this->client->post('/raccomandate', [
                'headers' => $this->headers(),
                'json'    => $data,
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    /**
     * Retrieve details of a specific Raccomandata (GET /raccomandate/{id}).
     */
    public function getRaccomandata(string $raccomandataId, array $queryParams = []): array
    {
        try {
            $response = $this->client->get("/raccomandate/{$raccomandataId}", [
                'headers' => $this->headers(),
                'query'   => $queryParams,
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    /**
     * Confirm a Raccomandata (PATCH /raccomandate/{id}).
     */
    public function confirmRaccomandata(string $raccomandataId, bool $confirmed = true): array
    {
        try {
            $response = $this->client->patch("/raccomandate/{$raccomandataId}", [
                'headers' => $this->headers(),
                'json'    => ['confirmed' => $confirmed],
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    /**
     * Download archiviazione (GET /raccomandate/{id}/destinatari/{IdDestinatario}/archiviazione).
     * This likely returns a PDF file or a binary. 
     * If the server returns a raw PDF, you’ll want to handle it accordingly.
     */
    public function downloadArchiviazione(string $raccomandataId, string $destinatarioId): ?string
    {
        try {
            $response = $this->client->get("/raccomandate/{$raccomandataId}/destinatari/{$destinatarioId}/archiviazione", [
                'headers' => $this->headers(),
                // Might need to set 'stream' => true if you want to handle it as a stream
            ]);

            // If the response is indeed a PDF (binary), you can do something like:
            // return $response->getBody()->getContents();
            // Or you might just return it raw to the caller.
            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    /**
     * Download accettazione (GET /raccomandate/{id}/accettazione).
     * Similar to downloadArchiviazione – returns PDF if present.
     */
    public function downloadAccettazione(string $raccomandataId): ?string
    {
        try {
            $response = $this->client->get("/raccomandate/{$raccomandataId}/accettazione", [
                'headers' => $this->headers(),
            ]);

            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
}
