<?php

namespace LaravelShared\Core\Controllers\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;

class XwmsApiHelper
{
    private static string $clientId;
    private static string $clientSecret;
    private static Client $httpClient;
    private static string $baseUri;
    private static string|null $redirectUri = null;
    public static function setup(): void
    {
        self::$baseUri = config("xwms.xwms_api_url", env("XWMS_API_URI"));
        self::$clientId = config("xwms.client_id", env("XWMS_CLIENT_ID"));
        self::$clientSecret = config("xwms.client_secret", env("XWMS_CLIENT_SECRET"));
        self::$httpClient = new Client([
            'base_uri' => self::$baseUri,
            'timeout'  => config("xwms.xwms_api_timeout", 10),
        ]);
    }

    protected static function postToEndpoint(string $endpoint, array $payload): array
    {
        if (!self::$httpClient || !self::$clientId || !self::$clientSecret) {
            throw new Exception('XwmsApiHelper not initialized. Make sure ENV XWMS_CLIENT_ID and XWMS_CLIENT_SECRET are set.');
        }

        try {
            $payload['redirect_url'] = $payload['redirect_url'] ??= config("xwms.client_redirect", env("XWMS_REDIRECT_URI"));
            $response = self::$httpClient->post($endpoint, [
                'headers' => [
                    'X-Client-Id' => self::$clientId,
                    'X-Client-Secret' => self::$clientSecret,
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $json = json_decode((string) $response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON response");
            }

            return $json;
        } catch (RequestException $e) {
            $msg = $e->hasResponse() ? (string) $e->getResponse()->getBody() : $e->getMessage();
            throw new Exception("API request to {$endpoint} failed: " . $msg);
        }
    }

    public static function authenticateUser(array $data = []): self
    {
        self::setup();
        $response = (array) self::postToEndpoint("sign-token", $data);

        $instance = new self();
        if (isset($response['data']['url'])) {
            self::$redirectUri = $response['data']['url'];
        } elseif (isset($response['redirect_url'])) {
            self::$redirectUri = $response['redirect_url'];
        } else {
            throw new Exception("Could not get redirect Api: ".json_encode($response)."");
        }

        return $instance;
    }

    public static function getAuthenticateUser(array $data = []): array
    {
        self::setup();
        $token = request('token');
        $response = (array) self::postToEndpoint("sign-token-verify", array_merge(['token' => $token], $data));
        return $response;
    }

    public function redirect(): string|null
    {
        return self::$redirectUri;
    }

    public function auth()
    {
        self::authenticateUser();
        $uri = self::redirect();
        return redirect()->to($uri);
    }

    public function authValidate()
    {
        return self::getAuthenticateUser();
    }
}