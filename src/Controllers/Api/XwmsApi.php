<?php

namespace LaravelShared\Core\Controllers\Api;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use LaravelShared\Core\Controllers\Controller;
use LaravelShared\Core\Helpers\XwmsSecurity;

class XwmsApi extends Controller
{
    private $apiUrl;
    private $sharedSecret;
    protected $models;

    public function __construct()
    {
        $this->apiUrl = env('XWMS_API_VPN_URL');
        $this->sharedSecret = env('XWMS_SHARED_SECRET');
    }

    public function sendRequest(string $endpoint, array $data = [], string $method = 'POST')
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('User not authenticated.');
        }
    
        // Bouw de payload
        $securePayload = XwmsSecurity::sign([
            'xwms_id' => $user->xwms_id,
            'data' => $data,
        ]);
    
        $url = rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');
    
        // Standaard headers
        $request = Http::timeout(5)
            ->asForm(); // We verzenden nog steeds als Form om compatibel te blijven
    
        // Kies juiste method
        $response = match (strtoupper($method)) {
            'GET'    => $request->get($url, ['payload' => $securePayload]),
            'POST'   => $request->post($url, ['payload' => $securePayload]),
            'PUT'    => $request->put($url, ['payload' => $securePayload]),
            'DELETE' => $request->delete($url, ['payload' => $securePayload]),
            default  => throw new \Exception("Unsupported HTTP method [$method]"),
        };
    
        $json = $response->json();

        if (!is_array($json)) {
            throw new \Exception('Invalid JSON response received.');
        }
    
        return $json;
    }

    public function verifiedUser()
    {
        $result = $this->safeExecute(function () {
            $response = $this->sendRequest("/user/isverified");
            if ($response['status'] === 'success') return true;
            throw new \Exception('Something went wrong.' . $response['message']);
        }, false, ['return' => true]);

        if ($result === true){
            return true;
        }
        return false;
    }

    public function checkProviders()
    {
        $this->safeExecute(function () {
            $response = $this->sendRequest("/user/providers");
            if (isset($response['data']) && is_array($response['data'])) {
                $userId = Auth::id();

                foreach ($response['data'] as $providerData) {
                    $providerModel = $this->models['provider'];
        
                    $providerModel->updateOrCreate(
                        ['user_id' => $userId, 'email' => $providerData['email'], 'provider' => $providerData['provider']],
                        [
                            'user_id' => $userId,
                            'provider_id' => $providerData['provider_id'],
                            'setting_name' => $providerData['setting_name'],
                            'provider' => $providerData['provider'],
                            'email' => $providerData['email'],
                            'access_token' => $providerData['access_token'],
                            'refresh_token' => $providerData['refresh_token'],
                            'created_at' => $providerData['created_at'],
                            'updated_at' => $providerData['updated_at'],
                        ]
                    );
                }
            }
        }, false, ['return' => true]);
    }
    

    public function logout()
    {
        $result = $this->safeExecute(function () {
            return $this->sendRequest("/logout");
        }, false, ['return' => true]);
    }
}
