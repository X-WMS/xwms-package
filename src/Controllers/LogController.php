<?php

namespace LaravelShared\Core\Controllers;

use LaravelShared\Core\Controllers\Controller;
use LaravelShared\Core\Services\LogService;

class LogController extends Controller
{
    protected LogService $logService;

    public function __construct(LogService $logService = null)
    {
        $this->logService = $logService ?: new LogService;
    }

    public function log_activity(string $type, array $data): bool
    {
        $LogService = $this->logService;
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
                return $LogService->logDebug($data['userid'], $data['category'], $data['message'], $data['context']);

            case 'info':
                return $LogService->logInfo($data['userid'], $data['category'], $data['message'], $data['context']);

            case 'warning':
                return $LogService->logWarning($data['userid'], $data['category'], $data['message'], $data['context']);

            case 'error':
                return $LogService->logError($data['userid'], $data['category'], $data['message'], $data['context']);

            case 'critical':
                return $LogService->logCritical($data['userid'], $data['category'], $data['message'], $data['context']);

            default:
                return false;
        }
    }
}
