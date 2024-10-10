<?php

namespace EscolaLms\MultidomainTool;

use Ahc\Cli\Output\Color;
use Ahc\Cli\Application;

class Variables
{

    public static function checkEnvVars(Application $app): bool
    {
        $color = new Color;
        $pass = true;

        $required_env_vars = [
            'AWS_ROOT_ACCESS_KEY_ID',
            'AWS_ROOT_SECRET_ACCESS_KEY',
            'AWS_ENDPOINT',
            'AWS_API_ENDPOINT',
            'AWS_URL_PREFIX',
            'DB_ROOT_HOST',
            'DB_ROOT_PORT',
            'DB_ROOT_USERNAME',
            'DB_ROOT_PASSWORD',
        ];

        foreach ($required_env_vars  as $var) {
            if (empty($_ENV[$var])) {
                $pass = false;
                echo $color->error("Error. Environment variable ${var} is not set.\n");
            } else {
                echo $color->ok("Environment variable ${var} is set.\n");
            }
        }

        return $pass;
    }

    public static function listDomainVariables(string $domain_key, array $vars): array
    {
        $output = [];
        foreach ($vars as $key => $value) {
            $output[$domain_key . "_" . $key] = $domain_key . "_" . $key . ' : ' . $value;
        }
        return $output;
    }

    public static function printVariables(array $vars): void
    {
        $color = new Color;
        echo $color->info("Use those values for adding new domain\n");
        echo $color->info("Pass them into Docker Container Enviroment Variables\n");
        echo $color->info("-------------\n");
        foreach ($vars as $key => $value) {
            echo $color->ok($value . "\n");
        }
    }

    public static function getRandomSecurePassword(): string
    {
        $bytes = openssl_random_pseudo_bytes(8);
        return bin2hex($bytes);
    }
}
