<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Aws\Rekognition\RekognitionClient;


return function ($event) {
    $options = [
        'region' => getenv('AWS_REGION'),
        'version' => 'latest',
        'credentials' => [
            'key' => getenv('AWS_USER_KEY'),
            'secret' => getenv('AWS_USER_SECRET'),
        ]
    ];

    try {
        $client = new RekognitionClient($options);
        $result = $client->detectLabels([
            'Image' => [
                'S3Object' => [
                    'Bucket' => getenv('BUCKET_NAME'),
                    'Name' => $event['image_path'],
                ],
            ],
            'MinConfidence' => intval(getenv('MIN_CONFIDENCE'))
        ]);
        $res = array_filter($result['Labels'], function ($item) {
            return $item['Name'] === 'dog' || $item['Name'] === 'Dog';
        });

        if (!empty($res)) {
            $labels = array_map(function ($item) {
                return $item['Name'];
            }, $result['Labels']);
            return [
                'dogExist' => true,
                'image_name' => $event['image_path'],
                'labels' => $labels,
            ];
        }

        return [
            'dogExist' => false,
            'image_name' => $event['image_path'],
        ];
    } catch (Exception $exception) {
        return  $exception->getMessage();
    }
};
