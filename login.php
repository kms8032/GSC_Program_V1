<?php
session_start();
// 세션 오류 메시지 처리
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
    <link rel="stylesheet" href="assets/styles/login.css"> <!-- 스타일 파일 경로 -->
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="assets/logo.png" alt="로고">
            <h1>영진전문대학교<br>글로벌시스템융합과</h1>
        </div>
        <form action="scripts/login_process.php" method="POST">
            <label for="student-id">학번:</label>
            <input type="text" id="student-id" name="student_id" placeholder="학번 입력" required>

            <label for="password">PASSWORD:</label>
            <input type="password" id="password" name="password" placeholder="비밀번호 입력" required>

            <?php if (!empty($error_message)): ?>
                <div style="color: red; margin-bottom: 10px;">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <button type="submit">로그인</button>
        </form>
        <p>
            <a href="register.html">회원가입</a>
        </p>
    </div>
</body>
</html>
