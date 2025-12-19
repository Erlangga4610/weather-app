<?php
// Konfigurasi API Key

$envKey = getenv('WEATHERAPI_KEY');
define('WEATHER_API_KEY', $envKey !== false && $envKey !== '' ? $envKey :
'4b72a5328b694e9a9f002725251912');