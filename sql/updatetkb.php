<?php
if (isset($_POST['update_tkb'])) {
    $idhp = $_POST['idhp'];
    $idgv = $_POST['idgv'];
    $ngaybatdau = $_POST['ngaybatdau'];
    $ngayketthuc = $_POST['ngayketthuc'];
    $phonghoc = $_POST['phonghoc'];

    // Kiểm tra và làm sạch dữ liệu đầu vào
    $phonghoc = filter_var($phonghoc, FILTER_SANITIZE_STRING);
    $ngaybatdau = filter_var($ngaybatdau, FILTER_SANITIZE_STRING);
    $ngayketthuc = filter_var($ngayketthuc, FILTER_SANITIZE_STRING);

    try {
        // Tiếp tục thực hiện câu truy vấn UPDATE vào bảng "thoikhoabieu" với điều kiện ID thời khóa biểu
        $update = $conn->prepare("CALL sp_Updatetkb(?, ?, ?, ?, ?, ?)");
        $update->execute([ $update_idtkb,$phonghoc,$ngaybatdau, $ngayketthuc,$idhp, $idgv]);
        $message[] = 'Cập nhật thời khóa biểu thành công!';
    } catch (PDOException $e) {
        $message[] = 'Lỗi khi cập nhật thời khóa biểu: ' . $e->getMessage();
    }
}
// Tiến hành lấy thông tin thời khóa biểu cần cập nhật từ cơ sở dữ liệu
if (!empty($update_idtkb)) {
    try {
        $get_tkb = $conn->prepare("SELECT * FROM `thoikhoabieu` WHERE idtkb = ?");
        $get_tkb->execute([$update_idtkb]);

        if ($get_tkb->rowCount() > 0) {
            $fetch_tkb = $get_tkb->fetch(PDO::FETCH_ASSOC);
        } else {
            $message[] = 'Không tìm thấy thông tin thời khóa biểu!';
        }
    } catch (PDOException $e) {
        $message[] = 'Lỗi khi truy vấn thông tin thời khóa biểu: ' . $e->getMessage();
    }
}
?>