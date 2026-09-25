<?php

function sendNotificationEmail($to, $subject, $message)
{
    $to = filter_var($to, FILTER_VALIDATE_EMAIL) ? $to : ADMIN_EMAIL;
    $subject = trim($subject);

    $htmlMessage = buildEmailTemplate($subject, $message);
    $plainMessage = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $message));

    logEmail($to, $subject, $plainMessage);

    if (!defined('SMTP_ENABLED') || SMTP_ENABLED !== true) {
        return true;
    }

    return sendSmtpEmail($to, $subject, $htmlMessage);
}

function buildEmailTemplate($subject, $message)
{
    return '
    <div style="margin:0;padding:0;background:#f4f7fb;font-family:Arial,sans-serif;">
        <div style="max-width:620px;margin:30px auto;background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 18px 45px rgba(15,23,42,0.12);">

            <div style="background:linear-gradient(135deg,#2563eb,#0f766e);padding:28px;text-align:center;color:#ffffff;">
                <h1 style="margin:0;font-size:26px;">GlobeTrek Adventures</h1>
                <p style="margin:8px 0 0;font-size:14px;">Premium Sri Lankan Travel Planning Platform</p>
            </div>

            <div style="padding:30px;color:#0f172a;">
                <h2 style="margin-top:0;color:#0f172a;font-size:22px;">' . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . '</h2>

                <div style="font-size:15px;line-height:1.7;color:#334155;">
                    ' . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . '
                </div>

                <div style="margin-top:26px;text-align:center;">
                    <a href="' . BASE_URL . '" style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:12px 24px;border-radius:30px;font-weight:bold;">
                        Visit GlobeTrek
                    </a>
                </div>
            </div>

            <div style="background:#0f172a;color:#cbd5e1;text-align:center;padding:16px;font-size:13px;">
                © ' . date('Y') . ' GlobeTrek Adventures. All rights reserved.
            </div>

        </div>
    </div>';
}

function logEmail($to, $subject, $message)
{
    $logDir = __DIR__ . '/../../storage/logs';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    $log = "\n---------------- EMAIL NOTIFICATION ----------------\n";
    $log .= "Date: " . date('Y-m-d H:i:s') . "\n";
    $log .= "To: " . $to . "\n";
    $log .= "Subject: " . $subject . "\n";
    $log .= "Message:\n" . $message . "\n";

    file_put_contents($logDir . '/email.log', $log, FILE_APPEND);
}

function sendSmtpEmail($to, $subject, $message)
{
    $host = SMTP_HOST;
    $port = SMTP_PORT;
    $username = SMTP_USERNAME;
    $password = SMTP_PASSWORD;
    $fromEmail = SMTP_FROM_EMAIL;
    $fromName = SMTP_FROM_NAME;

    $socket = @stream_socket_client(
        "tcp://{$host}:{$port}",
        $errno,
        $errstr,
        30,
        STREAM_CLIENT_CONNECT
    );

    if (!$socket) {
        logMailError("SMTP connection failed: {$errstr} ({$errno})");
        return false;
    }

    stream_set_timeout($socket, 30);

    smtpRead($socket);
    smtpWrite($socket, "EHLO localhost");
    smtpWrite($socket, "STARTTLS");

    if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
        logMailError('SMTP TLS failed.');
        fclose($socket);
        return false;
    }

    smtpWrite($socket, "EHLO localhost");
    smtpWrite($socket, "AUTH LOGIN");
    smtpWrite($socket, base64_encode($username));
    smtpWrite($socket, base64_encode($password));
    smtpWrite($socket, "MAIL FROM:<{$fromEmail}>");
    smtpWrite($socket, "RCPT TO:<{$to}>");
    smtpWrite($socket, "DATA");

    $headers = [];
    $headers[] = "From: {$fromName} <{$fromEmail}>";
    $headers[] = "To: <{$to}>";
    $headers[] = "Subject: {$subject}";
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: text/html; charset=UTF-8";

    $emailData = implode("\r\n", $headers) . "\r\n\r\n" . $message . "\r\n.";

    smtpWrite($socket, $emailData);
    smtpWrite($socket, "QUIT");

    fclose($socket);
    return true;
}

function smtpWrite($socket, $command)
{
    fwrite($socket, $command . "\r\n");
    return smtpRead($socket);
}

function smtpRead($socket)
{
    $response = '';

    while ($line = fgets($socket, 515)) {
        $response .= $line;

        if (isset($line[3]) && $line[3] === ' ') {
            break;
        }
    }

    if (preg_match('/^[45]/', $response)) {
        logMailError($response);
    }

    return $response;
}

function logMailError($message)
{
    $logDir = __DIR__ . '/../../storage/logs';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    file_put_contents(
        $logDir . '/error.log',
        date('Y-m-d H:i:s') . ' SMTP error: ' . trim($message) . PHP_EOL,
        FILE_APPEND
    );
}

?>