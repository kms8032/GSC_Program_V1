<?php
// 데이터베이스 연결
include 'db_connect.php';

// 학년 필터링
$grade_filter = $_GET['grade'] ?? 'all';

if ($grade_filter === 'all') {
    $sql = "SELECT * FROM notices ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT * FROM notices WHERE grade = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $grade_filter);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 목록</title>
    <link rel="stylesheet" href="../assets/styles/notice_list.css">
    <style>
        .back-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ffcc00;
            color: #000;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #ffaa00;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 공지사항 제목 -->
        <div class="notice-header">
            <h2>공지사항</h2>
        </div>

        <!-- 학년 필터 -->
        <div class="filter">
            <form method="GET" action="notice_list.php">
                <label for="grade-filter">학년:</label>
                <select id="grade-filter" name="grade" onchange="this.form.submit()">
                    <option value="all" <?= $grade_filter === 'all' ? 'selected' : '' ?>>전체</option>
                    <option value="1" <?= $grade_filter === '1' ? 'selected' : '' ?>>1학년</option>
                    <option value="2" <?= $grade_filter === '2' ? 'selected' : '' ?>>2학년</option>
                    <option value="3" <?= $grade_filter === '3' ? 'selected' : '' ?>>3학년</option>
                </select>
            </form>
        </div>

        <!-- 공지사항 테이블 -->
        <table class="notice-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>학년</th>
                    <th>제목</th>
                    <th>날짜</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['grade']) ?>학년</td>
                            <td><a href="notice_detail.php?id=<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['title']) ?></a></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">공지사항이 없습니다.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- 뒤로가기 버튼 -->
        <button class="back-button" onclick="goBack()">뒤로가기</button>
    </div>

    <script>
        function goBack() {
            window.location.href = "../student_dashboard.html";
        }
    </script>
</body>
</html>
