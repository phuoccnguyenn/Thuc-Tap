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
        <!-- Phần hiển thị thông tin sinh viên -->
        <h1>Thông tin sinh viên</h1>
        <p>MSSV: <?php echo (isset($row_sv['mssv']) ? $row_sv['mssv'] : ''); ?></p>
        <p>Tên sinh viên: <?php echo (isset($row_sv['tensv']) ? $row_sv['tensv'] : ''); ?></p>
        <p>Lớp: <?php echo (isset($row_sv['lop']) ? $row_sv['lop'] : ''); ?></p>
        <!-- Hiển thị thông tin sinh viên khác tùy ý -->

        <!-- Phần hiển thị thời khóa biểu của sinh viên -->
        <h1>Thời khóa biểu của sinh viên</h1>
        <form method="post" style="max-width: 500rem;">
            <label for="search_input">Tìm kiếm sinh viên:</label>
            <input type="text" name="search_input" id="search_input" required>
            <input type="submit" name="search" value="Tìm kiếm" style="background-color: rgb(0, 166, 90);">
            <label for="hocky" style="padding-left: 350px;">Chọn học kỳ:</label>
            <select name="hocky" required>
                <?php
                while ($row_hocky = $stmt_hocky->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row_hocky['idhk'] . "'>" . (isset($row_hocky['tenhk']) ? $row_hocky['tenhk'] : '') . " - " . (isset($row_hocky['namhoc']) ? $row_hocky['namhoc'] : '') . "</option>";
                }
                ?>
            </select>
        </form>

        <?php
        // Xử lý khi người dùng tìm kiếm sinh viên và học kỳ
        if (isset($_POST['search'])) {
            $search_input = $_POST['search_input'];
            $hocky_id = isset($_POST['hocky']) ? $_POST['hocky'] : '';

            // Lấy thông tin sinh viên từ cơ sở dữ liệu dựa vào MSSV hoặc tên
            $stmt_search_sv = $conn->prepare("SELECT * FROM usersv WHERE (mssv LIKE :search_input OR tensv LIKE :search_input) AND id = :sinhvien_id");
            $stmt_search_sv->bindValue(':search_input', '%' . $search_input . '%');
            $stmt_search_sv->bindParam(':sinhvien_id', $sinhvien_id);
            $stmt_search_sv->execute();

            // Hiển thị kết quả tìm kiếm
            if ($stmt_search_sv->rowCount() > 0) {
                // Lấy thông tin học kỳ được chọn từ cơ sở dữ liệu
                if (!empty($hocky_id)) {
                    $stmt_selected_hocky = $conn->prepare("SELECT * FROM hocky WHERE idhk = :hocky_id");
                    $stmt_selected_hocky->bindParam(':hocky_id', $hocky_id);
                    $stmt_selected_hocky->execute();
                    $row_selected_hocky = $stmt_selected_hocky->fetch(PDO::FETCH_ASSOC);

                    // Lấy thông tin đăng ký học phần của sinh viên trong học kỳ đã chọn
                    $stmt_dk = $conn->prepare("SELECT * FROM dangkyhp WHERE idusv = :sinhvien_id AND idhocky = :hocky_id");
                    $stmt_dk->bindParam(':sinhvien_id', $sinhvien_id);
                    $stmt_dk->bindParam(':hocky_id', $hocky_id);
                    $stmt_dk->execute();

                    if ($stmt_dk->rowCount() > 0) {
                        echo "<h2>Thời khóa biểu</h2>";
                        echo "<table style='width: 100%;'>";
                        echo "<tr><th>Ngày</th><th>Lớp học phần</th></tr>";

                        $ngay_trong_tuan = array("Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật");

                        while ($row_dk = $stmt_dk->fetch(PDO::FETCH_ASSOC)) {
                            $iddk = $row_dk['iddk'];

                            // Lấy thông tin thời khóa biểu dựa vào id đăng ký học phần
                            $stmt_tkb = $conn->prepare("SELECT * FROM thoikhoabieu WHERE iddk = :iddk");
                            $stmt_tkb->bindParam(':iddk', $iddk);
                            $stmt_tkb->execute();

                            if ($stmt_tkb->rowCount() > 0) {
                                while ($row_tkb = $stmt_tkb->fetch(PDO::FETCH_ASSOC)) {
                                    $idhp = $row_tkb['idhp'];
                                    $idgv = $row_tkb['idgv'];
                                    $phonghoc = $row_tkb['phonghoc'];
                                    $thoigian = $row_tkb['thoigian'];
                                    $tiethoc = $row_tkb['tiethoc'];

                                    // Chuyển đổi giá trị của $thoigian thành số nguyên
                                    $thoigian = intval($thoigian);

                                    // Lấy thông tin học phần từ bảng hocphan
                                    $stmt_hp = $conn->prepare("SELECT * FROM hocphan WHERE idhp = :idhp");
                                    $stmt_hp->bindParam(':idhp', $idhp);
                                    $stmt_hp->execute();
                                    $row_hp = $stmt_hp->fetch(PDO::FETCH_ASSOC);

                                    echo "<tr><td>" . $ngay_trong_tuan[$thoigian-2] . "</td><td>" .  "Mã học phần: " .(isset($row_hp['mahp']) ? $row_hp['mahp'] : '') . "</br>" .  "Tên học phần: " . (isset($row_hp['tenhp']) ? $row_hp['tenhp'] : '') . " </br> ".  "Tín chỉ: " .  (isset($row_hp['tinchi']) ? $row_hp['tinchi'] : '') . " </br> ".  "Phòng học: "  . $phonghoc . "</br> ".  "Tiết học: "  . $tiethoc . "</td></tr>";
                                }
                            }
                        }
                        echo "</table>";
                        echo "</pre>";
                    } else {
                        echo "<p>Sinh viên không đăng ký học phần trong học kỳ này.</p>";
                    }
                }
            } else {
                echo "<p>Không tìm thấy sinh viên với từ khóa: $search_input</p>";
            }
        }
        ?>
    </section>
<script src="../js/script.js"></script>

</body>
</html>