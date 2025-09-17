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

// Read JSON body
$raw = file_get_contents("php://input");
$js  = json_decode($raw, true) ?: [];
function input($k,$def=''){ return isset($_POST[$k]) ? trim($_POST[$k]) : $def; }

$firstname = isset($js['firstname']) ? trim($js['firstname']) : input('firstname','');
$lastname  = isset($js['lastname'])  ? trim($js['lastname'])  : input('lastname','');
$email     = isset($js['email'])     ? trim($js['email'])     : input('email','');
$username  = isset($js['username'])  ? trim($js['username'])  : input('username','');
$password  = isset($js['password'])  ? $js['password']        : input('password','');
$phone     = isset($js['phone_number']) ? trim($js['phone_number']) : input('phone_number','');

// Derive username from email if not provided
if ($username === '' && $email !== '') {
  $parts = explode('@',$email);
  if (!empty($parts[0])) { $username = $parts[0]; }
}

// Require at minimum username (or email) and password
if (($username === '' && $email === '') || $password === '') {
  echo json_encode(["success" => false, "message" => "Missing required fields: username/email and password are required"]);
  exit();
}

$servername = "localhost";
$username   = "root";
$dbpassword = "";
$dbname     = "SmokeDetectiondb";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
  $conn = new mysqli($servername, $username, $dbpassword, $dbname);

  // Optional: check existing email/username
  $check = $conn->prepare("SELECT 1 FROM `User` WHERE `email` = ? OR `username` = ? LIMIT 1");
  $check->bind_param("ss", $email, $username);
  $check->execute();
  $check->store_result();
  if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email/Username already registered"]);
    $check->close();
    $conn->close();
    exit();
  }
  $check->close();

  $hash = password_hash($password, PASSWORD_BCRYPT);
  $insertedId = null;
  try {
    // Preferred schema with username column
    $stmt = $conn->prepare("INSERT INTO `User` (`firstname`, `lastname`, `email`, `username`, `password`, `phone_number`) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstname, $lastname, $email, $username, $hash, $phone);
    $stmt->execute();
    $insertedId = $conn->insert_id;
    $stmt->close();
  } catch (mysqli_sql_exception $e) {
    // Fallback schema without username column
    if (strpos($e->getMessage(), 'Unknown column') !== false || strpos($e->getMessage(), 'unknown column') !== false) {
      $stmt2 = $conn->prepare("INSERT INTO `User` (`firstname`, `lastname`, `email`, `password`, `phone_number`) VALUES (?, ?, ?, ?, ?)");
      $stmt2->bind_param("sssss", $firstname, $lastname, $email, $hash, $phone);
      $stmt2->execute();
      $insertedId = $conn->insert_id;
      $stmt2->close();
    } else {
      throw $e;
    }
  }

  echo json_encode(["success" => true, "message" => "User registered successfully", "user" => ["id"=>$insertedId, "username"=>$username, "email"=>$email]]);

  $conn->close();
} catch (mysqli_sql_exception $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "message" => "DB error", "error" => $e->getMessage()]);
}