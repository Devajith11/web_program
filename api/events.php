<?php
/**
 * GEC Wayanad TechFest Hub (FUTURA '26)
 * Assignment 3: Relational Database-Driven RESTful CRUD API
 * 
 * Technical Constraints:
 * - Connects to MySQL/Relational DB via PDO (config/database.php)
 * - Zero HTML generation in API responses (strict JSON payloads)
 * - Accurate HTTP response codes: 200, 201, 400, 401, 404, 405, 500
 * - Data mutations (POST, PUT) strictly secured via PHP $_SESSION
 * - Unauthenticated GET endpoints for data retrieval and live keyword search (?q=keyword)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Set Essential HTTP & CORS Headers
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-cache, no-store, must-revalidate');

function sendApiResponse(int $code, array $payload): void {
    if (!headers_sent()) {
        http_response_code($code);
    }
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// 2. Resolve Request Method
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// If executed via CLI testing harness, allow query string passing via $argv[1]
if (php_sapi_name() === 'cli' && isset($argv[1])) {
    parse_str($argv[1], $_GET);
}

// Handle Preflight OPTIONS
if ($requestMethod === 'OPTIONS') {
    if (!headers_sent()) http_response_code(204);
    exit;
}

// 3. Connect to Database via PDO
require_once __DIR__ . '/../config/database.php';
$pdo = Database::getConnection();

// Helper to format event DB row into RO-Crate graph entity
function formatEventRow(array $row): array {
    $tags = [];
    if (!empty($row['tags'])) {
        $tags = array_map('trim', explode(',', $row['tags']));
    }

    return [
        'id' => $row['id'],
        'type' => $row['type'],
        'name' => $row['name'],
        'shortCode' => $row['short_code'],
        'category' => $row['category'],
        'description' => $row['description'],
        'department' => $row['department'],
        'teamSize' => $row['team_size'],
        'duration' => $row['duration'],
        'registrationRequired' => (bool)$row['registration_required'],
        'registrationFee' => $row['registration_fee'],
        'prizePool' => $row['prize_pool'],
        'venue' => $row['venue'],
        'date' => $row['event_date'],
        'time' => $row['event_time'],
        'coordinator' => $row['coordinator'],
        'speaker' => $row['speaker'],
        'instructor' => $row['instructor'],
        'tags' => $tags,
        'status' => $row['status']
    ];
}

// ============================================================================
// METHOD: GET (Unauthenticated Data Retrieval & Live Search)
// ============================================================================
if ($requestMethod === 'GET') {
    try {
        // Query Parameter: Single ID Lookup (?id=event-001)
        if (isset($_GET['id']) && trim($_GET['id']) !== '') {
            $id = trim($_GET['id']);
            $stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();

            if (!$row) {
                sendApiResponse(404, [
                    'status' => 'error',
                    'code' => 404,
                    'message' => "Entity with id '{$id}' not found in database."
                ]);
            }

            sendApiResponse(200, [
                'status' => 'success',
                'code' => 200,
                'data' => formatEventRow($row)
            ]);
        }

        // Query Parameter: Live Keyword Search (?q=keyword or ?search=keyword)
        $searchQuery = trim($_GET['q'] ?? $_GET['search'] ?? '');
        if ($searchQuery !== '') {
            $sql = "SELECT * FROM events 
                    WHERE name LIKE :q 
                       OR description LIKE :q 
                       OR tags LIKE :q 
                       OR coordinator LIKE :q 
                       OR speaker LIKE :q 
                       OR instructor LIKE :q 
                       OR venue LIKE :q 
                       OR department LIKE :q
                    ORDER BY id ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':q' => '%' . $searchQuery . '%']);
            $rows = $stmt->fetchAll();

            $results = array_map('formatEventRow', $rows);

            sendApiResponse(200, [
                'status' => 'success',
                'code' => 200,
                'query' => $searchQuery,
                'count' => count($results),
                'graph' => $results
            ]);
        }

        // Query Parameter: Filter by Type or Category (?type=Workshop)
        $typeFilter = trim($_GET['type'] ?? '');
        $catFilter = trim($_GET['category'] ?? '');

        if ($typeFilter !== '' || $catFilter !== '') {
            $sql = "SELECT * FROM events WHERE 1=1";
            $params = [];
            if ($typeFilter !== '') {
                $sql .= " AND LOWER(type) = LOWER(:type)";
                $params[':type'] = $typeFilter;
            }
            if ($catFilter !== '') {
                $sql .= " AND LOWER(category) = LOWER(:cat)";
                $params[':cat'] = $catFilter;
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll();
            $results = array_map('formatEventRow', $rows);

            sendApiResponse(200, [
                'status' => 'success',
                'code' => 200,
                'count' => count($results),
                'graph' => $results
            ]);
        }

        // Default: Full Institutional RO-Crate Graph Retrieval
        // 1. Root Festival Meta Entity
        $graph = [
            [
                'id' => 'techfest-2026',
                'type' => 'Event',
                'name' => "FUTURA '26 - GEC Wayanad TechFest",
                'edition' => '12th Edition',
                'tagline' => 'Innovate for the Hills, Code for the Globe',
                'description' => 'Annual national technology festival of Government Engineering College Wayanad, bringing together coders, roboticists, innovators, and thinkers to tackle real-world challenges through sustainable technology.',
                'startDate' => '2026-10-02',
                'endDate' => '2026-10-04',
                'venue' => 'GEC Wayanad Campus, Mananthavady, Kerala',
                'organizer' => 'Government Engineering College Wayanad (GECW)',
                'contactEmail' => 'techfest@gecwyd.ac.in',
                'contactPhone' => '+91 4935 241215'
            ]
        ];

        // 2. Fetch Events from Relational Table
        $eventStmt = $pdo->query("SELECT * FROM events ORDER BY id ASC");
        while ($row = $eventStmt->fetch()) {
            $graph[] = formatEventRow($row);
        }

        // 3. Fetch Schedules from Relational Table
        $schedStmt = $pdo->query("SELECT * FROM schedules ORDER BY id ASC");
        while ($row = $schedStmt->fetch()) {
            $graph[] = [
                'id' => $row['id'],
                'type' => 'Schedule',
                'day' => $row['day'],
                'dayKey' => $row['day_key'],
                'time' => $row['time_slot'],
                'title' => $row['title'],
                'venue' => $row['venue'],
                'category' => $row['category'],
                'eventId' => $row['event_id'],
                'speaker' => $row['speaker']
            ];
        }

        // 4. Fetch Updates from Relational Table
        $updateStmt = $pdo->query("SELECT * FROM updates ORDER BY timestamp DESC");
        while ($row = $updateStmt->fetch()) {
            $graph[] = [
                'id' => $row['id'],
                'type' => 'Update',
                'timestamp' => $row['timestamp'],
                'title' => $row['title'],
                'tag' => $row['tag'],
                'badge' => $row['badge'],
                'content' => $row['content']
            ];
        }

        sendApiResponse(200, [
            'status' => 'success',
            'code' => 200,
            'database' => Database::getDriverUsed(),
            'totalEntities' => count($graph),
            'graph' => $graph
        ]);

    } catch (PDOException $e) {
        sendApiResponse(500, [
            'status' => 'error',
            'code' => 500,
            'message' => 'Internal Server Error executing SQL query.',
            'details' => $e->getMessage()
        ]);
    }
}

// ============================================================================
// SECURITY CHECK: Data Mutations (POST, PUT) Restricted to Authenticated Sessions
// ============================================================================
function requireAuthenticatedSession(): void {
    if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
        sendApiResponse(401, [
            'status' => 'error',
            'code' => 401,
            'message' => 'Unauthorized: This mutation endpoint requires an active administrative session. Please log in via /api/auth.php.'
        ]);
    }
}

// Ingest JSON or form body for POST / PUT
$rawBody = file_get_contents('php://input');
$body = json_decode($rawBody, true) ?: $_POST;

// ============================================================================
// METHOD: POST (Protected Event Creation)
// ============================================================================
if ($requestMethod === 'POST') {
    requireAuthenticatedSession();

    // Input Validation
    $name = trim($body['name'] ?? '');
    $category = trim($body['category'] ?? '');
    $description = trim($body['description'] ?? '');

    if ($name === '' || $category === '' || $description === '') {
        sendApiResponse(400, [
            'status' => 'error',
            'code' => 400,
            'message' => 'Bad Request: Fields "name", "category", and "description" are strictly required.'
        ]);
    }

    // Determine type (defaults to category or custom)
    $type = trim($body['type'] ?? $category);
    if (!in_array($type, ['Competition', 'Workshop', 'Talk'])) {
        $type = in_array($category, ['Competition', 'Workshop', 'Talk']) ? $category : 'Competition';
    }

    // Generate or sanitize ID
    $id = trim($body['id'] ?? '');
    if ($id === '') {
        $id = 'event-' . strtolower(substr(md5(uniqid((string)mt_rand(), true)), 0, 6));
    }

    $shortCode = trim($body['shortCode'] ?? strtoupper(substr($name, 0, 6)));
    $department = trim($body['department'] ?? 'Computer Science & Engineering');
    $teamSize = trim($body['teamSize'] ?? 'Individual (1 Person)');
    $duration = trim($body['duration'] ?? '2 Hours');
    $regRequired = isset($body['registrationRequired']) ? ((bool)$body['registrationRequired'] ? 1 : 0) : 1;
    $regFee = trim($body['registrationFee'] ?? 'Free');
    $prizePool = trim($body['prizePool'] ?? '');
    $venue = trim($body['venue'] ?? 'GEC Wayanad Campus');
    $eventDate = trim($body['date'] ?? '2026-10-02');
    $eventTime = trim($body['time'] ?? '10:00 AM');
    $coordinator = trim($body['coordinator'] ?? '');
    $speaker = trim($body['speaker'] ?? '');
    $instructor = trim($body['instructor'] ?? '');
    
    $tagsInput = $body['tags'] ?? [];
    $tagsStr = is_array($tagsInput) ? implode(', ', $tagsInput) : trim((string)$tagsInput);
    $status = trim($body['status'] ?? 'Registration Open');

    try {
        $stmt = $pdo->prepare("
            INSERT INTO events 
            (id, type, name, short_code, category, description, department, team_size, duration, 
             registration_required, registration_fee, prize_pool, venue, event_date, event_time, 
             coordinator, speaker, instructor, tags, status)
            VALUES 
            (:id, :type, :name, :short_code, :category, :description, :department, :team_size, :duration,
             :reg_required, :reg_fee, :prize_pool, :venue, :event_date, :event_time,
             :coordinator, :speaker, :instructor, :tags, :status)
        ");

        $stmt->execute([
            ':id' => $id,
            ':type' => $type,
            ':name' => $name,
            ':short_code' => $shortCode,
            ':category' => $category,
            ':description' => $description,
            ':department' => $department,
            ':team_size' => $teamSize,
            ':duration' => $duration,
            ':reg_required' => $regRequired,
            ':reg_fee' => $regFee,
            ':prize_pool' => $prizePool !== '' ? $prizePool : null,
            ':venue' => $venue,
            ':event_date' => $eventDate,
            ':event_time' => $eventTime,
            ':coordinator' => $coordinator !== '' ? $coordinator : null,
            ':speaker' => $speaker !== '' ? $speaker : null,
            ':instructor' => $instructor !== '' ? $instructor : null,
            ':tags' => $tagsStr,
            ':status' => $status
        ]);

        // Fetch inserted record
        $fetchStmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
        $fetchStmt->execute([':id' => $id]);
        $newRecord = $fetchStmt->fetch();

        sendApiResponse(201, [
            'status' => 'success',
            'code' => 201,
            'message' => 'Event record created successfully in database.',
            'data' => formatEventRow($newRecord)
        ]);

    } catch (PDOException $e) {
        sendApiResponse(500, [
            'status' => 'error',
            'code' => 500,
            'message' => 'Database error during record creation.',
            'details' => $e->getMessage()
        ]);
    }
}

// ============================================================================
// METHOD: PUT (Protected Event Update)
// ============================================================================
if ($requestMethod === 'PUT') {
    requireAuthenticatedSession();

    // Resolve Event ID from Query (?id=event-001) or Payload Body
    $id = trim($_GET['id'] ?? $body['id'] ?? '');

    if ($id === '') {
        sendApiResponse(400, [
            'status' => 'error',
            'code' => 400,
            'message' => 'Bad Request: Target event "id" must be specified to perform an update.'
        ]);
    }

    // Check if target entity exists in database
    $checkStmt = $pdo->prepare("SELECT * FROM events WHERE id = :id LIMIT 1");
    $checkStmt->execute([':id' => $id]);
    $existing = $checkStmt->fetch();

    if (!$existing) {
        sendApiResponse(404, [
            'status' => 'error',
            'code' => 404,
            'message' => "Entity with id '{$id}' does not exist in database."
        ]);
    }

    // Merge updated values with existing record
    $name = isset($body['name']) && trim($body['name']) !== '' ? trim($body['name']) : $existing['name'];
    $category = isset($body['category']) && trim($body['category']) !== '' ? trim($body['category']) : $existing['category'];
    $type = isset($body['type']) && trim($body['type']) !== '' ? trim($body['type']) : $category;
    $description = isset($body['description']) && trim($body['description']) !== '' ? trim($body['description']) : $existing['description'];
    $shortCode = isset($body['shortCode']) ? trim($body['shortCode']) : $existing['short_code'];
    $department = isset($body['department']) ? trim($body['department']) : $existing['department'];
    $teamSize = isset($body['teamSize']) ? trim($body['teamSize']) : $existing['team_size'];
    $duration = isset($body['duration']) ? trim($body['duration']) : $existing['duration'];
    $regRequired = isset($body['registrationRequired']) ? ((bool)$body['registrationRequired'] ? 1 : 0) : $existing['registration_required'];
    $regFee = isset($body['registrationFee']) ? trim($body['registrationFee']) : $existing['registration_fee'];
    $prizePool = isset($body['prizePool']) ? trim($body['prizePool']) : $existing['prize_pool'];
    $venue = isset($body['venue']) ? trim($body['venue']) : $existing['venue'];
    $eventDate = isset($body['date']) ? trim($body['date']) : $existing['event_date'];
    $eventTime = isset($body['time']) ? trim($body['time']) : $existing['event_time'];
    $coordinator = isset($body['coordinator']) ? trim($body['coordinator']) : $existing['coordinator'];
    $speaker = isset($body['speaker']) ? trim($body['speaker']) : $existing['speaker'];
    $instructor = isset($body['instructor']) ? trim($body['instructor']) : $existing['instructor'];

    if (isset($body['tags'])) {
        $tagsStr = is_array($body['tags']) ? implode(', ', $body['tags']) : trim((string)$body['tags']);
    } else {
        $tagsStr = $existing['tags'];
    }

    $status = isset($body['status']) ? trim($body['status']) : $existing['status'];

    try {
        $stmt = $pdo->prepare("
            UPDATE events SET 
                type = :type,
                name = :name,
                short_code = :short_code,
                category = :category,
                description = :description,
                department = :department,
                team_size = :team_size,
                duration = :duration,
                registration_required = :reg_required,
                registration_fee = :reg_fee,
                prize_pool = :prize_pool,
                venue = :venue,
                event_date = :event_date,
                event_time = :event_time,
                coordinator = :coordinator,
                speaker = :speaker,
                instructor = :instructor,
                tags = :tags,
                status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $id,
            ':type' => $type,
            ':name' => $name,
            ':short_code' => $shortCode,
            ':category' => $category,
            ':description' => $description,
            ':department' => $department,
            ':team_size' => $teamSize,
            ':duration' => $duration,
            ':reg_required' => $regRequired,
            ':reg_fee' => $regFee,
            ':prize_pool' => $prizePool !== '' ? $prizePool : null,
            ':venue' => $venue,
            ':event_date' => $eventDate,
            ':event_time' => $eventTime,
            ':coordinator' => $coordinator !== '' ? $coordinator : null,
            ':speaker' => $speaker !== '' ? $speaker : null,
            ':instructor' => $instructor !== '' ? $instructor : null,
            ':tags' => $tagsStr,
            ':status' => $status
        ]);

        // Fetch updated record
        $fetchStmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
        $fetchStmt->execute([':id' => $id]);
        $updatedRecord = $fetchStmt->fetch();

        sendApiResponse(200, [
            'status' => 'success',
            'code' => 200,
            'message' => "Event '{$id}' updated successfully in database.",
            'data' => formatEventRow($updatedRecord)
        ]);

    } catch (PDOException $e) {
        sendApiResponse(500, [
            'status' => 'error',
            'code' => 500,
            'message' => 'Database error during record update.',
            'details' => $e->getMessage()
        ]);
    }
}

// ============================================================================
// UNSUPPORTED HTTP METHODS
// ============================================================================
sendApiResponse(405, [
    'status' => 'error',
    'code' => 405,
    'message' => "Method Not Allowed. Supported HTTP methods: GET, POST, PUT."
]);
