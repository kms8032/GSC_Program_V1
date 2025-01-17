<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 사용자 입력 값 가져오기
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type']; // 사용자가 선택한 사용자 유형 가져오기

    // 데이터베이스 연결
    include 'db_connect.php';

    // 디버깅: 입력 값 확인
    echo "입력된 학번: " . $student_id . "<br>";
    echo "입력된 사용자 유형: " . $user_type . "<br>";

    // 사용자 인증 로직
    $sql = "SELECT * FROM users WHERE student_id = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $student_id, $user_type); // 학번과 사용자 유형을 바인딩
    $stmt->execute();
    $result = $stmt->get_result();

    // 디버깅: 쿼리 결과 확인
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 평문 비밀번호 비교
        if ($password === $user['password']) {
            // 로그인 성공
            echo "로그인 성공!";

            // 학생 권한일 경우 student_dashboard.html로 이동
            if ($user['role'] === '학생') {
                header("Location: ../student_dashboard.html");
                exit; // 리다이렉트 후 코드 실행 중단
            } else {
                echo "권한이 없습니다.";
            }
        } else {
            echo "비밀번호가 틀렸습니다.";
        }
    } else {
        echo "학번 또는 사용자 유형이 존재하지 않습니다.";
    }

    // 연결 해제
    $stmt->close();
    $conn->close();
} else {
    echo "올바르지 않은 요청입니다.";
}
?>
