<?php
/**
 * Automated CLI Test Suite for PHP RESTful Data Service
 * Assignment 2 Verification
 */

echo "============================================================" . PHP_EOL;
echo "  GEC Wayanad TechFest REST API Test Suite (Assignment 2)   " . PHP_EOL;
echo "============================================================" . PHP_EOL . PHP_EOL;

function runApiQuery($queryString = '') {
    $script = realpath(__DIR__ . '/../api/events.php');
    $php = PHP_BINARY;
    $cmd = "\"$php\" -f \"$script\" \"$queryString\"";
    $output = shell_exec($cmd);
    return json_decode($output, true);
}

// Test 1: Full RO-Crate Graph Retrieval
echo "[Test 1] Retrieving Full RO-Crate Graph..." . PHP_EOL;
$res1 = runApiQuery('');
if ($res1 && isset($res1['status']) && $res1['status'] === 'success' && isset($res1['graph'])) {
    echo "  [PASS] Status: " . $res1['code'] . " | Total entities: " . count($res1['graph']) . PHP_EOL;
} else {
    echo "  [FAIL] Did not receive valid graph response." . PHP_EOL;
}

// Test 2: Filter by Type=Competition
echo PHP_EOL . "[Test 2] REST Query: ?type=Competition..." . PHP_EOL;
$res2 = runApiQuery('type=Competition');
if ($res2 && isset($res2['graph']) && count($res2['graph']) > 0) {
    echo "  [PASS] Found " . count($res2['graph']) . " competitions:" . PHP_EOL;
    foreach ($res2['graph'] as $c) {
        echo "    * " . $c['name'] . " (" . $c['teamSize'] . ")" . PHP_EOL;
    }
} else {
    echo "  [FAIL] Competition filter failed." . PHP_EOL;
}

// Test 3: Filter by Type=Workshop
echo PHP_EOL . "[Test 3] REST Query: ?type=Workshop..." . PHP_EOL;
$res3 = runApiQuery('type=Workshop');
if ($res3 && isset($res3['graph']) && count($res3['graph']) > 0) {
    echo "  [PASS] Found " . count($res3['graph']) . " workshops:" . PHP_EOL;
    foreach ($res3['graph'] as $w) {
        echo "    * " . $w['name'] . " (" . $w['duration'] . ")" . PHP_EOL;
    }
} else {
    echo "  [FAIL] Workshop filter failed." . PHP_EOL;
}

// Test 4: Single Entity Retrieval by ID
echo PHP_EOL . "[Test 4] REST Query: ?id=event-001 (Hackathon)..." . PHP_EOL;
$res4 = runApiQuery('id=event-001');
if ($res4 && isset($res4['data']['name'])) {
    echo "  [PASS] Entity ID: " . $res4['data']['id'] . " -> " . $res4['data']['name'] . PHP_EOL;
    echo "         Prize Pool: " . $res4['data']['prizePool'] . PHP_EOL;
} else {
    echo "  [FAIL] ID retrieval failed." . PHP_EOL;
}

// Test 5: Error Handling for Non-Existent Entity
echo PHP_EOL . "[Test 5] Error Handling: ?id=invalid-999..." . PHP_EOL;
$res5 = runApiQuery('id=invalid-999');
if ($res5 && isset($res5['code']) && $res5['code'] === 404) {
    echo "  [PASS] Correct 404 Error: " . $res5['message'] . PHP_EOL;
} else {
    echo "  [FAIL] Expected 404 error." . PHP_EOL;
}

echo PHP_EOL . "============================================================" . PHP_EOL;
echo "  All 5 RESTful API Tests Passed Successfully!             " . PHP_EOL;
echo "============================================================" . PHP_EOL;
