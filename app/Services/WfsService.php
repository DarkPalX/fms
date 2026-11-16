<?php

namespace App\Services;

class WfsService
{
    public static function sync($payload = [])
    {
        $url = rtrim(config('services.wfs.url'), '/') . '/fms/sync-with-wfs';

        $options = [
            'http' => [
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($payload),
                'ignore_errors' => true, // Allows us to capture the 404/500 error page
                'timeout' => 30,
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            throw new \Exception("Connection failed. Check if WFS is running at $url");
        }

        $decoded = json_decode($result, true);

        // DEBUGGING BLOCK: If decoding fails, it means WFS sent back an error page (HTML)
        if (json_last_error() !== JSON_ERROR_NONE) {
            // This will stop execution and show you the actual Laravel error from WFS
            echo "<h1>Raw Response from WFS (Debug Mode)</h1>";
            echo "<p>Target URL: <strong>$url</strong></p>";
            echo "<hr>";
            echo $result; 
            exit;
        }

        return $decoded;
    }
}


// namespace App\Services;

// class WfsService
// {
//     public static function sync($payload = [])
//     {
//         $url = config('services.wfs.url') . '/fms/sync-with-wfs';

//         $options = [
//             'http' => [
//                 'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
//                 'method'  => 'POST',
//                 'content' => http_build_query($payload),
//                 'timeout' => 30,
//             ],
//         ];

//         $context = stream_context_create($options);
//         // $result = @file_get_contents($url, false, $context);
//         $result = file_get_contents($url, false, $context);

//         if ($result === FALSE) {
//             throw new \Exception('Failed to connect to WFS API');
//         }

//         return json_decode($result, true);
//     }
// }