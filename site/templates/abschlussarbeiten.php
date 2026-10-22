<?php

// Header über Kirby setzen – direkte header()-Aufrufe überschreibt Kirby mit text/html
$kirby->response()->type('application/json');
$kirby->response()->header('Access-Control-Allow-Origin', '*');

$items = [];
foreach ($page->children()->listed()->sortBy('date', 'desc') as $child) {
    $items[] = abschlussarbeit_to_array($child);
}

echo json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
