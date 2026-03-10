<?php

use App\Models\SystemSetting;

// get_setting
if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        return SystemSetting::where('key', $key)->value('value') ?? $default;
    }
}

// Set env 
if (!function_exists('setEnv')) {
    function setEnv(array $data)
    {
        $envPath = base_path('.env');
        $env = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            if ($value === null) continue;

            $value = '"' . trim($value) . '"';

            if (preg_match("/^{$key}=.*/m", $env)) {
                $env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
            } else {
                $env .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $env);
        return true;
    }
}
