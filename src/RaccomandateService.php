<?php

namespace JustSolve\Raccomandate;

use Exception;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;

class RaccomandateService
{
    protected HttpFactory $http;
    protected string $baseUri;
    protected ?string $apiKey;

    public function __construct(HttpFactory $http)
    {
        $this->baseUri = config('raccomandate.base_uri');
        $this->apiKey = config('raccomandate.api_key');
        $this->http = $http;
    }

    /**
     * Example of how you might attach the API Key 
     * or Bearer token to each request (if required).
     */
    private function headers(): array
    {
        return [
            'Accept'       => 'application/json, application/pdf',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Build a configured PendingRequest for the API.
     */
    private function request(): PendingRequest
    {
        $request = $this->http
            ->baseUrl($this->baseUri)
            ->withHeaders($this->headers())
            ->timeout(30)
            ->withoutVerifying();

        if (!empty($this->apiKey)) {
            $request = $request->withToken($this->apiKey);
        }

        return $request;
    }

    /**
     * List Raccomandate (GET /raccomandate).
     */
    public function listRaccomandate(): array | null
    {
        try {
            $response = $this->request()->get('/raccomandate')->throw();
            return $response->json();
        } catch (RequestException $e) {
            throw $e; // Preserve failure for caller/tests
        }
    }

    /**
     * Create a new Raccomandata (POST /raccomandate).
     * $data must follow the schema required by the API.
     */
    public function createRaccomandata(array $data): array
    {
        try {
            $response = $this->request()->post('/raccomandate', $data)->throw();
            return $response->json();
        } catch (RequestException $e) {
            throw $e;
        }
    }

    /**
     * Retrieve details of a specific Raccomandata (GET /raccomandate/{id}).
     */
    public function getRaccomandata(string $raccomandataId, array $queryParams = []): array
    {
        try {
            $response = $this->request()->get("/raccomandate/{$raccomandataId}", $queryParams)->throw();
            return $response->json();
        } catch (RequestException $e) {
            throw $e;
        }
    }

    /**
     * Confirm a Raccomandata (PATCH /raccomandate/{id}).
     */
    public function confirmRaccomandata(string $raccomandataId, bool $confirmed = true): array
    {
        try {
            $response = $this->request()->patch("/raccomandate/{$raccomandataId}", [
                'confirmed' => $confirmed,
            ])->throw();

            return $response->json();
        } catch (RequestException $e) {
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
            $response = $this->request()->get("/raccomandate/{$raccomandataId}/destinatari/{$destinatarioId}/archiviazione")->throw();
            return $response->body();
        } catch (RequestException $e) {
            throw $e;
        }
    }

    public function getArchiviazioneFromRaccomandata(array $raccomandata): ?string
    {
        if (!array_key_exists('id', $raccomandata) || !array_key_exists('destinatari', $raccomandata)) {
            throw new Exception('Chiavi "id" e/o "destinatari" mancanti.');
        }
        foreach ($raccomandata['destinatari'] as $destinatario) {
            if (!array_key_exists('id', $destinatario)) {
                throw new Exception('Id destinatario mancante.');
            }
        }
        if (count($raccomandata['destinatari']) != 1) {
            throw new Exception('N. destinatari diverso da 1.');
        }

        $raccomandataId = $raccomandata['id'];
        $destinatarioId = $raccomandata['destinatari'][0]['id'];

        return $this->downloadArchiviazione($raccomandataId, $destinatarioId);
    }

    public function getArchiviazioneFromId(string $raccomandataId): ?string
    {
        try {
            $raccomandata = $this->getRaccomandata($raccomandataId);
            return $this->getArchiviazioneFromRaccomandata($raccomandata['data']);
        } catch (Exception $e) {
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
            $response = $this->request()->get("/raccomandate/{$raccomandataId}/accettazione")->throw();
            return $response->body();
        } catch (RequestException $e) {
            throw $e;
        }
    }
}
