<?php
session_start();
session_destroy(); // 모든 세션 삭제
header("Location: login.html"); // 로그인 페이지로 이동
exit();
?>
