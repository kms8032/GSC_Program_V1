<?php
// 데이터베이스 연결
include 'db_connect.php';

// 공지사항 ID 가져오기
$notice_id = $_GET['id'] ?? null;

// 공지사항 데이터 가져오기
$notice = null;
if ($notice_id) {
    $sql = "SELECT * FROM notices WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notice_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notice = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 상세</title>
    <link rel="stylesheet" href="../assets/styles/notice_detail.css">
</head>
<body>
    <div class="container">
        <div class="notice-header">
            <h2>공지사항</h2>
        </div>

        <?php if ($notice): ?>
            <div class="notice-detail">
                <h3 class="notice-title"><?= htmlspecialchars($notice['title']) ?></h3>
                <div class="notice-meta">
                    <span>게시일: <?= $notice['created_at'] ?></span>
                    <span>작성자: <?= htmlspecialchars($notice['author'] ?? '관리자') ?></span>
                </div>
                <div class="notice-content">
                    <?= nl2br(htmlspecialchars($notice['content'])) ?>
                </div>
            </div>
        <?php else: ?>
            <p>공지사항을 찾을 수 없습니다.</p>
        <?php endif; ?>

        <button class="back-button" onclick="window.history.back();">돌아가기</button>
    </div>
</body>
</html>
