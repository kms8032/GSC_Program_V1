<?php
include 'db_connect.php';

// 폼에서 전달된 데이터 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 사용자 입력값 가져오기
    $name = $_POST['name'];
    $student_id = $_POST['student-id'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $role = $_POST['user-type']; // 선택된 사용자 유형

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
    $stmt->bind_param("ssss", $name, $student_id, $password, $role);

    // 쿼리 실행
    if ($stmt->execute()) {
        echo "회원가입이 완료되었습니다.";
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
