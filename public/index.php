<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Response;
use App\Mock;
use App\Auth;

// Handle preflight for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    Response::json(['ok' => true]); // 200 + CORS headers
}

$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path   = rtrim($path, '/') ?: '/';

// Utilities
function readJsonBody(): array {
    $raw = file_get_contents('php://input') ?: '';
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

// Health
if ($method === 'GET' && $path === '/status') {
    Response::json(['ok' => true, 'php' => PHP_VERSION]);
}

/* -------------------- STUDENTS -------------------- */

// GET /students
if ($method === 'GET' && $path === '/students') {
    Response::json(array_values(Mock::$students));
}

// GET /students/{id}
if ($method === 'GET' && preg_match('#^/students/(\d+)$#', $path, $m)) {
    $id = (int)$m[1];
    if (isset(Mock::$students[$id])) Response::json(Mock::$students[$id]);
    Response::json(['error' => 'Student not found'], 404);
}

// POST /students
if ($method === 'POST' && $path === '/students') {
    $in = readJsonBody();
    $name = trim($in['name'] ?? '');
    $email = trim($in['email'] ?? '');
    if ($name === '' || $email === '') {
        Response::json(['error' => 'name and email are required'], 422);
    }
    $id = Mock::nextId(Mock::$students);
    Mock::$students[$id] = ['id' => $id, 'name' => $name, 'email' => $email];
    Response::json(Mock::$students[$id], 201);
}

// PUT /students/{id}
if ($method === 'PUT' && preg_match('#^/students/(\d+)$#', $path, $m)) {
    $id = (int)$m[1];
    if (!isset(Mock::$students[$id])) Response::json(['error' => 'Student not found'], 404);
    $in = readJsonBody();
    Mock::$students[$id]['name']  = $in['name']  ?? Mock::$students[$id]['name'];
    Mock::$students[$id]['email'] = $in['email'] ?? Mock::$students[$id]['email'];
    Response::json(Mock::$students[$id]);
}

// DELETE /students/{id}  (SECURE)
if ($method === 'DELETE' && preg_match('#^/students/(\d+)$#', $path, $m)) {
    Auth::requireBearer();
    $id = (int)$m[1];
    if (!isset(Mock::$students[$id])) Response::json(['error' => 'Student not found'], 404);
    unset(Mock::$students[$id]);
    Response::json(['deleted' => true]);
}

/* -------------------- COURSES -------------------- */

// GET /courses
if ($method === 'GET' && $path === '/courses') {
    Response::json(array_values(Mock::$courses));
}

// GET /courses/{id}
if ($method === 'GET' && preg_match('#^/courses/(\d+)$#', $path, $m)) {
    $id = (int)$m[1];
    if (isset(Mock::$courses[$id])) Response::json(Mock::$courses[$id]);
    Response::json(['error' => 'Course not found'], 404);
}

// POST /courses
if ($method === 'POST' && $path === '/courses') {
    Auth::requireBearer();
    $in = readJsonBody();
    $code  = trim($in['code']  ?? '');
    $title = trim($in['title'] ?? '');
    if ($code === '' || $title === '') {
        Response::json(['error' => 'code and title are required'], 422);
    }
    $id = Mock::nextId(Mock::$courses);
    Mock::$courses[$id] = ['id' => $id, 'code' => $code, 'title' => $title];
    Response::json(Mock::$courses[$id], 201);
}

// PUT /courses/{id}
if ($method === 'PUT' && preg_match('#^/courses/(\d+)$#', $path, $m)) {
    $id = (int)$m[1];
    if (!isset(Mock::$courses[$id])) Response::json(['error' => 'Course not found'], 404);
    $in = readJsonBody();
    Mock::$courses[$id]['code']  = $in['code']  ?? Mock::$courses[$id]['code'];
    Mock::$courses[$id]['title'] = $in['title'] ?? Mock::$courses[$id]['title'];
    Response::json(Mock::$courses[$id]);
}

// DELETE /courses/{id}  (SECURE)
if ($method === 'DELETE' && preg_match('#^/courses/(\d+)$#', $path, $m)) {
    Auth::requireBearer();
    $id = (int)$m[1];
    if (!isset(Mock::$courses[$id])) Response::json(['error' => 'Course not found'], 404);
    unset(Mock::$courses[$id]);
    Response::json(['deleted' => true]);
}

/* -------------------- ENROLLMENTS -------------------- */

// GET /enrollments
if ($method === 'GET' && $path === '/enrollments') {
    Response::json(array_values(Mock::$enrollments));
}

// POST /enrollments  (SECURE)
if ($method === 'POST' && $path === '/enrollments') {
    Auth::requireBearer();
    $in = readJsonBody();
    $sid = (int)($in['student_id'] ?? 0);
    $cid = (int)($in['course_id'] ?? 0);
    if (!$sid || !$cid || !isset(Mock::$students[$sid]) || !isset(Mock::$courses[$cid])) {
        Response::json(['error' => 'valid student_id and course_id are required'], 422);
    }
    $id = Mock::nextId(Mock::$enrollments);
    Mock::$enrollments[$id] = ['id' => $id, 'student_id' => $sid, 'course_id' => $cid];
    Response::json(Mock::$enrollments[$id], 201);
}

// DELETE /enrollments/{id}  (SECURE)
if ($method === 'DELETE' && preg_match('#^/enrollments/(\d+)$#', $path, $m)) {
    Auth::requireBearer();
    $id = (int)$m[1];
    if (!isset(Mock::$enrollments[$id])) Response::json(['error' => 'Enrollment not found'], 404);
    unset(Mock::$enrollments[$id]);
    Response::json(['deleted' => true]);
}

// If we get here, no route matched
Response::json(['error' => "Route not found: $method $path"], 404);
