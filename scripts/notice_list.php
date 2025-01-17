<?php
// 데이터베이스 연결
include 'db_connect.php';

// 학년 필터링 (전체 보기 또는 특정 학년)
$grade_filter = $_GET['grade'] ?? 'all';
if ($grade_filter === 'all') {
    $sql = "SELECT * FROM notices ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM notices WHERE grade = ? ORDER BY created_at DESC";
}

$stmt = $conn->prepare($sql);

if ($grade_filter !== 'all') {
    $stmt->bind_param("s", $grade_filter); // 학년 필터 바인딩
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
</head>
<body>
    <div class="container">
        <!-- 공지사항 제목 -->
        <div class="notice-header">
            <h2>공지사항</h2>
        </div>

        <!-- 학년 필터 -->
        <div class="filter">
            <label for="grade-filter">학년:</label>
            <select id="grade-filter" onchange="filterNotices()">
                <option value="all" <?= $grade_filter === 'all' ? 'selected' : '' ?>>전체</option>
                <option value="1" <?= $grade_filter === '1' ? 'selected' : '' ?>>1학년</option>
                <option value="2" <?= $grade_filter === '2' ? 'selected' : '' ?>>2학년</option>
                <option value="3" <?= $grade_filter === '3' ? 'selected' : '' ?>>3학년</option>
            </select>
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
            <tbody id="notice-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . ($row['grade'] ?? '전체') . "</td>";
                        echo "<td><a href='notice_detail.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>공지사항이 없습니다.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- 뒤로가기 버튼 -->
        <button class="back-button" onclick="window.history.back();">돌아가기</button>
    </div>

    <script>
        // 필터 변경 시 URL 변경
        function filterNotices() {
            const filter = document.getElementById('grade-filter').value;
            window.location.href = `notice_list.php?grade=${filter}`;
        }
    </script>
</body>
</html>
