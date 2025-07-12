<?php

namespace LaravelShared\Core\Services;

use Stevebauman\Location\Facades\Location;

class IpService
{
    public static function getUserAgent(): string
    {
        return substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'), 0, 500);
    }

    public static function getIpAddress(): string
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        }

        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    public static function isLocal(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return
                preg_match('/^(127\.|10\.|192\.168\.|172\.(1[6-9]|2[0-9]|3[0-1]))/', $ip) ||
                $ip === '169.254.0.0';
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return in_array($ip, ['::1']) || str_starts_with($ip, 'fe80:') || str_starts_with($ip, 'fc00:');
        }

        return false;
    }

    public static function getIpAdr(string|null $ip = null): array
    {
        $ip = $ip ?: self::getIpAddress();
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return [
                'ipaddress' => '217.154.83.228',
                'status' => 200,
                'city' => 'NoMansCity',
                'region' => 'NoMansLand',
                'countrycode' => 'NL',
                'countryname' => 'No Mans Land',
                'lat' => '51.8562',
                'long' => '9.3651',
            ];
        }

        $position = Location::get($ip);

        if (!$position) {
            return [
                'ipaddress' => $ip,
                'status' => 206,
                'city' => '',
                'region' => '',
                'countrycode' => '',
                'countryname' => '',
                'btw' => null,
                'lat' => '',
                'long' => '',
            ];
        }

        return [
            'ipaddress' => $position->ip,
            'status' => 200,
            'city' => $position->cityName,
            'region' => $position->regionName,
            'countrycode' => $position->countryCode,
            'countryname' => $position->countryName,
            'lat' => $position->latitude,
            'long' => $position->longitude,
        ];
    }
}