<?php

namespace LaravelShared\Core\Helpers;

use App\Models\CodeVerification;
use LaravelShared\Core\Services\IpService;
use Exception;

class VerificationHelper
{
    protected static function getVerificationBase(int|string|null $xwmsId = null): array
    {
        $ipData = IpService::getIpAdr();

        return [
            'xwms_id' => is_numeric($xwmsId) ? $xwmsId : null,
            'ip' => $ipData['ipaddress'] ?? 'unknown',
        ];
    }

    /**
     * Genereer en sla een nieuwe verificatiecode op.
     */
    public static function send(int|string|null $xwmsId, string $category, ?string $email = null, int $validMinutes = 5): CodeVerification
    {
        $base = self::getVerificationBase($xwmsId);

        // Verwijder vorige 'pending' codes van dit type
        $query = CodeVerification::where('category', $category)
            ->where('status', 'pending');

        if (!empty($base['xwms_id'])) {
            $query->whereRaw('(xwms_id = ? OR ip = ?)', [
                $base['xwms_id'],
                $base['ip']
            ]);
        } else {
            $query->where('ip', $base['ip']);
        }

        $query->delete();
        $code = random_int(100000, 999999);

        return CodeVerification::create(array_merge($base, [
            'category' => $category,
            'status' => 'pending',
            'code' => $code,
            'email' => $email,
            'attempt' => 1,
            'expires_at' => now()->addMinutes($validMinutes),
            'last_attempt' => now(),
        ]));
    }

    /**
     * Verifieer een code voor een gebruiker.
     */
    public static function verify(int|string|null $xwmsId, string $category, string $inputCode, int $rateLimitSeconds = 5, int $maxAttempts = 5): CodeVerification
    {
        $base = self::getVerificationBase($xwmsId);

        $query = CodeVerification::where('category', $category)
            ->where('status', 'pending');

        if (!empty($base['xwms_id'])) {
            $query->whereRaw('(xwms_id = ? OR ip = ?)', [
                $base['xwms_id'],
                $base['ip']
            ]);
        } else {
            $query->where('ip', $base['ip']);
        }

        $codeEntry = $query->latest()->first();


        if (!$codeEntry) {
            throw new Exception("No code has been requested.");
        }

        if ($codeEntry->isExpired()) {
            $codeEntry->status = 'expired';
            $codeEntry->save();
            throw new Exception("The verification code has expired.");
        }

        if (
            $codeEntry->last_attempt &&
            now()->lt($codeEntry->last_attempt->addSeconds($rateLimitSeconds))
        ) {
            throw new Exception("Please wait before trying again.");
        }

        if ($codeEntry->attempt >= $maxAttempts) {
            $codeEntry->status = 'rate_limited';
            $codeEntry->save();
            throw new Exception("Too many attempts. Please request a new code.");
        }

        $codeEntry->last_attempt = now();
        $codeEntry->attempt++;

        if ((string) $inputCode !== (string) $codeEntry->code) {
            $codeEntry->save();
            throw new Exception("Incorrect code.");
        }

        $codeEntry->status = 'used';
        $codeEntry->completed_at = now();
        $codeEntry->save();

        return $codeEntry;
    }

    public static function getLatestCompletedVerification(int|string|null $xwmsId, string $category): ?CodeVerification
    {
        $base = self::getVerificationBase($xwmsId);

        $query = CodeVerification::where('category', $category)
            ->where('status', 'used');

        if (!empty($base['xwms_id'])) {
            $query->whereRaw('(xwms_id = ? OR ip = ?)', [
                $base['xwms_id'],
                $base['ip']
            ]);
        } else {
            $query->where('ip', $base['ip']);
        }

        return $query->latest('completed_at')->first();
    }

    public static function isTemporarilyUnlocked(int|string|null $xwmsId, string $category, int $seconds = 300): bool
    {
        $latest = self::getLatestCompletedVerification($xwmsId, $category);

        return $latest &&
            $latest->completed_at &&
            now()->lt($latest->completed_at->addSeconds($seconds));
    }


    public static function requireFreshVerification(int|string|null $xwmsId, string $category, ?string $email = null, int $validMinutes = 5, int $unlockSeconds = 300): true|CodeVerification
    {
        if (self::isTemporarilyUnlocked($xwmsId, $category, $unlockSeconds)) {
            return true;
        }

        $verification = self::send($xwmsId, $category, $email, $validMinutes);
        Mail::sendVerificationCode($email, $verification->code, [
            'smtp_profile' => 'mailfi',
            'subject' => 'Unlock your account',
            'subtitle' => "use the code below to unlock your account settings.",
            'note' => 'This code is valid for 5 minutes. Please don’t share it with anyone.',
            'footer' => 'If you didn’t request to change your email, you can ignore this message.'
        ]);

        return $verification;
    }

    /**
     * Haal het aantal seconden op dat nog over is van een tijdelijke unlock.
     */
    public static function getTemporaryUnlockRemainingSeconds(int|string|null $xwmsId, string $category, int $seconds = 300): int
    {
        $base = self::getVerificationBase($xwmsId);

        $query = CodeVerification::where('category', $category)
            ->where('status', 'used');

        if (!empty($base['xwms_id'])) {
            $query->whereRaw('(xwms_id = ? OR ip = ?)', [
                $base['xwms_id'],
                $base['ip']
            ]);
        } else {
            $query->where('ip', $base['ip']);
        }

        $latestUsed = $query->latest('completed_at')->first();

        if (!$latestUsed || !$latestUsed->completed_at) {
            return 0;
        }

        $unlockUntil = $latestUsed->completed_at->addSeconds($seconds);
        $now = now();

        if ($now->gte($unlockUntil)) {
            return 0;
        }

        return $now->diffInSeconds($unlockUntil);
    }
}
