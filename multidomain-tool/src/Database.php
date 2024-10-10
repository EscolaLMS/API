<?php

namespace EscolaLms\MultidomainTool;

use Ahc\Cli\Output\Color;

class Database
{
    public static function createPostgresUserAndDatabase($domain)
    {
        $color = new Color;
        echo $color->info('Database' . "\n");
        $domain = strtolower($domain);
        // Connecting, selecting database
        $dbconn = pg_connect("host={$_ENV['DB_ROOT_HOST']} user={$_ENV['DB_ROOT_USERNAME']} password={$_ENV['DB_ROOT_PASSWORD']}")
            or die('Could not connect: ' . pg_last_error());

        $username = pg_escape_identifier($dbconn, $domain);
        $bytes = openssl_random_pseudo_bytes(8);
        $password = pg_escape_string($dbconn, bin2hex($bytes));

        // Create USER 
        $query = "CREATE USER {$username} WITH password '{$password}'";
        pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", []);

        if ($result) {
            echo $color->ok("Postgres. User $username with password $password Created Succesfully \n");
        } else {
            echo $color->warn("Postgres. User $username Failed \n");
            echo $color->error(pg_last_error() . "\n");
            return [
                'success' => false
            ];
        }

        // Create Database 

        $query = "CREATE DATABASE {$username}";
        pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", []);

        if ($result) {
            echo $color->ok("Postgres. Database $username Created Succesfully \n");
        } else {
            echo $color->warn("Postgres. Database $username Failed \n");
            echo $color->error(pg_last_error() . "\n");
            return [
                'success' => false
            ];
        }

        // GRANT

        $query = "GRANT ALL PRIVILEGES ON DATABASE {$username} TO {$username}";
        pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", []);

        if ($result) {
            echo $color->ok("Postgres. Privileges granted to $username on database $username \n");
        } else {
            echo $color->warn("Postgres. Privileges granted to $username Failed \n");
            echo $color->error(pg_last_error() . "\n");
            return [
                'success' => false
            ];
        }

        // connect to database and 

        // Connecting, selecting database
        $dbconn_db = pg_connect("host={$_ENV['DB_ROOT_HOST']} user={$_ENV['DB_ROOT_USERNAME']} password={$_ENV['DB_ROOT_PASSWORD']} dbname={$domain}")
            or die('Could not connect: ' . pg_last_error());

        $query = "GRANT ALL ON SCHEMA public TO {$username}";
        pg_prepare($dbconn_db, "", $query);
        $result = pg_execute($dbconn_db, "", []);

        if ($result) {
            echo $color->ok("Postgres. GRANT ALL ON SCHEMA public granted to $username on database $username \n");
        } else {
            echo $color->warn("Postgres. GRANT ALL ON SCHEMA public granted to $username Failed \n");
            echo $color->error(pg_last_error() . "\n");
            return [
                'success' => false
            ];
        }

        return [
            'success' => true,
            'vars' => [
                'DB_DATABASE' => $username,
                'DB_USERNAME' => $username,
                'DB_PASSWORD' => $password
            ]
        ];
    }
}
