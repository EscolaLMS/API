<?php

namespace EscolaLms\MultidomainTool;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Cookie\CookieJar;
use Ahc\Cli\Output\Color;

class MinIO
{
    public static function loginGetCookie($url, $IAM_KEY, $IAM_SECRET): CookieJar
    {

        $client = new Client();
        $jar = new CookieJar;

        $credentials = [
            "accessKey" => $IAM_KEY,
            "secretKey" =>  $IAM_SECRET
        ];
        $client->request('POST', $url, [
            'headers' => ['Content-Type' => 'application/json',],
            'cookies' => $jar,
            'json' =>  $credentials
        ]);

        return $jar;
    }

    public static function postToMinio(string $url, CookieJar $jar, $body, $method = 'POST'): MinioResponse
    {

        $client = new Client();

        try {
            $response = $client->request($method, $url, [
                'headers' => ['Content-Type' => 'application/json',],
                'cookies' => $jar,
                'json' =>  $body
            ]);
            return new MinioResponse(true, $response->getBody()->getContents());
        } catch (RequestException $err) {
            return new MinioResponse(false, $err->getMessage());
        }
    }


    public static function createUserPolicyBucket(string $domain)
    {
        $color = new Color;
        echo $color->info('MinIO' . "\n");


        $MINIO_API_ENDPOINT = $_ENV['AWS_API_ENDPOINT'] . "/api/v1";

        $bucket_name = str_replace('_', '-', $domain); // Replaces all spaces with hyphens.
        $bucket_name = preg_replace('/[^A-Za-z0-9\-]/', '', $bucket_name); // Removes special chars.
        $bytes = openssl_random_pseudo_bytes(8);
        $password = bin2hex($bytes);

        // Cookie has token, which is required for next events
        $cookieJar = self::loginGetCookie("${MINIO_API_ENDPOINT}/login", $_ENV['AWS_ROOT_ACCESS_KEY_ID'], $_ENV['AWS_ROOT_SECRET_ACCESS_KEY']);

        // create bucket
        $bucket = self::postToMinio("${MINIO_API_ENDPOINT}/buckets", $cookieJar, ["name" => $bucket_name]);
        if ($bucket->success) {
            echo $color->ok("MinIO. Bucket $bucket_name Created Succesfully \n");
        } else {
            echo $color->warn("MinIO. Bucket $bucket_name Failed \n");
            echo $color->error($bucket->message . "\n");
            return [
                'success' => false
            ];
        }

        //make bucket public 
        $setPolicy =  self::postToMinio("${MINIO_API_ENDPOINT}/buckets/${bucket_name}/set-policy", $cookieJar, ["access" => "PUBLIC", 'definition' => json_encode([
            "Version" => "2012-10-17",
            "Statement" => [
                []
            ]
        ])], "PUT");

        if ($setPolicy->success) {
            echo $color->ok("MinIO. Bucket $bucket_name access set to public Successfully \n");
        } else {
            echo $color->warn("MinIO. Bucket $bucket_name access set to public Failed \n");
            echo $color->error($setPolicy->message . "\n");
            return [
                'success' => false
            ];
        }


        // create a policy for accessing only one bucket
        $policy =  self::postToMinio("${MINIO_API_ENDPOINT}/policies", $cookieJar, ["name" => $domain, 'policy' => json_encode([
            "Version" => "2012-10-17",
            "Statement" => [
                [
                    "Effect" => "Allow",
                    "Action" => "s3:*",
                    "Resource" => [
                        "arn:aws:s3:::$bucket_name/*",
                        "arn:aws:s3:::$bucket_name"
                    ]
                ]
            ]
        ])]);

        if ($policy->success) {
            echo $color->ok("MinIO. Policy $bucket_name Created Successfully \n");
        } else {
            echo $color->warn("MinIO. Policy $bucket_name Failed \n");
            echo $color->error($policy->message . "\n");
            return [
                'success' => false
            ];
        }

        // create a user and give access to policy 
        $user = self::postToMinio("${MINIO_API_ENDPOINT}/users", $cookieJar, ["accessKey" => $bucket_name, "secretKey" => $password, "groups" => [], "policies" => [$domain]]);

        if ($user->success) {
            echo $color->ok("MinIO. User $bucket_name Created Successfully \n");
        } else {
            echo $color->warn("MinIO. User $bucket_name Failed \n");
            echo $color->error($user->message . "\n");
            return [
                'success' => false
            ];
        }

        return [
            'success' => $bucket->success && $policy->success && $user->success,
            'vars' => [
                'AWS_ACCESS_KEY_ID' => $bucket_name,
                'AWS_SECRET_ACCESS_KEY' => $password,
                'AWS_BUCKET' => $bucket_name,
                'AWS_URL' => $_ENV['AWS_URL_PREFIX'] . "/" . $bucket_name,
            ]
        ];
    }
}


class MinioResponse
{
    public function __construct(public bool $success, public string $message) {}
}
