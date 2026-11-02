<?php
/**
 * GEC Wayanad TechFest Hub (FUTURA '26)
 * Assignment 3: Session-Based Authentication Endpoint
 * 
 * Technical Constraints:
 * - Basic session-check mechanism via PHP $_SESSION
 * - No password hashing, no registration forms, no RBAC, no password reset flows (per constraints)
 * - Returns accurate JSON payloads and HTTP status codes (200, 400, 401, 405)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. HTTP Response Headers
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-cache, no-store, must-revalidate');

function sendAuthResponse(int $code, array $payload): void {
    if (!headers_sent()) {
        http_response_code($code);
    }
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'OPTIONS') {
    if (!headers_sent()) http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/database.php';
$pdo = Database::getConnection();

// 2. GET /api/auth.php - Session Status Check
if ($method === 'GET') {
    if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
        sendAuthResponse(200, [
            'status' => 'success',
            'authenticated' => true,
            'user' => $_SESSION['user']
        ]);
    } else {
        sendAuthResponse(200, [
            'status' => 'success',
            'authenticated' => false,
            'message' => 'No active authenticated session.'
        ]);
    }
}

// 3. POST /api/auth.php - Login & Logout Actions
if ($method === 'POST') {
    $action = $_GET['action'] ?? 'login';

    // Handle Logout Action
    if ($action === 'logout') {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        sendAuthResponse(200, [
            'status' => 'success',
            'code' => 200,
            'authenticated' => false,
            'message' => 'Administrator session terminated successfully.'
        ]);
    }

    // Handle Login Action
    // Ingest JSON payload or form-urlencoded
    $rawInput = file_get_contents('php://input');
    $inputData = json_decode($rawInput, true) ?: $_POST;

    $username = trim($inputData['username'] ?? '');
    $password = trim($inputData['password'] ?? '');

    // Input Validation
    if ($username === '' || $password === '') {
        sendAuthResponse(400, [
            'status' => 'error',
            'code' => 400,
            'message' => 'Bad Request: Both username and password are required.'
        ]);
    }

    // Query Database for User Credentials (Plain comparison per constraint exclusions)
    try {
        $stmt = $pdo->prepare("SELECT id, username, password, full_name FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === $password) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'login_time' => date('c')
            ];

            sendAuthResponse(200, [
                'status' => 'success',
                'code' => 200,
                'authenticated' => true,
                'message' => 'Authentication successful. Administrative session established.',
                'user' => $_SESSION['user']
            ]);
        } else {
            sendAuthResponse(401, [
                'status' => 'error',
                'code' => 401,
                'authenticated' => false,
                'message' => 'Unauthorized: Invalid username or password.'
            ]);
        }
    } catch (PDOException $e) {
        sendAuthResponse(500, [
            'status' => 'error',
            'code' => 500,
            'message' => 'Internal Server Error during authentication query.',
            'details' => $e->getMessage()
        ]);
    }
}

// 4. Unsupported HTTP Methods
sendAuthResponse(405, [
    'status' => 'error',
    'code' => 405,
    'message' => "Method Not Allowed. Only GET and POST are supported by auth endpoint."
]);
