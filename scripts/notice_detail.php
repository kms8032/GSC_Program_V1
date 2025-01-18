<?php
// 데이터베이스 연결
include 'db_connect.php';

// 공지사항 ID 가져오기
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "잘못된 접근입니다.";
    exit;
}

// 공지사항 데이터 가져오기
$sql = "SELECT * FROM notices WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "공지사항을 찾을 수 없습니다.";
    exit;
}

$notice = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 상세보기</title>
    <link rel="stylesheet" href="../assets/styles/notice_detail.css">
</head>
<body>
    <div class="container">
        <!-- 공지사항 제목 -->
        <div class="notice-header">
            <h2><?= htmlspecialchars($notice['title']) ?></h2>
        </div>

        <!-- 공지사항 내용 -->
        <div class="notice-content">
            <p>게시일: <?= htmlspecialchars($notice['created_at']) ?></p>
            <p>학년: <?= htmlspecialchars($notice['grade']) ?>학년</p>
            <p><?= nl2br(htmlspecialchars($notice['content'])) ?></p>
        </div>

        <!-- 뒤로가기 버튼 -->
        <button class="back-button" onclick="window.history.back();">돌아가기</button>
    </div>
</body>
</html>
