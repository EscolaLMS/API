<?php


require realpath(__DIR__ . '/../../vendor/autoload.php');

$env_path = realpath(__DIR__ . '/../../.env');

$dotenv = Dotenv\Dotenv::createMutable(dirname($env_path));
$dotenv->load();

function set_env(string $key, string $prev_value, string $new_value, string $env_path)
{
    $new_value = preg_replace('/\s+/', '', $new_value); //replace special ch
    $key = strtoupper($key); //force upper for security
    $env = file_get_contents($env_path); //fet .env file
    $env = str_replace("$key=" . $prev_value, "$key=" . $new_value, $env); //replace value
    $env = file_put_contents($env_path, $env);
}

function add_env(string $key, string $new_value, string $env_path)
{
    $new_value = preg_replace('/\s+/', '', $new_value); //replace special ch
    $key = strtoupper($key); //force upper for security
    $env = file_get_contents($env_path); //fet .env file
    $env = $env . "\n" . $key . "=" . $new_value;
    $env = file_put_contents($env_path, $env);
}


foreach (getenv() as $env => $new_value) {
    if (strpos($env, "LARAVEL_") === 0) {
        $key =  substr($env, 8);
        $old_value = isset($_ENV[$key]) ? $_ENV[$key] : false;

        if ($old_value) { // only replace values that exist
            echo "Replacing .env file $key from $old_value to $new_value\n";
            set_env($key, $old_value, $new_value, $env_path);
        } else {
            echo "Appending .env file $key to new value: $new_value\n";
            add_env($key, $new_value, $env_path);
        }
    }
}
