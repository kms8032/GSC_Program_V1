<?php
// 데이터베이스 연결
include 'db_connect.php';

// 학년 필터링
$grade_filter = $_GET['grade'] ?? '1'; // 기본값은 1학년

// 학년에 따라 테이블 이름 설정
$table_name = "schedules_grade" . $grade_filter;

// 시간표 데이터 가져오기
$sql = "SELECT * FROM $table_name ORDER BY day_of_week, class";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// 요일 매핑
$days = ["월요일", "화요일", "수요일", "목요일", "금요일"];

// 시간표 데이터 저장
$schedule_data = [];
while ($row = $result->fetch_assoc()) {
    $schedule_data[$row['day_of_week']][$row['class']] = $row;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>시간표</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0099ff;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 1000px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .filter {
            margin-bottom: 20px;
            text-align: center;
        }

        .filter label {
            font-weight: bold;
        }

        select {
            padding: 5px 10px;
            font-size: 1em;
            margin-left: 10px;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr); /* 6 열: 시간 + 요일 */
            gap: 10px;
            text-align: center;
        }

        .schedule-grid div {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .schedule-grid .header {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .schedule-item {
            background-color: #90caf9; /* 기본 색상 */
            color: #000;
        }

        .schedule-grid .time {
            font-weight: bold;
            background-color: #e1f5fe;
        }

        .back-button {
            display: block;
            margin: 20px auto 0;
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
        <!-- 제목 -->
        <h2>학년별 시간표</h2>

        <!-- 학년 필터 -->
        <div class="filter">
            <form method="GET" action="schedule_list.php">
                <label for="grade">학년:</label>
                <select name="grade" id="grade" onchange="this.form.submit()">
                    <option value="1" <?= $grade_filter == '1' ? 'selected' : '' ?>>1학년</option>
                    <option value="2" <?= $grade_filter == '2' ? 'selected' : '' ?>>2학년</option>
                    <option value="3" <?= $grade_filter == '3' ? 'selected' : '' ?>>3학년</option>
                </select>
            </form>
        </div>

        <!-- 시간표 -->
        <div class="schedule-grid">
            <!-- 시간표 헤더 -->
            <div class="header">시간</div>
            <?php foreach ($days as $day): ?>
                <div class="header"><?= $day ?></div>
            <?php endforeach; ?>

            <!-- 시간표 데이터 -->
            <?php
            for ($class = 1; $class <= 12; $class++): ?>
                <div class="time"><?= $class ?>교시</div>
                <?php foreach ($days as $day): ?>
                    <?php if (isset($schedule_data[$day][$class])): ?>
                        <?php $row = $schedule_data[$day][$class]; ?>
                        <div class="schedule-item">
                            <?= htmlspecialchars($row['subject_name']) ?><br><?= htmlspecialchars($row['classroom']) ?>
                        </div>
                    <?php else: ?>
                        <div></div> <!-- 빈 칸 -->
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>

        <!-- 뒤로가기 버튼 -->
        <button class="back-button" onclick="window.location.href='../student_dashboard.html';">돌아가기</button>
    </div>
</body>
</html>
