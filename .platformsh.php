<?php

declare(strict_types=1);

$relationships = getenv('PLATFORM_RELATIONSHIPS');

if (!$relationships) {
    return;
}

ini_set('session.save_path', '/tmp/sessions');

$relationships = json_decode(base64_decode($relationships), true);

$setEnvVar = function (string $name, ?string $value): void {
    if (!putenv("$name=$value")) {
        throw new \RuntimeException('Failed to create environment variable: ' . $name);
    }
    $order = ini_get('variables_order');
    if (stripos($order, 'e') !== false) {
        $_ENV[$name] = $value;
    }
    if (stripos($order, 's') !== false) {
        if (strpos($name, 'HTTP_') !== false) {
            throw new \RuntimeException('Refusing to add ambiguous environment variable ' . $name . ' to $_SERVER');
        }
        $_SERVER[$name] = $value;
    }
};

foreach ($relationships['redis'] as $endpoint) {
    $setEnvVar('REDIS_HOST', $endpoint['host']);
    $setEnvVar('REDIS_PORT', (string) $endpoint['port']);
}
