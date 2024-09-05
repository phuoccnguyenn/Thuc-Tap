<?php
if (isset($_POST['update_hp'])) {
    $idhp = $_POST['idhp'];
    $mahp = $_POST['mahp'];
    $tenhp = $_POST['tenhp'];
    $tinchi = intval($_POST['tinchi']);
    $sotien = $tinchi * 360;
    $id = $_POST['teacher'];
    $idhk = $_POST['idhk'];
    $soluongsv = intval($_POST['soluongsv']);
    $soluonggv = intval($_POST['soluonggv']);


    try {
        // Gọi stored procedure để cập nhật học phần
        $stmt = $conn->prepare("CALL sp_UpdateHocPhan(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$idhp, $mahp, $tenhp, $tinchi, $tinchi , $sotien, $id, $idhk, $soluongsv, $soluonggv]);


    } catch (PDOException $e) {
        $message[] = 'Lỗi khi truy vấn thông tin thời khóa biểu: ' . $e->getMessage();

    }
}
?>
