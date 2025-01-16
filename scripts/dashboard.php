<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// 사용자 정보 가져오기
$name = $_SESSION['name'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>대시보드</title>
</head>
<body>
    <h1>안녕하세요, <?php echo htmlspecialchars($name); ?>님!</h1>
    <p>당신의 역할: <?php echo htmlspecialchars($role); ?></p>
    <a href="logout.php">로그아웃</a>
</body>
</html>
