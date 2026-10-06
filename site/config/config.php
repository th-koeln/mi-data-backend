<?php

require_once __DIR__ . '/helpers.php';

// Lokale Credentials (clientId, clientSecret, keycloakBase, realm, redirectUri)
// Datei nicht committen – siehe config.local.php.example
$cred = is_file(__DIR__ . '/config.local.php')
    ? require __DIR__ . '/config.local.php'
    : [];

$keycloakBase = rtrim($cred['keycloakBase'] ?? '', '/');
$realm        = $cred['realm'] ?? '';
$oidcBase     = $keycloakBase . '/realms/' . $realm . '/protocol/openid-connect';

return [
    'debug' => false,

    'panel' => [
        'install' => true,
    ],

    'content' => [
        'locking' => false,
    ],

    'thathoff.oauth' => [
        'providers' => [
            'thkoeln' => [
                'name'                    => 'TH Köln (Keycloak)',
                'clientId'                => $cred['clientId']     ?? '',
                'clientSecret'            => $cred['clientSecret'] ?? '',
                'redirectUri'             => $cred['redirectUri']  ?? '',
                'urlAuthorize'            => $oidcBase . '/auth',
                'urlAccessToken'          => $oidcBase . '/token',
                'urlResourceOwnerDetails' => $oidcBase . '/userinfo',
                'scope'                   => 'openid email profile',
            ],
        ],

        // Nur TH-Köln-Adressen zulassen
        'domainWhitelist' => ['th-koeln.de'],

        // Neuanlage automatisch: Whitelist → admin, alle anderen → editor
        // (Rolle gilt nur bei Neuanlage, bestehende Konten behalten ihre Rolle)
        'onlyExistingUsers' => false,
        'defaultRole'       => 'editor',
        'adminWhitelist'    => ['christian.noss@th-koeln.de'],

        // Lokal: false (Standard-Kirby-Login aktiv)
        // Produktion: in config.local.php auf true setzen
        'onlyOauth'    => $cred['onlyOauth']    ?? false,
        'autoRedirect' => $cred['autoRedirect'] ?? false,
    ],
];
