<?php

namespace LaravelShared\Core\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use LaravelShared\Core\Controllers\Controller;

class XwmsSSOController extends Controller
{
    public function validateToken(Request $request)
    {
        $result = $this->safeExecute(function () use ($request) {
            $token = $request->bearerToken() ?: $request->input('token');
    
            if (!$token) {
                throw new Exception('No token provided.');
            }
    
            $vpnUrl = env('XWMS_API_VPN_URL');
    
            try {
                $response = Http::timeout(3)->asForm()->post($vpnUrl . $this->validateTokenUrl, [
                    'token' => $token,
                ]);
            } catch (Exception $e) {
                throw new Exception('Could not connect to XWMS API. Please try again later.');
            }

            $data = $response->json();
            // dd($response->body()); // for debug

            if (!is_array($data) || isset($data['status']) && $data['status'] !== 'success') {
                throw new Exception($data['message'] ?? 'Invalid token response.');
            }

            if (!isset($data['xwms_id'])) {
                throw new Exception('Invalid token payload.');
            }

            return $data;

            // EXAMPLE

            // $user = User::firstOrCreate(
            //     ['xwms_id' => $data['xwms_id']],
            //     [
            //         'username' => $data['user']['username'],
            //         'email' => $data['user']['email'],
            //         'password' => bcrypt(str()->random(32)),
            //         'country' => IpService::getIpAdr()['countrycode'] ?? 'NL',
            //         'online_date' => now()
            //     ]
            // );

            // Auth::login($user);

            // $successMessage = config("xwms.sign.successMessage");
            // $url = config("xwms.sign.successUrl");

            // $params = http_build_query([
            //     'xwmsmessage_status' => 'success',
            //     'xwmsmessage_message' => $successMessage,
            // ]);

            // return redirect()->to("{$url}?{$params}");
        }, true, [
            'return' => true
        ]);

        return $this->handleSafeRedirectOrReturn($result, config("xwms.sign.fallbackUrl", "/"));
    }

    protected function handleSafeRedirectOrReturn($result, $fallbackUri)
    {
        if (is_array($result) && isset($result['returnWasOn'])) {
            $redirectUrl = $fallbackUri . (parse_url($fallbackUri, PHP_URL_QUERY) ? '&' : '?') . http_build_query([
                'xwmsmessage_status' => $result['status'] ?? 'error',
                'xwmsmessage_message' => substr($result['message'] ?? 'Unknown error.', 0, 255),
            ]);
            return redirect()->away($redirectUrl);
        }

        return $result;
    }
}
