<?php
/**
 * GEC Wayanad TechFest Hub (FUTURA '26)
 * Assignment 2: Server-Side RESTful Data Service
 * 
 * Technical Constraints:
 * - Strictly vanilla PHP data provider
 * - No databases, backend frameworks, or third-party APIs
 * - Reads data from standalone .json file
 * - Emits valid JSON responses with appropriate HTTP headers and status codes
 */

// Helper to safely emit HTTP status codes and JSON payload
function sendJsonResponse($code, $payload) {
    if (!headers_sent()) {
        http_response_code($code);
    }
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// 1. Set Essential HTTP & CORS Response Headers
if (!headers_sent()) {
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: no-cache, no-store, must-revalidate');
}

// 2. Resolve and Enforce HTTP Request Method
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// If executed via CLI testing harness, allow query string passing via $argv[1]
if (php_sapi_name() === 'cli' && isset($argv[1])) {
    parse_str($argv[1], $_GET);
}

// Handle Preflight HTTP OPTIONS Requests
if ($requestMethod === 'OPTIONS') {
    if (!headers_sent()) {
        http_response_code(204);
    }
    exit;
}

// Enforce Allowed HTTP Request Methods (GET only for read-only data service)
if ($requestMethod !== 'GET') {
    sendJsonResponse(405, [
        'status' => 'error',
        'code' => 405,
        'message' => 'Method Not Allowed. Only HTTP GET requests are supported by this data provider.'
    ]);
}

// 3. Resolve JSON Data Source File Path
$dataPath = realpath(__DIR__ . '/../data/techfest.json');

// Check if data file exists
if (!$dataPath || !file_exists($dataPath)) {
    sendJsonResponse(404, [
        'status' => 'error',
        'code' => 404,
        'message' => 'Resource Not Found: The institutional dataset file (data/techfest.json) could not be located.'
    ]);
}

// Read raw file content
$rawJson = @file_get_contents($dataPath);
if ($rawJson === false) {
    sendJsonResponse(500, [
        'status' => 'error',
        'code' => 500,
        'message' => 'Internal Server Error: Unable to read institutional dataset file.'
    ]);
}

// Decode and validate JSON format
$decodedData = json_decode($rawJson, true);
if (json_last_error() !== JSON_ERROR_NONE || !isset($decodedData['graph']) || !is_array($decodedData['graph'])) {
    sendJsonResponse(500, [
        'status' => 'error',
        'code' => 500,
        'message' => 'Internal Server Error: Malformed JSON syntax or missing root graph array.',
        'details' => json_last_error_msg()
    ]);
}

$graph = $decodedData['graph'];

// 4. RESTful Parameter Filtering (Optional query filters)
$typeFilter = isset($_GET['type']) ? trim($_GET['type']) : null;
$categoryFilter = isset($_GET['category']) ? trim($_GET['category']) : null;
$idFilter = isset($_GET['id']) ? trim($_GET['id']) : null;
$searchFilter = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : null;

// Filter by single ID
if ($idFilter !== null && $idFilter !== '') {
    $foundItem = null;
    foreach ($graph as $item) {
        if (isset($item['id']) && $item['id'] === $idFilter) {
            $foundItem = $item;
            break;
        }
    }

    if ($foundItem === null) {
        sendJsonResponse(404, [
            'status' => 'error',
            'code' => 404,
            'message' => "Entity with id '$idFilter' not found in dataset."
        ]);
    }

    sendJsonResponse(200, [
        'status' => 'success',
        'code' => 200,
        'data' => $foundItem
    ]);
}

// Filter by Type, Category, and Search Query
if ($typeFilter !== null && $typeFilter !== '' || $categoryFilter !== null && $categoryFilter !== '' || $searchFilter !== null && $searchFilter !== '') {
    $filteredGraph = array_values(array_filter($graph, function($item) use ($typeFilter, $categoryFilter, $searchFilter) {
        if ($typeFilter !== null && $typeFilter !== '') {
            if (!isset($item['type']) || strcasecmp($item['type'], $typeFilter) !== 0) {
                return false;
            }
        }

        if ($categoryFilter !== null && $categoryFilter !== '') {
            if (!isset($item['category']) || strcasecmp($item['category'], $categoryFilter) !== 0) {
                return false;
            }
        }

        if ($searchFilter !== null && $searchFilter !== '') {
            $blob = strtolower(json_encode($item));
            if (strpos($blob, $searchFilter) === false) {
                return false;
            }
        }

        return true;
    }));

    sendJsonResponse(200, [
        'status' => 'success',
        'code' => 200,
        'count' => count($filteredGraph),
        'graph' => $filteredGraph
    ]);
}

// 5. Default REST Response: Complete RO-Crate Aligned Graph
sendJsonResponse(200, [
    'status' => 'success',
    'code' => 200,
    'totalEntities' => count($graph),
    'graph' => $graph
]);
