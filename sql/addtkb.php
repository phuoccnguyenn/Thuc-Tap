<?php
if (isset($_POST['add_tkb'])) {
    $idhp = $_POST['idhp'];
    $idgv = $_POST['idgv'];
    $ngaybatdau = $_POST['ngaybatdau'];
    $ngayketthuc = $_POST['ngayketthuc'];
    $phonghoc = $_POST['phonghoc'];
    $iddk = $_POST['iddk'];
    $tiethoc = $_POST['tiethoc'];
    $ngayhoc = $_POST['ngayhoc'];
    $thoigian = $_POST['thoigian'];
    $tuanhoc = $_POST['tuanhoc'];

    // Kiểm tra và làm sạch dữ liệu đầu vào
    $phonghoc = filter_var($phonghoc, FILTER_SANITIZE_STRING);
    $ngaybatdau = filter_var($ngaybatdau, FILTER_SANITIZE_STRING);
    $ngayketthuc = filter_var($ngayketthuc, FILTER_SANITIZE_STRING);
    $tiethoc = filter_var($tiethoc, FILTER_SANITIZE_STRING);
    $ngayhoc = filter_var($ngayhoc, FILTER_SANITIZE_STRING);
    $thoigian = filter_var($thoigian, FILTER_SANITIZE_STRING);
    $tuanhoc = filter_var($tuanhoc, FILTER_SANITIZE_STRING);

    try {
        // Kiểm tra biến kết nối $conn trước khi thực hiện truy vấn
        if ($conn) {
            // Tiếp tục thực hiện câu truy vấn INSERT vào bảng "thoikhoabieu"
            $insert = $conn->prepare("CALL sp_Addtkb(?, ?, ?, ?, ?, ?, ?, ?,?,?);");
            $insert->execute([$idhp, $idgv, $ngaybatdau, $ngayketthuc, $phonghoc, $iddk, $tiethoc, $ngayhoc, $thoigian,$tuanhoc]);

            // Kiểm tra kết quả thực hiện truy vấn
            if ($insert->rowCount() > 0) {
                echo "Thêm thời khóa biểu thành công!";
            } else {
                echo "Thêm thời khóa biểu thất bại.";
            }
        } else {
            echo "Không thể kết nối đến cơ sở dữ liệu.";
        }
    } catch (PDOException $e) {
        echo 'Lỗi khi thêm thời khóa biểu: ' . $e->getMessage();
    }
}
?>
