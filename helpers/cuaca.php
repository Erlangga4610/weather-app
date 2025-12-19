<?php

function cuacaIndo(string $text): string {
    $map = [
        'Sunny' => 'Cerah',
        'Clear' => 'Cerah',
        'Partly cloudy' => 'Cerah Berawan',
        'Cloudy' => 'Berawan',
        'Overcast' => 'Mendung',
        'Patchy rain nearby' => 'Hujan ringan di sekitar',
        'Light rain' => 'Hujan ringan',
        'Moderate rain' => 'Hujan sedang',
        'Heavy rain' => 'Hujan lebat',
        'Thunderstorm' => 'Badai petir',
        'Mist' => 'Berkabut',
        'Fog' => 'Kabut',
    ];

    return $map[$text] ?? $text;
}
