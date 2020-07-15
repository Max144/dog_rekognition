<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Aws\DynamoDb\DynamoDbClient;

return function ($event) {
    $client = new DynamoDbClient([
        'region' => getenv('AWS_REGION'),
        'version' => 'latest',
        'credentials' => [
            'key' => getenv('AWS_USER_KEY'),
            'secret' => getenv('AWS_USER_SECRET'),
        ]
    ]);
    try {
        $client->putItem(array(
            'TableName' => 'dog_table',
            'Item' => array(
                'image_name' => ['S' => $event['image_name']],
                'labels'   => array('S' => json_encode($event['labels'])),
            )
        ));
    } catch (Exception $exception) {
        return 'database error: ' . $exception->getMessage();
    }

    return true;
};
