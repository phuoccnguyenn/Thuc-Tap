<?php
session_start();

@include '../config.php';

$sinhvien_id = $_SESSION['sinhvien_id'];

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($sinhvien_id)) {
    header('Location: ../index.php');
    exit();
}

// Kết nối đến cơ sở dữ liệu
$conn = new PDO($db_name, $username, $password);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại");
}

// Lấy thông tin sinh viên từ cơ sở dữ liệu
$stmt_sv = $conn->prepare("SELECT * FROM usersv WHERE id = :sinhvien_id");
$stmt_sv->bindParam(':sinhvien_id', $sinhvien_id);
$stmt_sv->execute();
$row_sv = $stmt_sv->fetch(PDO::FETCH_ASSOC);

// Lấy thông tin học kỳ từ cơ sở dữ liệu
$stmt_hocky = $conn->prepare("SELECT * FROM hocky");
$stmt_hocky->execute();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Thông tin thời khóa biểu của sinh viên</title>
</head>
<style>
    table,
    th,
    td {
        border: 3px solid #785ef7;
        padding: 8px;
        text-align: center;
        white-space: nowrap;
    }

    /* Định dạng bảng rộng 100% */
    table.full-width {
        width: 100%;
    }
    input[type="text"] {
    border: 0.3px solid black; /* Màu viền đen */
    padding: 5px; /* Khoảng cách giữa nội dung và viền */
}
</style>
<body>
    <?php include 'header.php'; ?>

    <section class="update-gv_gv">
        <h1>Học phí của sinh viên</h1>
        <form method="post" style="max-width: 500rem;">
            <label for="mssv">Tìm kiếm sinh viên:</label>
            <input type="text" name="mssv" id="mssv" required>
            <input type="submit" name="search" value="Tìm kiếm" style="background-color: rgb(0, 166, 90);">
            <label for="hocky" style="padding-left: 350px;">Học kỳ:</label>
            <select name="hocky" required>
                <?php
                while ($row_hocky = $stmt_hocky->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row_hocky['idhk'] . "'>" . $row_hocky['tenhk'] . " - " . $row_hocky['namhoc'] . "</option>";
                }
                ?>
            </select>
        </form>

        <?php
        if (isset($_POST['search'])) {
            $mssv = $_POST['mssv'];
            $hocky_id = $_POST['hocky'];

            // Lấy thông tin học phí từ cơ sở dữ liệu
            $stmt_hp = $conn->prepare("SELECT hp.mahp, hp.tenhp, hp.tinchi,hp.tien_chi, hp.sotien,  ng.tenng
                                        FROM dangkyhp AS dk
                                        INNER JOIN hocphan AS hp ON dk.idhp = hp.idhp
                                        LEFT JOIN nganhang AS ng ON dk.idng = ng.idng
                                        WHERE dk.idusv = :sinhvien_id AND dk.idhocky = :hocky_id");
            $stmt_hp->bindParam(':sinhvien_id', $sinhvien_id);
            $stmt_hp->bindParam(':hocky_id', $hocky_id);
            $stmt_hp->execute();

            if ($stmt_hp->rowCount() > 0) {
                echo "<table style='width: 100%;'>";
                echo "<tr><th>STT</th><th>Mã học phần</th><th>Tên học phần</th><th>Số tín chỉ</th><th>Đơn giá</th><th>Thành tiền (VNĐ)</th><th>Ghi chú</th></tr>";
                $stt = 1;
                $totalHocPhi = 0;
            
                while ($row_hp = $stmt_hp->fetch(PDO::FETCH_ASSOC)) {
                    $don_gia = $row_hp['tien_chi'];
                    $thanh_tien = $row_hp['sotien'];
                    $ghi_chu = $row_hp['tenng'];
                    $totalHocPhi += $thanh_tien;
            
                    echo "<tr><td>$stt</td><td>{$row_hp['mahp']}</td><td>{$row_hp['tenhp']}</td><td>{$row_hp['tinchi']}</td><td>{$don_gia}</td><td>{$thanh_tien}</td><td>{$ghi_chu}</td></tr>";
                    $stt++;
                }
            
                echo "<tr><td colspan='5' style='text-align: right;'>Tổng học phí:</td><td>{$totalHocPhi} VNĐ</td><td></td></tr>";
                echo "</table>";
            
                // Hiển thị tổng học phí bên ngoài bảng
               
            } else {
                echo "<p>Không có học phí nào cho sinh viên trong học kỳ này.</p>";
            }
        }
        ?>
    </section>
</body>
</html>
