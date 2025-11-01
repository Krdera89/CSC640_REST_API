<?php
namespace App;

class Mock {
    public static array $students = [
        1 => ["id" => 1, "name" => "Kevin",   "email" => "kevin@example.com"],
        2 => ["id" => 2, "name" => "Anthony", "email" => "anthony@example.com"],
    ];

    public static array $courses = [
        1 => ["id" => 1, "code" => "CSC640", "title" => "Software Engineering"],
        2 => ["id" => 2, "code" => "CSC601", "title" => "Algorithms"],
    ];

    // super simple enrollment model: id, student_id, course_id
    public static array $enrollments = [
        1 => ["id" => 1, "student_id" => 1, "course_id" => 1],
    ];

    public static function nextId(array $arr): int {
        return $arr ? max(array_keys($arr)) + 1 : 1;
    }
}
