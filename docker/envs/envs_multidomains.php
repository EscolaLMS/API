<?php

$domain = $argv[1];
$domainKey = $argv[2];

$domainVariables = [];
foreach (getenv() as $key => $value) {
    if (str_starts_with($key, $domainKey)) {
        $variableKey = str_replace($domainKey . '_', '', $key);
        $domainVariables[$variableKey] = $value;
    }
}

exec("php artisan domain:update_env $domain --domain_values='" . json_encode($domainVariables) . "'");
echo "Updated $domain env file\n";
