<?php
// 데이터베이스 연결
include 'db_connect.php';

// 사용자 목록 가져오기
$sql = "SELECT * FROM users ORDER BY role, student_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>사용자 관리</title>
</head>
<body>
    <h1>사용자 관리</h1>
    <a href="add_user.php">사용자 추가</a>
    <table border="1">
        <thead>
            <tr>
                <th>학번</th>
                <th>이름</th>
                <th>역할</th>
                <th>작업</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['student_id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['role'] ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $row['student_id'] ?>">수정</a>
                        <a href="delete_user.php?id=<?= $row['student_id'] ?>" onclick="return confirm('삭제하시겠습니까?')">삭제</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
