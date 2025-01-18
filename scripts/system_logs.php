<?php
// 데이터베이스 연결
include 'db_connect.php';

// 로그 가져오기
$sql = "SELECT * FROM system_logs ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>시스템 로그</title>
</head>
<body>
    <h1>시스템 로그</h1>
    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>내용</th>
                <th>날짜</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['message']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
