<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Response;
use App\Database;
use App\Auth;

// Handle preflight for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    Response::json(['ok' => true]);
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
    Response::json(['ok' => true, 'php' => PHP_VERSION, 'database' => 'MySQL']);
}

/* -------------------- STUDENTS -------------------- */

// GET /students
if ($method === 'GET' && $path === '/students') {
    Response::json(Database::getAllStudents());
}

// GET /students/{id}
if ($method === 'GET' && preg_match('#^/students/(\d+)$#', $path, $m)) {
    $id = (int)$m[1];
    $student = Database::getStudentById($id);
    if ($student) Response::json($student);
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
    try {
        $student = Database::createStudent($name, $email);
        Response::json($student, 201);
    } catch (Exception $e) {
        Response::json(['error' => 'Could not create student: ' . $e->getMessage()], 500);
    }
}

// PUT /students/{id}
if ($method === 'PUT' && preg_match('#^/students/(\d+)$#', $path, $m)) {
    $id = (int)$m[1];
    $student = Database::getStudentById($id);
    if (!$student) Response::json(['error' => 'Student not found'], 404);
    
    $in = readJsonBody();
    $name = trim($in['name'] ?? $student['name']);
    $email = trim($in['email'] ?? $student['email']);
    
    Database::updateStudent($id, $name, $email);
    Response::json(['id' => $id, 'name' => $name, 'email' => $email]);
}

// DELETE /students/{id}  (SECURE)
if ($method === 'DELETE' && preg_match('#^/students/(\d+)$#', $path, $m)) {
    Auth::requireBearer();
    $id = (int)$m[1];
    if (!Database::getStudentById($id)) Response::json(['error' => 'Student not found'], 404);
    Database::deleteStudent($id);
    Response::json(['deleted' => true]);
}

/* -------------------- COURSES -------------------- */

// GET /courses
if ($method === 'GET' && $path === '/courses') {
    Response::json(Database::getAllCourses());
}

// GET /courses/{id}
if ($method === 'GET' && preg_match('#^/courses/(\d+)$#', $path, $m)) {
    $id = (int)$m[1];
    $course = Database::getCourseById($id);
    if ($course) Response::json($course);
    Response::json(['error' => 'Course not found'], 404);
}

// POST /courses (SECURE)
if ($method === 'POST' && $path === '/courses') {
    Auth::requireBearer();
    $in = readJsonBody();
    $code  = trim($in['code']  ?? '');
    $title = trim($in['title'] ?? '');
    if ($code === '' || $title === '') {
        Response::json(['error' => 'code and title are required'], 422);
    }
    try {
        $course = Database::createCourse($code, $title);
        Response::json($course, 201);
    } catch (Exception $e) {
        Response::json(['error' => 'Could not create course: ' . $e->getMessage()], 500);
    }
}

// PUT /courses/{id}
if ($method === 'PUT' && preg_match('#^/courses/(\d+)$#', $path, $m)) {
    $id = (int)$m[1];
    $course = Database::getCourseById($id);
    if (!$course) Response::json(['error' => 'Course not found'], 404);
    
    $in = readJsonBody();
    $code = trim($in['code'] ?? $course['code']);
    $title = trim($in['title'] ?? $course['title']);
    
    Database::updateCourse($id, $code, $title);
    Response::json(['id' => $id, 'code' => $code, 'title' => $title]);
}

// DELETE /courses/{id}  (SECURE)
if ($method === 'DELETE' && preg_match('#^/courses/(\d+)$#', $path, $m)) {
    Auth::requireBearer();
    $id = (int)$m[1];
    if (!Database::getCourseById($id)) Response::json(['error' => 'Course not found'], 404);
    Database::deleteCourse($id);
    Response::json(['deleted' => true]);
}

/* -------------------- ENROLLMENTS -------------------- */

// GET /enrollments
if ($method === 'GET' && $path === '/enrollments') {
    Response::json(Database::getAllEnrollments());
}

// POST /enrollments  (SECURE)
if ($method === 'POST' && $path === '/enrollments') {
    Auth::requireBearer();
    $in = readJsonBody();
    $sid = (int)($in['student_id'] ?? 0);
    $cid = (int)($in['course_id'] ?? 0);
    
    if (!$sid || !$cid) {
        Response::json(['error' => 'valid student_id and course_id are required'], 422);
    }
    
    $enrollment = Database::createEnrollment($sid, $cid);
    if (!$enrollment) {
        Response::json(['error' => 'Student or course not found'], 422);
    }
    Response::json($enrollment, 201);
}

// DELETE /enrollments/{id}  (SECURE)
if ($method === 'DELETE' && preg_match('#^/enrollments/(\d+)$#', $path, $m)) {
    Auth::requireBearer();
    $id = (int)$m[1];
    if (!Database::enrollmentExists($id)) Response::json(['error' => 'Enrollment not found'], 404);
    Database::deleteEnrollment($id);
    Response::json(['deleted' => true]);
}

// If we get here, no route matched
Response::json(['error' => "Route not found: $method $path"], 404);