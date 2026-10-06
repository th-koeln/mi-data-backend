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
    'debug' => true,

    'panel' => [
        // Erstinstallation übers Panel nur lokal erlauben (config.local.php)
        'install' => $cred['panelInstall'] ?? false,
    ],

    'content' => [
        'locking' => false,
    ],

    'hooks' => [
        'page.update:before' => function ($page, $values) {
            if ($page->intendedTemplate()->name() !== 'abschlussarbeit') {
                return;
            }
            // files-Feld liefert ein Array, kein String
            $hasUpload = !empty($values['teaser_image']);
            $hasUrl    = !empty(trim($values['teaser_image_url'] ?? ''));
            if (!$hasUpload && !$hasUrl) {
                throw new \Kirby\Exception\Exception(
                    'Ein Teaserbild ist erforderlich – bitte ein Bild hochladen oder eine externe URL angeben.'
                );
            }
        },
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
