<?php
// 데이터베이스 연결
include 'db_connect.php';

// 학년 필터링
$grade_filter = $_GET['grade'] ?? '1'; // 기본값은 1학년
$table_name = "schedules_grade" . $grade_filter;

// 시간표 가져오기
$sql = "SELECT * FROM $table_name ORDER BY day_of_week, class";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>학년별 시간표 관리</title>
</head>
<body>
    <h1>학년별 시간표 관리</h1>
    <form method="GET" action="manage_schedules.php">
        <label for="grade">학년:</label>
        <select name="grade" id="grade" onchange="this.form.submit()">
            <option value="1" <?= $grade_filter == '1' ? 'selected' : '' ?>>1학년</option>
            <option value="2" <?= $grade_filter == '2' ? 'selected' : '' ?>>2학년</option>
            <option value="3" <?= $grade_filter == '3' ? 'selected' : '' ?>>3학년</option>
        </select>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>요일</th>
                <th>교시</th>
                <th>과목</th>
                <th>강의실</th>
                <th>작업</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['day_of_week']) ?></td>
                    <td><?= $row['class'] ?>교시</td>
                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                    <td><?= htmlspecialchars($row['classroom']) ?></td>
                    <td>
                        <a href="edit_schedule.php?grade=<?= $grade_filter ?>&id=<?= $row['id'] ?>">수정</a>
                        <a href="delete_schedule.php?grade=<?= $grade_filter ?>&id=<?= $row['id'] ?>" onclick="return confirm('삭제하시겠습니까?')">삭제</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
