<?php
// 오류 표시 설정 (디버깅용)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

// 사용자 입력값 받기
$name = $_POST['name'] ?? null;
$student_id = $_POST['student_id'] ?? null;
$password = $_POST['password'] ?? null;
$confirm_password = $_POST['confirm_password'] ?? null;
$user_type = $_POST['user_type'] ?? null;

// 모든 필드 확인
if (empty($name) || empty($student_id) || empty($password) || empty($confirm_password) || empty($user_type)) {
    die("모든 필드를 작성해야 합니다.");
}

// 비밀번호 확인
if ($password !== $confirm_password) {
    die("비밀번호가 일치하지 않습니다.");
}

// 데이터베이스에 사용자 추가
$sql = "INSERT INTO users (student_id, name, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssss", $student_id, $name, $password, $user_type);
    if ($stmt->execute()) {
        echo "회원가입 성공!";
        header("Location: ../login.html");
        exit;
    } else {
        die("회원가입 중 오류 발생: " . $stmt->error);
    }
} else {
    die("쿼리 준비 중 오류 발생: " . $conn->error);
}
?>
