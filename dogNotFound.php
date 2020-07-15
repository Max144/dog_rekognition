<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Aws\Ses\SesClient;

return function ($event) {
    $sesClient = new SesClient([
        'region' => getenv('AWS_REGION'),
        'version' => 'latest',
        'credentials' => [
            'key' => getenv('AWS_USER_KEY'),
            'secret' => getenv('AWS_USER_SECRET'),
        ]
    ]);

    $senderEmail = 'iiejibmenb@gmail.com';
    $recipient_emails = ['iiejibmenb@gmail.com'];

    $subject = 'Dog not found';
    $plaintext_body = 'Dog not found' ;
    $html_body =  '<h1>Dog not found</h1>';

    try {
        $result = $sesClient->sendEmail([
            'Destination' => [
                'ToAddresses' => $recipient_emails,
            ],
            'ReplyToAddresses' => [$senderEmail],
            'Source' => $senderEmail,
            'Message' => [
                'Body' => [
                    'Html' => [
                        'Charset' => 'utf-8',
                        'Data' => $html_body,
                    ],
                    'Text' => [
                        'Charset' => 'utf-8',
                        'Data' => $plaintext_body,
                    ],
                ],
                'Subject' => [
                    'Charset' => 'utf-8',
                    'Data' => $subject,
                ],
            ],
        ]);
    } catch (Exception $exception) {
        return 'email was not sent, error: ' . $exception->getMessage();
    }

    return true;
};
