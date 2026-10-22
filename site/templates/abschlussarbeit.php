<?php

// Header über Kirby setzen – direkte header()-Aufrufe überschreibt Kirby mit text/html
$kirby->response()->type('application/json');
$kirby->response()->header('Access-Control-Allow-Origin', '*');

if ($page->isDraft()) {
    $kirby->response()->code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}

echo json_encode(
    abschlussarbeit_to_array($page),
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
