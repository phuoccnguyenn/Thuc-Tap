<?php

if (isset($_POST['add_hp'])) {
    $mahp = $_POST['mahp'];
    $tenhp = $_POST['tenhp'];
    $tinchi = intval($_POST['tinchi']);
    $tien_chi = intval($_POST['tien_chi']);

    $sotien = $tinchi * $tien_chi;
    $id = $_POST['teacher'];
    $idhk = $_POST['idhk'];
    $soluongsv = intval($_POST['soluongsv']);
    $soluonggv = intval($_POST['soluonggv']);

    try {
        $stmt = $conn->prepare("CALL sp_InsertHocPhan(?, ?, ?, ?, ?, ?, ?, ?,?)");
        $stmt->execute([$mahp, $tenhp, $tinchi,$tien_chi, $sotien, $id, $idhk, $soluongsv, $soluonggv]);

    } catch (PDOException $e) {
        $message[] = 'Lỗi khi thêm thời khóa biểu: ' . $e->getMessage();
    }
}
?>
