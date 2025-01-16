<?php
// db_connect.php 파일을 포함하여 데이터베이스 연결
include 'db_connect.php';

// POST 방식으로 전달된 데이터 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 사용자 입력값 가져오기
    $name = $_POST['name'];
    $student_id = $_POST['student-id'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $user_type = $_POST['user-type']; // 학생, 교수, 관리자

    // 비밀번호 확인
    if ($password !== $confirm_password) {
        echo "비밀번호가 일치하지 않습니다.";
        exit();
    }

    // SQL 쿼리: 사용자 정보 삽입
    $sql = "INSERT INTO users (name, student_id, password, role) 
            VALUES (?, ?, ?, ?)";

    // 쿼리 준비 및 바인딩
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $student_id, $password, $user_type);

    // 쿼리 실행
    if ($stmt->execute()) {
        echo "회원가입이 완료되었습니다.";
        // 회원가입 완료 후, 로그인 페이지나 다른 페이지로 리다이렉트할 수 있습니다.
        // header("Location: login.php"); 
    } else {
        echo "회원가입 실패: " . $stmt->error;
    }

    // 연결 종료
    $stmt->close();
    $conn->close();
} else {
    echo "잘못된 요청입니다.";
}
?>
