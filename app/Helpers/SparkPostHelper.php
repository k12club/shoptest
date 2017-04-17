<?php

namespace App\Helpers;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class SparkPostHelper {
    public static function sendTemplate($templateId, $recipients) {
        // Don't send email for demo site
        if (env('SHOP_DEMO') === true) {
            return true;
        }

        $client = new GuzzleClient(['base_uri' => 'https://api.sparkpost.com/api/v1/']);
        $request = new GuzzleRequest('POST', 'transmissions');

        try {
            $response = $client->send($request, [
                'auth' => [env('SPARKPOST_SECRET'), ''],
                'json' => [
                    'recipients' => $recipients,
                    'content' => [
                        'template_id' => $templateId
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}