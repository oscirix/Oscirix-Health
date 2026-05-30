<?php

date_default_timezone_set('America/Guayaquil');

header('Content-Type: application/json; charset=UTF-8');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Use POST.',
    ]);
    exit;
}

// ==============================
// CONFIGURATION
// ==============================

$secret_token = 'ia_clinic_auto_token_123';

$db_host = 'localhost';
$db_name = 'u293403309_ia_clinic_auto';
$db_user = 'u293403309_ia_clinic_auto';
$db_password = 'Ia_clinic_auto123';

// ==============================
// VALIDATE TOKEN
// ==============================

$headers = getallheaders();
$auth_header = $headers['Authorization'] ?? $headers['authorization'] ?? '';

if ($auth_header !== 'Bearer '.$secret_token) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized. Invalid or missing token.',
    ]);
    exit;
}

// ==============================
// READ JSON BODY
// ==============================

$raw_data = file_get_contents('php://input');
$request_data = json_decode($raw_data, true);

if (!is_array($request_data)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON.',
        'raw' => $raw_data,
    ]);
    exit;
}

// ==============================
// GET DATA FROM N8N
// ==============================

$full_name = trim($request_data['full_name'] ?? '');
$phone_number = trim($request_data['phone_number'] ?? '');
$date = trim($request_data['date'] ?? '');

if ($full_name === '' || $phone_number === '' || $date === '') {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Missing required data.',
        'required' => [
            'full_name',
            'phone_number',
            'date',
        ],
        'received' => $request_data,
    ]);
    exit;
}

// ==============================
// FORMAT APPOINTMENT DATE
// ==============================

try {
    $timezone = new DateTimeZone('America/Guayaquil');

    $appointment_datetime = new DateTime($date, $timezone);
    $appointment_datetime->setTimezone($timezone);

    $appointment_date = $appointment_datetime->format('Y-m-d H:i:s');
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid date format. Use preferably: YYYY-MM-DD HH:MM:SS',
        'date_received' => $date,
    ]);
    exit;
}

$created_at = date('Y-m-d H:i:s');

// ==============================
// SAVE INTO MYSQL
// ==============================

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $pdo->exec("SET time_zone = '-05:00'");

    $sql = '
        INSERT INTO appointments_n8n 
        (full_name, phone_number, appointment_date, created_at)
        VALUES 
        (:full_name, :phone_number, :appointment_date, :created_at)
    ';

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':full_name' => $full_name,
        ':phone_number' => $phone_number,
        ':appointment_date' => $appointment_date,
        ':created_at' => $created_at,
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Appointment saved successfully.',
        'id' => $pdo->lastInsertId(),
        'data' => [
            'full_name' => $full_name,
            'phone_number' => $phone_number,
            'appointment_date' => $appointment_date,
            'created_at' => $created_at,
        ],
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database save error.',
        'error' => $e->getMessage(),
    ]);
    exit;
}
