<?php
// 데이터베이스 연결
include 'db_connect.php';

// 공지사항 목록 가져오기
$sql = "SELECT * FROM notices ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 관리</title>
</head>
<body>
    <h1>공지사항 관리</h1>
    <a href="add_notice.php">공지사항 추가</a>
    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>제목</th>
                <th>작성일</th>
                <th>작업</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="edit_notice.php?id=<?= $row['id'] ?>">수정</a>
                        <a href="delete_notice.php?id=<?= $row['id'] ?>" onclick="return confirm('삭제하시겠습니까?')">삭제</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
