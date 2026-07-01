<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$items = [];
foreach ($page->children()->listed()->sortBy('date', 'desc') as $child) {
    $items[] = abschlussarbeit_to_array($child);
}

echo json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
