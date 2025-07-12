<?php

namespace LaravelShared\Core\Services;

use LaravelShared\Core\Services\IpService;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;

class MyService
{
    private Agent $agent;
    private array $ipService;
    private Request $request;
    public function __construct(Request $request)
    {
        $this->agent = new Agent();
        $this->request = $request;

        $this->ipService = IpService::getIpAdr();
    }
    
    public function getFirstPage(): string
    {
        return $this->request->fullUrl();
    }

    public function getReferUrl(): ?string
    {
        return $this->request->headers->get('referer');
    }

    public function getApp(): string
    {
        $host = parse_url($this->request->getHost(), PHP_URL_HOST) ?? $this->request->getHost();
        $hostParts = explode('.', $host);
        return count($hostParts) > 1 ? $hostParts[count($hostParts) - 2] : $hostParts[0];
    }
 
    public function getIpaddress()
    {
        return $this->ipService['ipaddress'];
    }

    public function getCountry()
    {
        return $this->ipService['countrycode'];
    }

    public function getCountryName()
    {
        return $this->ipService['countryname'];
    }

    public function getCity()
    {
        return $this->ipService['city'];
    }

    public function getRegion()
    {
        return $this->ipService['region'];
    }

    public function getLatAndLong()
    {
        $latAndLong = [
            'latitude' => $this->ipService['lat'],
            'longitude' => $this->ipService['long']
        ];
        return $latAndLong;
    }

    public function getIpStatus()
    {
        return $this->ipService['status'];
    }

    public function getDevice()
    {
        // Detect device type (mobile, tablet, desktop)
        if ($this->agent->isDesktop()) {
            return 'desktop';
        } elseif ($this->agent->isTablet()) {
            return 'tablet';
        } elseif ($this->agent->isMobile()) {
            return 'mobile';
        } else {
            return 'unknown';
        }
    }

    public function getPlatform()
    {
        // Detect operating system
        return $this->agent->platform();
    }

    public function getBrowser()
    {
        // Detect browser name
        return $this->agent->browser();
    }

    public function getBrowserVersion()
    {
        // Detect browser version
        return $this->agent->version($this->getBrowser());
    }

    public function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }

}