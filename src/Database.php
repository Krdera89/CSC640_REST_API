<?php
namespace App;

use PDO;
use PDOException;

class Database {
    private static ?PDO $pdo = null;

    public static function connect(): PDO {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=student_api;charset=utf8mb4',
                    'root',  // Default XAMPP username
                    '',      // Default XAMPP password (empty)
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
            }
        }
        return self::$pdo;
    }

    // Students
    public static function getAllStudents(): array {
        $stmt = self::connect()->query('SELECT * FROM students ORDER BY id');
        return $stmt->fetchAll();
    }

    public static function getStudentById(int $id): ?array {
        $stmt = self::connect()->prepare('SELECT * FROM students WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function createStudent(string $name, string $email): array {
        $stmt = self::connect()->prepare('INSERT INTO students (name, email) VALUES (?, ?)');
        $stmt->execute([$name, $email]);
        $id = (int)self::connect()->lastInsertId();
        return ['id' => $id, 'name' => $name, 'email' => $email];
    }

    public static function updateStudent(int $id, string $name, string $email): bool {
        $stmt = self::connect()->prepare('UPDATE students SET name = ?, email = ? WHERE id = ?');
        return $stmt->execute([$name, $email, $id]);
    }

    public static function deleteStudent(int $id): bool {
        $stmt = self::connect()->prepare('DELETE FROM students WHERE id = ?');
        return $stmt->execute([$id]);
    }

    // Courses
    public static function getAllCourses(): array {
        $stmt = self::connect()->query('SELECT * FROM courses ORDER BY id');
        return $stmt->fetchAll();
    }

    public static function getCourseById(int $id): ?array {
        $stmt = self::connect()->prepare('SELECT * FROM courses WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function createCourse(string $code, string $title): array {
        $stmt = self::connect()->prepare('INSERT INTO courses (code, title) VALUES (?, ?)');
        $stmt->execute([$code, $title]);
        $id = (int)self::connect()->lastInsertId();
        return ['id' => $id, 'code' => $code, 'title' => $title];
    }

    public static function updateCourse(int $id, string $code, string $title): bool {
        $stmt = self::connect()->prepare('UPDATE courses SET code = ?, title = ? WHERE id = ?');
        return $stmt->execute([$code, $title, $id]);
    }

    public static function deleteCourse(int $id): bool {
        $stmt = self::connect()->prepare('DELETE FROM courses WHERE id = ?');
        return $stmt->execute([$id]);
    }

    // Enrollments
    public static function getAllEnrollments(): array {
        $stmt = self::connect()->query('SELECT * FROM enrollments ORDER BY id');
        return $stmt->fetchAll();
    }

    public static function createEnrollment(int $studentId, int $courseId): ?array {
        // Check if student and course exist
        if (!self::getStudentById($studentId) || !self::getCourseById($courseId)) {
            return null;
        }
        
        $stmt = self::connect()->prepare('INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)');
        $stmt->execute([$studentId, $courseId]);
        $id = (int)self::connect()->lastInsertId();
        return ['id' => $id, 'student_id' => $studentId, 'course_id' => $courseId];
    }

    public static function deleteEnrollment(int $id): bool {
        $stmt = self::connect()->prepare('DELETE FROM enrollments WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function enrollmentExists(int $id): bool {
        $stmt = self::connect()->prepare('SELECT COUNT(*) FROM enrollments WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}