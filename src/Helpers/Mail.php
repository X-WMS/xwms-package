<?php

namespace XWMS\Package\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class Mail
{
    public static function send(string $to, array $data, array $options = []): bool
    {
        $mail = new PHPMailer(true);

        try {
            $smtpConfig = $options['smtp'] ?? [];
            if (empty($smtpConfig)){
                $smtpConfig = self::resolveSmtpProfile();
            }

            // 🔌 Instellingen toepassen
            self::setupSmtp($mail, $smtpConfig);
            self::setupRecipients($mail, $to, $options);

            // 📩 HTML renderen
            $view = $data['template'] ?? 'xwms-verification';
            $view = "mail.".$view;
            $html = view($view, ['data' => $data])->render();

            $mail->isHTML(true);
            $mail->Subject = $data['subject'] ?? 'XWMS Mail';
            $mail->Body = $html;

            $mail->send();
            return true;

        } catch (MailException $e) {
            throw new \Exception("Mail error: {$mail->ErrorInfo}");
        }
    }

    protected static function resolveSmtpProfile(): array
    {
        return [
            'host' => env('MAIL_HOST'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'port' => env('MAIL_PORT'),
            'secure' => PHPMailer::ENCRYPTION_SMTPS
        ];
    }

    public static function sendVerificationCode(string $email, string $code, string|array $options = []): bool
    {
        $options = is_array($options) ? $options : ['username' => $options];
        $data = array_merge([
            'template' => 'xwms-verification',
            'subject' => $options['subject'] ?? 'XWMS Verification Code',
            'verificationCode' => $code,
        ], $options);

        return self::send($email, $data, $options);
    }

    protected static function setupSmtp(PHPMailer $mail, array $smtp): void
    {
        $mail->isSMTP();
        $mail->Host       = $smtp['host'] ?? env('MAIL_HOST');
        $mail->SMTPAuth   = $smtp['auth'] ?? true;
        $mail->Username   = $smtp['username'] ?? env('MAIL_USERNAME');
        $mail->Password   = $smtp['password'] ?? env('MAIL_PASSWORD');
        $mail->SMTPSecure = $smtp['secure'] ?? PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $smtp['port'] ?? env('MAIL_PORT');
    }

    protected static function setupRecipients(PHPMailer $mail, string $to, array $options): void
    {
        $from = $options['from_email'] ?? $mail->Username;
        $name = $options['from_name'] ?? 'XWMS';

        $mail->setFrom($from, $name);
        $mail->addAddress($to);
    }
}
