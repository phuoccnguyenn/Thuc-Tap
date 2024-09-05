<?php
session_start();

// Kiểm tra và đảm bảo có kết nối cơ sở dữ liệu hợp lệ
include '../config.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../admin_login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách học phần</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<style>
    table,
    th,
    td {
        border: 3px solid #e26a6a;
        padding: 8px;
        text-align: center;
        font-size: 15px;
        white-space: nowrap;
    }
    .btn{
        width: 500px;
        margin-left: 35%;
        margin-top: 50px;
    }
</style>
<body>
    <?php include 'admin_header.php'; ?>

    <?php

$sql_hocphan = "SELECT hp.idhp, hp.mahp, hp.tenhp, hp.soluongsv, hp.tinchi, COUNT(dk.iddk) AS student_count
               FROM hocphan hp
               LEFT JOIN dangkyhp dk ON hp.idhp = dk.idhp
               GROUP BY hp.idhp, hp.mahp, hp.tenhp, hp.soluongsv, hp.tinchi";
$result_hocphan = $conn->query($sql_hocphan);

if ($result_hocphan !== false) {
        $num_rows_hocphan = $result_hocphan->rowCount();
        if ($num_rows_hocphan > 0) {
            echo "<h1 style='text-align: center; padding: 20px 0px 20px; color: red; font-size: 30px;'>Danh sách học phần</h1>";
            echo "<table style='width: 100%; border-collapse: collapse; border: 10px; text-align: center; '>";
            echo "<tr style='font-size: 20px; color: blue;'>";
            echo "<th>ID</th>";
            echo "<th>Mã HP</th>";
            echo "<th>Tên HP</th>";
            echo "<th>Số tín chỉ</th>";
            echo "<th>Số lượng sinh viên</th>";
            echo "</tr>";

    while ($row_hocphan = $result_hocphan->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row_hocphan["idhp"] . "</td>";
        echo "<td>" . $row_hocphan["mahp"] . "</td>";
        echo "<td>" . $row_hocphan["tenhp"] . "</td>";
        echo "<td>" . $row_hocphan["tinchi"] . "</td>";

        // Tính toán số lượng sinh viên dưới dạng phân số
        $studentCount = $row_hocphan["student_count"];
        $totalStudents = $row_hocphan["soluongsv"];
        $fraction = "$studentCount / $totalStudents";

                echo "<td><a href='thanhtoanadmin.php?idhp=" . $row_hocphan["idhp"] . "'>" . $fraction . "</a></td>";

                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "Không có kết quả.";
        }
    } else {
        echo "Lỗi truy vấn học phần: " . $conn->errorInfo()[2];
    }
    ?>

    <?php
    if (isset($_GET["idhp"])) {
        $idhp = $_GET["idhp"];
    
        $sql_students = "SELECT dk.iddk, usv.mssv, usv.tensv, usv.email, usv.lop, kh.tenkhoa, hp.tien_chi, hp.tinchi, dk.idtrt, hk.tenhk
                FROM dangkyhp dk
                INNER JOIN usersv usv ON dk.idusv = usv.id
                INNER JOIN hocphan hp ON dk.idhp = hp.idhp
                INNER JOIN khoa kh ON usv.idkhoa = kh.idkhoa
                INNER JOIN hocky hk ON dk.idhocky = hk.idhk
                WHERE dk.idhp = $idhp";
$result_students = $conn->query($sql_students);

if ($result_students !== false) {
            $num_rows_students = $result_students->rowCount();
            if ($num_rows_students > 0) {
    echo "<h1 style='text-align: center; padding: 50px 0px 20px ; color: red; font-size: 30px; font-weight: bold;'>Danh sách sinh viên đã đăng ký</h1>";
    echo "<form method='post'>"; // Bắt đầu form
    echo "<table style='width: 100%; border-collapse: collapse; border: 10px; text-align: center;'>
            <tr style='font-size: 20px; color: blue;'>
                <th>MSSV</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Lớp</th>
                <th>Khoa</th>
                <th>Học kỳ</th> <!-- Thêm cột học kỳ -->
                <th>Trạng thái</th>
            </tr>";
    while ($row_student = $result_students->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td><a href='tongtien.php?mssv=" . $row_student["mssv"] . "&tenhk=" . urlencode($row_student["tenhk"]) . "'>" . $row_student["mssv"] . "</a></td>";
        echo "<td>" . $row_student["tensv"] . "</td>";
        echo "<td>" . $row_student["email"] . "</td>"; // Hiển thị email
        echo "<td>" . $row_student["lop"] . "</td>"; // Hiển thị lớp
        echo "<td>" . $row_student["tenkhoa"] . "</td>"; // Hiển thị tên khoa
        echo "<td>" . $row_student["tenhk"] . "</td>"; // Hiển thị tên học kỳ

        // Thẻ select trạng thái
        echo "<td>";
        echo "<select name='trangthai[" . $row_student["iddk"] . "]'>";
        echo "<option value='1'" . ($row_student["idtrt"] == 1 ? " selected" : "") . ">Đã đăng ký</option>";
        echo "<option value='2'" . ($row_student["idtrt"] == 2 ? " selected" : "") . ">Chưa đăng ký</option>";
        echo "</select>";
        echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "<input type='submit' class='btn' name='submit' value='Cập nhật trạng thái và thanh toán'>";
                echo "</form>"; // Kết thúc form
            } else {
                echo "Không có sinh viên đăng ký.";
            }
        } else {
            echo "Lỗi truy vấn sinh viên: " . $conn->errorInfo()[2];
        }
    }
    ?>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["trangthai"]) && is_array($_POST["trangthai"])) {
            foreach ($_POST["trangthai"] as $iddk => $idtrt) {
                $iddk = intval($iddk);
                $idtrt = intval($idtrt);

                $sql_update_trangthai = "UPDATE dangkyhp SET idtrt = $idtrt WHERE iddk = $iddk";
                $conn->query($sql_update_trangthai);
            }
        }

        if (isset($_POST["paid_amount"]) && is_array($_POST["paid_amount"])) {
            foreach ($_POST["paid_amount"] as $iddk => $paidAmount) {
                $iddk = intval($iddk);
                $paidAmount = intval($paidAmount);
                $sql_update_paid_amount = "UPDATE dangkyhp SET paid_amount = $paidAmount WHERE iddk = $iddk";
                $conn->query($sql_update_paid_amount);
            }
        }
    }

    $conn = null; // Đóng kết nối PDO
    ?>
<a href="admin_page.php" type="submit" class="btn" name="submit">Quay về</a>
<!-- <img src="../images/images.jpg" alt="Ảnh lỗi" class="full-screen-image"> -->

</body>

</html>
