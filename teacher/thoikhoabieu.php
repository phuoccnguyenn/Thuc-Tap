<?php
session_start();
@include '../config.php'; // Kết nối đến CSDL

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['giangvien_id'])) {
    header('Location: ../student_teacher_login.php');
    exit();
}

// Kết nối đến cơ sở dữ liệu
$conn = new PDO($db_name, $username, $password);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại");
}

// Lấy thông tin giảng viên từ cơ sở dữ liệu
$giangvien_id = $_SESSION['giangvien_id']; // Đảm bảo có biến này trong session hoặc thay thế bằng cách lấy từ biến khác
$stmt_gv = $conn->prepare("SELECT * FROM usergv WHERE id = :giangvien_id");
$stmt_gv->bindParam(':giangvien_id', $giangvien_id);
$stmt_gv->execute();
$row_gv = $stmt_gv->fetch(PDO::FETCH_ASSOC);
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
    <title>Thông tin thời khóa biểu của giảng viên</title>
</head>
<style>
    table,
    th,
    td {
        border: 3px solid #e26a6a;
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
    <!-- Phần hiển thị thông tin giảng viên -->
    <h1>Thông tin giảng viên</h1>
    <p>Mã giảng viên: <?php echo (isset($row_gv['id']) ? $row_gv['id'] : ''); ?></p>
    <p>Tên giảng viên: <?php echo (isset($row_gv['tengv']) ? $row_gv['tengv'] : ''); ?></p>

        <!-- Phần hiển thị thời khóa biểu của giảng viên -->
        <h1>Thời khóa biểu của giảng viên</h1>
        <form method="post" style="max-width: 500rem;">
            <label for="search_input">Tìm kiếm giảng viên:</label>
            <input type="text" name="search_input" id="search_input" required>
            <input type="submit" name="search" value="Tìm kiếm" style="background-color: rgb(0, 166, 90);">
            <label for="hocky" style="padding-left: 350px;">Chọn học kỳ:</label>
            <select name="hocky" required>
                <?php
                $stmt_hocky = $conn->prepare("SELECT * FROM hocky");
                $stmt_hocky->execute();

                while ($row_hocky = $stmt_hocky->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row_hocky['idhk'] . "'>" . (isset($row_hocky['tenhk']) ? $row_hocky['tenhk'] : '') . " - " . (isset($row_hocky['namhoc']) ? $row_hocky['namhoc'] : '') . "</option>";
                }
                ?>
            </select>
        </form>


        <?php
            if ($stmt_gv->rowCount() > 0) {
             
                $row_gv = $stmt_gv->fetch(PDO::FETCH_ASSOC);
               
    
                // Retrieve thời khóa biểu information based on giảng viên and selected semester
                $stmt_tkb = $conn->prepare("SELECT tkb.*, hp.tenhp, hp.mahp, hp.tinchi FROM thoikhoabieu tkb 
                                            INNER JOIN hocphan hp ON tkb.idhp = hp.idhp
                                            WHERE tkb.idgv = :giangvien_id ");
                $stmt_tkb->bindParam(':giangvien_id', $giangvien_id);
               
                $stmt_tkb->execute();
    
                if ($stmt_tkb->rowCount() > 0) {
                    echo "<h2>Thời khóa biểu</h2>";
                    echo "<table style='width: 100%;'>";
                    echo "<tr><th>Ngày bắt đầu</th><th>Ngày kết thúc</th><th>Phòng học</th><th>Tiết học</th><th>Thời gian</th><th>Mã học phần</th><th>Tên học phần</th><th>Tín chỉ</th></tr>";
    
                    while ($row_tkb = $stmt_tkb->fetch(PDO::FETCH_ASSOC)) {
                        $ngay_trong_tuan = array("Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật");
                        $ngayhoc = strtotime($row_tkb['ngayhoc']);
                        $thu = date("N", $ngayhoc) - 1;
    
                        echo "<tr><td>"
                            . $row_tkb['ngaybatdau'] . "</td><td>"
                            . $row_tkb['ngayketthuc'] . "</td><td>"
                            . $row_tkb['phonghoc'] . "</td><td>"
                            . $row_tkb['tiethoc'] . "</td><td>"
                            . $ngay_trong_tuan[$thu] . "</td><td>"
                            . $row_tkb['mahp'] . "</td><td>"
                            . $row_tkb['tenhp'] . "</td><td>"
                            . $row_tkb['tinchi'] . "</td></tr>";
                    }
                    echo "</table>";
                } 
            } 
        
        ?>


        <?php
        

    if (isset($_POST['search'])) {
        $search_input = $_POST['search_input'];
        $hocky_id = $_POST['hocky'];

        // Retrieve giảng viên information
        $stmt_gv = $conn->prepare("SELECT * FROM usergv WHERE cccd = :search_input");
        $stmt_gv->bindParam(':search_input', $search_input);
        $stmt_gv->execute();

        if ($stmt_gv->rowCount() > 0) {
            $row_gv = $stmt_gv->fetch(PDO::FETCH_ASSOC);
            $giangvien_id = $row_gv['id'];

            // Retrieve thời khóa biểu information based on giảng viên and selected semester
            $stmt_tkb = $conn->prepare("SELECT tkb.*, hp.tenhp, hp.mahp, hp.tinchi FROM thoikhoabieu tkb 
                                        INNER JOIN hocphan hp ON tkb.idhp = hp.idhp
                                        WHERE tkb.idgv = :giangvien_id AND hp.idhk = :hocky_id");
            $stmt_tkb->bindParam(':giangvien_id', $giangvien_id);
            $stmt_tkb->bindParam(':hocky_id', $hocky_id);
            $stmt_tkb->execute();

            if ($stmt_tkb->rowCount() > 0) {
                echo "<h2>Thời khóa biểu</h2>";
                echo "<table style='width: 100%;'>";
                echo "<tr><th>Ngày bắt đầu</th><th>Ngày kết thúc</th><th>Phòng học</th><th>Tiết học</th><th>Thời gian</th><th>Mã học phần</th><th>Tên học phần</th><th>Tín chỉ</th></tr>";

                while ($row_tkb = $stmt_tkb->fetch(PDO::FETCH_ASSOC)) {
                    $ngay_trong_tuan = array("Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật");
                    $ngayhoc = strtotime($row_tkb['ngayhoc']);
                    $thu = date("N", $ngayhoc) - 1;

                    echo "<tr><td>"
                        . $row_tkb['ngaybatdau'] . "</td><td>"
                        . $row_tkb['ngayketthuc'] . "</td><td>"
                        . $row_tkb['phonghoc'] . "</td><td>"
                        . $row_tkb['tiethoc'] . "</td><td>"
                        . $ngay_trong_tuan[$thu] . "</td><td>"
                        . $row_tkb['mahp'] . "</td><td>"
                        . $row_tkb['tenhp'] . "</td><td>"
                        . $row_tkb['tinchi'] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "Không có thời khóa biểu nào cho giảng viên này trong học kỳ đã chọn.";
            }
        } else {
            echo "<p>Không tìm thấy giảng viên với CCCD: $search_input</p>";
        }
    }
        ?>
    </section>
</body>
</html>
