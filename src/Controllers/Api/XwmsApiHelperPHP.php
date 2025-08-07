<?php

namespace LaravelShared\Core\Controllers\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class XwmsApiHelperPHP
{
    private string $clientId;
    private string $clientSecret;
    private Client $httpClient;
    private string $baseUri = 'https://xwms.nl/api/';

    public function __construct(string $clientId, string $clientSecret, ?Client $httpClient = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->httpClient = $httpClient ?: new Client([
            'base_uri' => $this->baseUri,
            'timeout'  => 10,
        ]);
    }

    protected function postToEndpoint(string $endpoint, array $payload): array
    {
        try {
            $response = $this->httpClient->post($endpoint, [
                'headers' => [
                    'X-Client-Id' => $this->clientId,
                    'X-Client-Secret' => $this->clientSecret,
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $json = json_decode((string) $response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON response");
            }

            return $json;
        } catch (RequestException $e) {
            $msg = $e->hasResponse() ? (string) $e->getResponse()->getBody() : $e->getMessage();
            throw new \Exception("API request to {$endpoint} failed: " . $msg);
        }
    }

    public function authenticateUser(array $data = []): array
    {
        return $this->postToEndpoint("sign-token", $data);
    }

    public function getAuthenticateUser(string $token, array $data = []): array
    {
        return $this->postToEndpoint("sign-token-verify", array_merge(['token' => $token], $data));
    }
}
