<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit();
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(["success" => false, "message" => "Method not allowed"]);
  exit();
}

// Read JSON or form body
$raw = file_get_contents("php://input");
$js  = json_decode($raw, true) ?: [];
function input($k,$def=''){ return isset($_POST[$k]) ? trim($_POST[$k]) : $def; }

$identifier = isset($js['username']) ? trim($js['username']) : (isset($js['email']) ? trim($js['email']) : (input('username') ?: input('email')));
$password   = isset($js['password']) ? $js['password'] : input('password','');

if ($identifier === '' || $password === '') {
  echo json_encode(["success" => false, "message" => "Missing username/email or password"]);
  exit();
}

$servername = "localhost";
$username_db = "root";
$dbpassword = "";
$dbname = "SmokeDetectiondb";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
  $conn = new mysqli($servername, $username_db, $dbpassword, $dbname);

  // Check by username OR email
  $stmt = $conn->prepare("SELECT * FROM `User` WHERE `email` = ? OR `username` = ? LIMIT 1");
  $stmt->bind_param("ss", $identifier, $identifier);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    $stmt->close();
    $conn->close();
    exit();
  }

  $user = $result->fetch_assoc();
  
  // Verify password
  if (password_verify($password, $user['password'])) {
    // Password is correct, return user data (without password)
    unset($user['password']);
    echo json_encode([
      "success" => true, 
      "message" => "Login successful",
      "user" => $user
    ]);
  } else {
    echo json_encode(["success" => false, "message" => "Invalid password"]);
  }

  $stmt->close();
  $conn->close();
} catch (mysqli_sql_exception $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "DB error", "error" => $e->getMessage()]);
}
?>
