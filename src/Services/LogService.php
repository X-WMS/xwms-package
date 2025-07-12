<?php

namespace LaravelShared\Core\Services;

use App\Models\Log;

class LogService
{
    protected $levels = [
        'DEBUG',
        'INFO',
        'WARNING',
        'ERROR',
        'CRITICAL',
    ];

    // category dingen zolas gameplay, hack, security, erros

    private function makeLog(?int $userId, string $level, string $category, string $message, ?array $context): bool
    {
        if (!in_array($level, $this->levels)) {
            throw new \InvalidArgumentException("Invalid log level provided.");
        }

        $ipData = IpService::getIpAdr();

        $address_data = [
            'ipaddress' => $ipData['ipaddress'],
            'city' => $ipData['city'],
            'countrycode' => $ipData['countrycode'],
            'countryname' => $ipData['countryname']
        ];

        $address_data_json = json_encode($address_data);
        $context_json = json_encode($context);

        $data = [
            'level' => $level,
            'category' => $category,
            'message' => $message,
            'userId' => $userId,
            'context' => $context_json,
            'adress_data' => $address_data_json,
        ];

        Log::create($data);
        return true;
    }

    protected function startLog($userId, $level, $category, $message, $context): bool
    {
        try {
            // Gebruik type casting om ervoor te zorgen dat de invoer valide is
            if (!is_null($userId) && !is_int($userId)) {
                throw new \InvalidArgumentException("Invalid userId type. Must be an integer or null.");
            }
    
            if (!is_string($category) || !is_string($level) || !is_string($message)) {
                throw new \InvalidArgumentException("Invalid input types. Strings are expected for category, level, and message.");
            }
    
            if (!is_null($context) && !is_array($context)) {
                throw new \InvalidArgumentException("Context must be an array or null.");
            }
    
            // Call makeLog
            $this->makeLog($userId, $level, $category, $message, $context);
            return true;
    
        } catch (\TypeError $e) {
            // TypeErrors bij typehint problemen
            // var_dump($e->getMessage());
            return false;
    
        } catch (\InvalidArgumentException $e) {
            // Fout door ongeldige invoerwaarden
            // var_dump($e->getMessage());
            return false;
    
        } catch (\Exception $e) {
            // Algemene uitzonderingen, zoals databaseproblemen
            // var_dump($e->getMessage());
            return false;
        }
    }

    public function logDebug($userId, $category, $message, $context): bool
    {
        return $this->startLog($userId, 'DEBUG', $category, $message, $context);
    }

    public function logInfo($userId, $category, $message, $context): bool
    {
        return $this->startLog($userId, 'INFO', $category, $message, $context);
    }

    public function logWarning($userId, $category, $message, $context): bool
    {
        return $this->startLog($userId, 'WARNING', $category, $message, $context);
    }

    public function logError($userId, $category, $message, $context): bool
    {
        return $this->startLog($userId, 'ERROR', $category, $message, $context);
    }

    public function logCritical($userId, $category, $message, $context): bool
    {
        return $this->startLog($userId, 'CRITICAL', $category, $message, $context);
    }

    public function log_activity(string $type, array $data): bool
    {
        // Standaardwaarden voor verwachte sleutels
        $defaultKeys = [
            'userid' => null,
            'category' => null,
            'message' => null,
            'context' => null
        ];

        // Vul ontbrekende sleutels in het $data-array met standaardwaarden
        foreach ($defaultKeys as $key => $defaultValue) {
            if (!array_key_exists($key, $data)) {
                $data[$key] = $defaultValue;
            }
        }

        switch (strtolower($type)) {
            case 'debug':
                return $this->logDebug($data['userid'], $data['category'], $data['message'], $data['context']);

            case 'info':
                return $this->logInfo($data['userid'], $data['category'], $data['message'], $data['context']);

            case 'warning':
                return $this->logWarning($data['userid'], $data['category'], $data['message'], $data['context']);

            case 'error':
                return $this->logError($data['userid'], $data['category'], $data['message'], $data['context']);

            case 'critical':
                return $this->logCritical($data['userid'], $data['category'], $data['message'], $data['context']);

            default:
                return false;
        }
    }
}
