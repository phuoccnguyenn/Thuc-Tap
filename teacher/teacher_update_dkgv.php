<?php
session_start();
@include '../config.php'; // Kết nối đến CSDL

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['giangvien_id'])) {
    header('Location: ../index.php');
    exit();
}

if (isset($_POST['update_dangkygv'])) {
    // Lấy dữ liệu từ form
    $iddkgv = $_POST['iddkgv'];
    $idhp = $_POST['idhp'];
    $idhocky = $_POST['idhocky'];
    $idugv = $_POST['idugv'];
    $idtrt = 2;
    $ngaybatdau = $_POST['ngaybatdau'];
    $ngayketthuc = $_POST['ngayketthuc'];

    // Thực hiện cập nhật dữ liệu vào CSDL, sử dụng Prepared Statements để tránh SQL Injection
    $stmt = $conn->prepare("UPDATE dangkygv 
                            SET idhp = :idhp, idhocky = :idhocky, idugv = :idugv, idtrt = :idtrt, ngaybatdau = :ngaybatdau, ngayketthuc = :ngayketthuc 
                            WHERE iddkgv = :iddkgv");

    $stmt->bindParam(':idhp', $idhp);
    $stmt->bindParam(':idhocky', $idhocky);
    $stmt->bindParam(':idugv', $idugv);
    $stmt->bindParam(':idtrt', $idtrt);
    $stmt->bindParam(':ngaybatdau', $ngaybatdau);
    $stmt->bindParam(':ngayketthuc', $ngayketthuc);
    $stmt->bindParam(':iddkgv', $iddkgv);

    if ($stmt->execute()) {
        // Cập nhật thành công, chuyển hướng về trang "teacher_dkgv.php"
        header('Location: teacher_dkgv.php');
        exit();
    } else {
        echo "Lỗi: " . $stmt->errorInfo()[2];
    }
}
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
    <title>Form Cập nhật Học Phần</title>
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
</style>
<body>

<?php include 'header.php'; ?>

<section class="update-gv_gv">

    <h1 class="title">Cập nhật đăng ký học phần của Giảng viên</h1>
    <?php
    if (!empty($message)) {
        foreach ($message as $msg) {
            echo '<p class="message">' . $msg . '</p>';
        }
    }

    if (isset($_GET['update']) && is_numeric($_GET['update'])) {
        $update_iddkgv = $_GET['update'];
        $select_dangkygv = $conn->prepare("SELECT * FROM dangkygv WHERE iddkgv = ?");
        $select_dangkygv->execute([$update_iddkgv]);
        if ($select_dangkygv->rowCount() > 0) {
            $fetch_dangkygv = $select_dangkygv->fetch(PDO::FETCH_ASSOC);
    ?>
    <form action="teacher_update_dkgv.php" method="post" enctype="multipart/form-data"  style="background: var(--pink);">
        <input type="hidden" name="iddkgv" value="<?php echo $fetch_dangkygv['iddkgv']; ?>">
        <label for="idhp" style="font-size: 20px; color: #134220;">Học phần:</label>
        <select name="idhp" class="box" required style="text-align: center;font-size: 20px; border: 2px solid #000; padding: 5px;">
            <option value="" selected disabled>Chọn học phần</option>
            <?php
            // Lấy danh sách học phần từ CSDL để đổ vào trường chọn select
            $query_hocphan = "SELECT idhp, tenhp FROM hocphan";
            $result_hocphan = $conn->query($query_hocphan);
            foreach ($result_hocphan as $row) {
                echo '<option value="' . $row['idhp'] . '"';
                if ($row['idhp'] == $fetch_dangkygv['idhp']) {
                    echo ' selected';
                }
                echo '>' . $row['tenhp'] . '</option>';
            }
            ?>
        </select><br>

        <label for="idhocky" style="font-size: 20px; color: #134220;">Học kỳ:</label>
        <select name="idhocky" class="box" required style="text-align: center;font-size: 20px;border: 2px solid #000; padding: 5px;">
            <option value="" selected disabled>Chọn học kỳ</option>
            <?php
            // Lấy danh sách học kỳ từ CSDL để đổ vào trường chọn select
            $query_hocky = "SELECT idhk, tenhk FROM hocky";
            $result_hocky = $conn->query($query_hocky);
            foreach ($result_hocky as $row) {
                echo '<option value="' . $row['idhk'] . '"';
                if ($row['idhk'] == $fetch_dangkygv['idhocky']) {
                    echo ' selected';
                }
                echo '>' . $row['tenhk'] . '</option>';
            }
            ?>
        </select><br>

        <label for="idugv" style="font-size: 20px; color: #134220;">Mã giảng viên:</label>
        <input type="text" name="idugv" value="<?php echo $fetch_dangkygv['idugv']; ?>" required style="text-align: center;font-size: 20px;border: 2px solid #000; padding: 5px;"><br>

    

        <label for="ngaybatdau" style="font-size: 20px; color: #134220;">Ngày bắt đầu:</label>
        <input type="date" name="ngaybatdau" value="<?php echo $fetch_dangkygv['ngaybatdau']; ?>" required style="text-align: center;font-size: 20px;border: 2px solid #000; padding: 5px;"><br>

        <label for="ngayketthuc" style="font-size: 20px; color: #134220;">Ngày kết thúc:</label>
        <input type="date" name="ngayketthuc" value="<?php echo $fetch_dangkygv['ngayketthuc']; ?>" required style="text-align: center;font-size: 20px;border: 2px solid #000; padding: 5px;"><br>

        <div class="flex-btn">
            <input type="submit" class="btn" value="Cập nhật" name="update_dangkygv">
        </div>
    </form>
    <?php
        } else {
            echo '<p class="empty">Không có đăng ký học phần nào được tìm thấy!</p>';
        }
    }
    ?>

</section>


<script src="../js/script.js"></script>

</body>
</html>
