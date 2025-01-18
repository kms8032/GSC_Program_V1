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

// 수정 상태 확인
$edit_mode = false;
$edit_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $day_of_week = $_POST['day_of_week'];
        $class = $_POST['class'];
        $subject_name = $_POST['subject_name'];
        $classroom = $_POST['classroom'];

        $sql = "INSERT INTO $table_name (day_of_week, class, subject_name, classroom) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('siss', $day_of_week, $class, $subject_name, $classroom);
        $stmt->execute();

        header("Location: admin_schedule.php?grade=$grade_filter");
        exit();
    } elseif ($action === 'edit') {
        $id = $_POST['id'];
        $sql = "SELECT * FROM $table_name WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $edit_data = $result->fetch_assoc();
        $edit_mode = true;
    } elseif ($action === 'update') {
        $id = $_POST['id'];
        $day_of_week = $_POST['day_of_week'];
        $class = $_POST['class'];
        $subject_name = $_POST['subject_name'];
        $classroom = $_POST['classroom'];

        $sql = "UPDATE $table_name SET day_of_week = ?, class = ?, subject_name = ?, classroom = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sissi', $day_of_week, $class, $subject_name, $classroom, $id);
        $stmt->execute();

        header("Location: admin_schedule.php?grade=$grade_filter");
        exit();
    } elseif ($action === 'delete') {
        $id = $_POST['id'];

        $sql = "DELETE FROM $table_name WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        header("Location: admin_schedule.php?grade=$grade_filter");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 시간표 관리</title>
    <style>
        /* 기존 스타일 유지 */
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
            grid-template-columns: repeat(6, 1fr);
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
            background-color: #90caf9;
            color: #000;
        }

        .action-buttons button {
            margin: 5px;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-button {
            background-color: #4caf50;
            color: white;
        }

        .delete-button {
            background-color: #f44336;
            color: white;
        }

        .add-form {
            margin-top: 20px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>관리자 시간표 관리</h2>

        <!-- 학년 필터 -->
        <div class="filter">
            <form method="GET" action="admin_schedule.php">
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
            <div class="header">교시</div>
            <?php foreach ($days as $day): ?>
                <div class="header"><?= $day ?></div>
            <?php endforeach; ?>

            <?php for ($class = 1; $class <= 12; $class++): ?>
                <div><?= $class ?>교시</div>
                <?php foreach ($days as $day): ?>
                    <?php if (isset($schedule_data[$day][$class])): ?>
                        <?php $row = $schedule_data[$day][$class]; ?>
                        <div class="schedule-item">
                            <?= htmlspecialchars($row['subject_name']) ?><br>
                            <?= htmlspecialchars($row['classroom']) ?><br>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="edit">
                                <button type="submit" class="edit-button">수정</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="delete-button">삭제</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endfor; ?>
        </div>

        <!-- 수정 및 추가 폼 -->
        <form class="add-form" method="POST" action="admin_schedule.php">
            <h3><?= $edit_mode ? '시간표 수정' : '시간표 추가' ?></h3>
            <input type="hidden" name="action" value="<?= $edit_mode ? 'update' : 'add' ?>">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
            <?php endif; ?>
            <label>요일:</label>
            <select name="day_of_week" required>
                <?php foreach ($days as $day): ?>
                    <option value="<?= $day ?>" <?= $edit_mode && $edit_data['day_of_week'] == $day ? 'selected' : '' ?>><?= $day ?></option>
                <?php endforeach; ?>
            </select>
            <label>교시:</label>
            <input type="number" name="class" min="1" max="12" value="<?= $edit_mode ? $edit_data['class'] : '' ?>" required>
            <label>과목명:</label>
            <input type="text" name="subject_name" value="<?= $edit_mode ? htmlspecialchars($edit_data['subject_name']) : '' ?>" required>
            <label>강의실:</label>
            <input type="text" name="classroom" value="<?= $edit_mode ? htmlspecialchars($edit_data['classroom']) : '' ?>" required>
            <button type="submit"><?= $edit_mode ? '수정 완료' : '추가' ?></button>
        </form>

        <!-- 뒤로가기 버튼 -->
        <button class="back-button" onclick="window.location.href='../admin_dashboard.html';">뒤로가기</button>
    </div>
</body>
</html>
