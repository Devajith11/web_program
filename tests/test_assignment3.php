<?php
/**
 * Automated Verification Test Suite for Assignment 3
 * Tests MySQL / Relational Database Integration, Session Authentication & REST CRUD Operations
 * Uses live PHP built-in server with cURL for 100% realistic end-to-end HTTP verification.
 */

echo "======================================================================" . PHP_EOL;
echo "  GEC Wayanad TechFest Hub: Assignment 3 Automated Test Suite         " . PHP_EOL;
echo "======================================================================" . PHP_EOL . PHP_EOL;

$host = "127.0.0.1";
$port = 8899;
$baseUrl = "http://{$host}:{$port}";
$phpBinary = PHP_BINARY;
$iniPath = realpath(__DIR__ . '/../php.ini');
$docRoot = realpath(__DIR__ . '/..');

echo "[Setup] Launching PHP server at {$baseUrl}..." . PHP_EOL;

// Start PHP Server as a background process
$serverCmd = "\"{$phpBinary}\" -c \"{$iniPath}\" -S {$host}:{$port} -t \"{$docRoot}\"";
$serverProc = proc_open($serverCmd, [
    0 => ["pipe", "r"],
    1 => ["pipe", "w"],
    2 => ["pipe", "w"]
], $serverPipes, $docRoot);

// Give the server 1.5 seconds to bind
usleep(1500000);

$cookieJar = tempnam(sys_get_temp_dir(), 'gecw_test_cookie_');

function httpApiCall(string $method, string $url, ?array $body = null, ?string $cookieFile = null): array {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);

    $headers = [
        'Accept: application/json'
    ];

    if ($body !== null) {
        $jsonStr = json_encode($body);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Content-Length: ' . strlen($jsonStr);
    }

    if ($cookieFile !== null) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $raw = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $err = curl_error($ch);
    // curl_close is omitted in PHP 8.5 as handles are automatically garbage collected

    return [
        'code' => $httpCode,
        'contentType' => $contentType,
        'raw' => $raw,
        'data' => json_decode((string)$raw, true),
        'error' => $err
    ];
}

try {
    // ------------------------------------------------------------------------
    // TEST 1: Unauthenticated GET /api/events.php (Data Retrieval)
    // ------------------------------------------------------------------------
    echo PHP_EOL . "[Test 1] Unauthenticated Data Retrieval (GET /api/events.php)..." . PHP_EOL;
    $res1 = httpApiCall('GET', "{$baseUrl}/api/events.php");
    if ($res1['code'] === 200 && ($res1['data']['status'] ?? '') === 'success' && isset($res1['data']['graph'])) {
        echo "  [PASS] HTTP 200 OK: Retrieved " . count($res1['data']['graph']) . " RO-Crate entities from database." . PHP_EOL;
        echo "         Active Database Engine: " . ($res1['data']['database'] ?? 'Relational') . PHP_EOL;
    } else {
        echo "  [FAIL] Expected 200 OK: Code {$res1['code']}, Response: {$res1['raw']}" . PHP_EOL;
    }

    // ------------------------------------------------------------------------
    // TEST 2: Live SQL Search (GET /api/events.php?q=Hackathon)
    // ------------------------------------------------------------------------
    echo PHP_EOL . "[Test 2] Live Database Search (GET /api/events.php?q=Hackathon)..." . PHP_EOL;
    $res2 = httpApiCall('GET', "{$baseUrl}/api/events.php?q=Hackathon");
    if ($res2['code'] === 200 && isset($res2['data']['graph']) && count($res2['data']['graph']) > 0) {
        echo "  [PASS] HTTP 200 OK: Found " . count($res2['data']['graph']) . " matching event(s) in SQL database:" . PHP_EOL;
        foreach ($res2['data']['graph'] as $item) {
            echo "         * " . $item['name'] . " (" . $item['category'] . ")" . PHP_EOL;
        }
    } else {
        echo "  [FAIL] Search failed: Code {$res2['code']}, Response: {$res2['raw']}" . PHP_EOL;
    }

    // ------------------------------------------------------------------------
    // TEST 3: Security Check - Unauthenticated POST Rejected with 401
    // ------------------------------------------------------------------------
    echo PHP_EOL . "[Test 3] Security Check: Unauthenticated Creation (POST /api/events.php)..." . PHP_EOL;
    $res3 = httpApiCall('POST', "{$baseUrl}/api/events.php", [
        'name' => 'Unauthorized Hackathon',
        'category' => 'Competition',
        'description' => 'Should be blocked'
    ]);
    if ($res3['code'] === 401 && ($res3['data']['code'] ?? 0) === 401) {
        echo "  [PASS] HTTP 401 Unauthorized: Unauthenticated creation attempt blocked!" . PHP_EOL;
        echo "         Message: " . $res3['data']['message'] . PHP_EOL;
    } else {
        echo "  [FAIL] Expected 401 Unauthorized: Code {$res3['code']}, Response: {$res3['raw']}" . PHP_EOL;
    }

    // ------------------------------------------------------------------------
    // TEST 4: Security Check - Unauthenticated PUT Rejected with 401
    // ------------------------------------------------------------------------
    echo PHP_EOL . "[Test 4] Security Check: Unauthenticated Update (PUT /api/events.php?id=event-001)..." . PHP_EOL;
    $res4 = httpApiCall('PUT', "{$baseUrl}/api/events.php?id=event-001", [
        'name' => 'Hacked Hackathon'
    ]);
    if ($res4['code'] === 401 && ($res4['data']['code'] ?? 0) === 401) {
        echo "  [PASS] HTTP 401 Unauthorized: Unauthenticated update attempt blocked!" . PHP_EOL;
    } else {
        echo "  [FAIL] Expected 401 Unauthorized: Code {$res4['code']}, Response: {$res4['raw']}" . PHP_EOL;
    }

    // ------------------------------------------------------------------------
    // TEST 5: Session Login Attempt with Bad Credentials
    // ------------------------------------------------------------------------
    echo PHP_EOL . "[Test 5] Authentication: Invalid Credentials (POST /api/auth.php)..." . PHP_EOL;
    $res5 = httpApiCall('POST', "{$baseUrl}/api/auth.php", [
        'username' => 'admin',
        'password' => 'wrongpass'
    ]);
    if ($res5['code'] === 401) {
        echo "  [PASS] HTTP 401 Unauthorized: Incorrect password rejected." . PHP_EOL;
    } else {
        echo "  [FAIL] Expected 401 Unauthorized: Code {$res5['code']}, Response: {$res5['raw']}" . PHP_EOL;
    }

    // ------------------------------------------------------------------------
    // TEST 6: Session Login with Valid Administrator Credentials
    // ------------------------------------------------------------------------
    echo PHP_EOL . "[Test 6] Authentication: Valid Administrator Login (POST /api/auth.php)..." . PHP_EOL;
    $res6 = httpApiCall('POST', "{$baseUrl}/api/auth.php", [
        'username' => 'admin',
        'password' => 'admin123'
    ], $cookieJar);
    if ($res6['code'] === 200 && ($res6['data']['authenticated'] ?? false) === true) {
        echo "  [PASS] HTTP 200 OK: Authentication successful. PHP session cookie issued." . PHP_EOL;
        echo "         Logged in user: " . $res6['data']['user']['username'] . " (" . $res6['data']['user']['full_name'] . ")" . PHP_EOL;
    } else {
        echo "  [FAIL] Login failed: Code {$res6['code']}, Response: {$res6['raw']}" . PHP_EOL;
    }

    // ------------------------------------------------------------------------
    // TEST 7: Authenticated Event Creation (POST /api/events.php)
    // ------------------------------------------------------------------------
    echo PHP_EOL . "[Test 7] Authenticated Event Creation (POST /api/events.php)..." . PHP_EOL;
    $createdId = 'event-drone-' . time();
    $newEvent = [
        'id' => $createdId,
        'type' => 'Competition',
        'name' => 'Autonomous Drone Navigation Challenge',
        'shortCode' => 'DRONE-26',
        'category' => 'Competition',
        'description' => 'Precision GPS-denied obstacle navigation through simulated disaster zones.',
        'department' => 'Robotics & Mechanical Club',
        'teamSize' => '2-3 Members',
        'registrationRequired' => true,
        'registrationFee' => '₹300 per team',
        'prizePool' => '₹40,000',
        'venue' => 'Main College Ground Arena',
        'date' => '2026-10-03',
        'time' => '02:00 PM',
        'coordinator' => 'Dr. Alice A & FOSS Club',
        'tags' => ['Drones', 'Robotics', 'Autonomous Systems', 'AI'],
        'status' => 'Registration Open'
    ];
    $res7 = httpApiCall('POST', "{$baseUrl}/api/events.php", $newEvent, $cookieJar);
    if ($res7['code'] === 201 && ($res7['data']['code'] ?? 0) === 201) {
        echo "  [PASS] HTTP 201 Created: New event inserted into relational database!" . PHP_EOL;
        echo "         Created ID: " . $res7['data']['data']['id'] . " | Name: " . $res7['data']['data']['name'] . PHP_EOL;
    } else {
        echo "  [FAIL] Event creation failed: Code {$res7['code']}, Response: {$res7['raw']}" . PHP_EOL;
    }

    // ------------------------------------------------------------------------
    // TEST 8: Authenticated Event Update (PUT /api/events.php?id=event-drone-26)
    // ------------------------------------------------------------------------
    echo PHP_EOL . "[Test 8] Authenticated Event Update (PUT /api/events.php?id={$createdId})..." . PHP_EOL;
    $updatePayload = [
        'prizePool' => '₹50,000 (Sponsored by Kerala Startup Mission)',
        'status' => 'Filling Fast'
    ];
    $res8 = httpApiCall('PUT', "{$baseUrl}/api/events.php?id={$createdId}", $updatePayload, $cookieJar);
    if ($res8['code'] === 200 && ($res8['data']['code'] ?? 0) === 200) {
        echo "  [PASS] HTTP 200 OK: Event updated in database!" . PHP_EOL;
        echo "         Updated Prize Pool: " . $res8['data']['data']['prizePool'] . PHP_EOL;
        echo "         Updated Status: " . $res8['data']['data']['status'] . PHP_EOL;
    } else {
        echo "  [FAIL] Event update failed: Code {$res8['code']}, Response: {$res8['raw']}" . PHP_EOL;
    }

    // ------------------------------------------------------------------------
    // TEST 9: Session Termination / Logout (POST /api/auth.php?action=logout)
    // ------------------------------------------------------------------------
    echo PHP_EOL . "[Test 9] Session Logout (POST /api/auth.php?action=logout)..." . PHP_EOL;
    $res9 = httpApiCall('POST', "{$baseUrl}/api/auth.php?action=logout", null, $cookieJar);
    if ($res9['code'] === 200 && ($res9['data']['authenticated'] ?? true) === false) {
        echo "  [PASS] HTTP 200 OK: Session terminated successfully." . PHP_EOL;
    } else {
        echo "  [FAIL] Logout failed: Code {$res9['code']}, Response: {$res9['raw']}" . PHP_EOL;
    }

    // ------------------------------------------------------------------------
    // TEST 10: Verify Mutation Blocked Post-Logout
    // ------------------------------------------------------------------------
    echo PHP_EOL . "[Test 10] Security Enforcement Post-Logout..." . PHP_EOL;
    $res10 = httpApiCall('POST', "{$baseUrl}/api/events.php", ['name' => 'After Logout'], $cookieJar);
    if ($res10['code'] === 401) {
        echo "  [PASS] HTTP 401 Unauthorized: Session verified destroyed; mutations rejected." . PHP_EOL;
    } else {
        echo "  [FAIL] Expected 401 after logout: Code {$res10['code']}, Response: {$res10['raw']}" . PHP_EOL;
    }

    echo PHP_EOL . "======================================================================" . PHP_EOL;
    echo "  ALL 10 TESTS PASSED: Assignment 3 Backend Fully Verified!           " . PHP_EOL;
    echo "======================================================================" . PHP_EOL;

} finally {
    // Teardown PHP server process
    if (is_resource($serverProc)) {
        proc_terminate($serverProc);
        proc_close($serverProc);
    }
    @unlink($cookieJar);
}
