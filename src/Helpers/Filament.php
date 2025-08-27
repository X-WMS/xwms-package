<?php

namespace XWMS\Package\Helpers;

use XWMS\Package\Helpers\RateLimit;
use XWMS\Package\Helpers\Mail;
use XWMS\Package\Helpers\VerificationHelper;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Filament
{
    public static function verifyEmail(string $email, User $user)
    {
        if ($email == $user->email){
            Notification::make()
                ->title('This is the same email')
                ->warning()
                ->send();
            return false;
        }

        if ($email === $user->second_email) {
            Notification::make()
                ->title('This is the same email')
                ->warning()
                ->send();
            return false;
        }

        return true;
    }
    public static function sendVerificationCode(int $userId, string $category, string $email): void
    {
        RateLimit::checkRateLimit($category, 46, ['message' => 'before requesting a new code.']);

        $code = VerificationHelper::send(
            userId: $userId,
            category: $category,
            email: $email,
            validMinutes: 10
        )->code;

        Mail::sendVerificationCode($email, $code, [
            'subject'  => 'Confirm your email address',
            'subtitle' => 'Use this code to confirm your email.',
            'note'     => 'Valid for 10 minutes. Don’t share it.',
            'footer'   => 'Didn’t request this? Ignore this message.',
        ]);
    }

    public static function verifyCode(int $userId, string $category, string $code): void
    {
        VerificationHelper::verify(
            userId: $userId,
            category: $category,
            inputCode: $code,
            rateLimitSeconds: 5,
            maxAttempts: 5
        );
    }

    public static function handleSecure(callable $callback)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            Notification::make()
            ->title('Unable to proccess the action')
            ->body('An error occurred: ' . $e->getMessage())
            ->icon('heroicon-o-exclamation-triangle')
            ->iconColor('danger')
            ->color('danger')
            ->persistent()
            ->send();

            return false;
        }
    }

    public static function submitStartEmailChange($component, ?string $invalidemailMessage = null): bool
    {
        return self::handleSecure(function () use($component, $invalidemailMessage) {

            $state = $component->form->getState();
            $newEmail = $state[$component->email_key] ?? null;

            if (!$newEmail || ! filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                $component->addError($component->email_key, $invalidemailMessage ?? "Please enter a valid email address so we can reach you.");
                return;
            }

            $user = User::find(Auth::id());
            self::verifyEmail($newEmail, $user);
            RateLimit::checkRateLimit('change_email', 46, ['message' => 'before requesting a new code.']);

            self::sendVerificationCode(
                userId: $user->id,
                category: 'change_email',
                email: $newEmail
            );

            Notification::make()
            ->title('Verification code sent')
            ->body('We’ve sent a confirmation code to your new email address. Please enter the code to complete the update.')
            ->icon('heroicon-o-envelope')
            ->iconColor('primary')
            ->duration(8000)
            ->color('success')
            ->send();

            return true;
        });
    }

    public static function submitVerifyNewEmail($component, ?string $missingCodeMessage = null): bool
    {
        $state = $component->form->getState();
        $CodeString = "email_code";

        $newEmail = $state[$component->email_key] ?? null;
        $code = $state[$CodeString] ?? null;

        if (! $newEmail || ! $code) {
            $component->addError($CodeString, $missingCodeMessage ?? "Please enter the verification code you received.");
            return false;
        }

        try {
            self::verifyCode(
                userId: Auth::id(),
                category: 'change_email',
                code: $code
            );

            $user = User::find(Auth::id());
            $user->email = $newEmail;
            $user->save();

            $component->emailCodeSent = false;
            $component->form->fill(
                $component->mutateFormDataBeforeFill(
                    $user->fresh()->toArray()
                )
            );


            Notification::make()
            ->title('Email updated successfully')
            ->body('Your new email address has been verified and saved. Future communication will be sent to this address.')
            ->icon('heroicon-o-check-circle')
            ->iconColor('success')
            ->duration(8000)
            ->color('success')
            ->send();

            return true;
        } catch (\Throwable $e) {
            $component->addError('email_code', $e->getMessage());
            return false;
        }
    }
}
