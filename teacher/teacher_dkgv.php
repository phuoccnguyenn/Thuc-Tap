<?php
session_start();
@include '../config.php'; // Kết nối đến CSDL

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['giangvien_id'])) {
    header('Location: ../index.php');
    exit();
}

// Kiểm tra xem có yêu cầu xóa không
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $iddkgv = $_GET['delete'];

    // Sử dụng Prepared Statements để tránh SQL Injection
    $stmt = $conn->prepare("DELETE FROM dangkygv WHERE iddkgv = :iddkgv");
    $stmt->bindParam(':iddkgv', $iddkgv);

    if ($stmt->execute()) {
        // Xóa thành công, chuyển hướng người dùng đến trang teacher_dkgv.php
        header('Location: teacher_dkgv.php');
        exit();
    } else {
        echo "Lỗi: " . $stmt->errorInfo()[2];
    }
}

// Kiểm tra xem form đã được gửi đi hay chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sử dụng Prepared Statements để tránh SQL Injection
    $idhp = $_POST['idhp'];
    $idhocky = $_POST['idhocky']; // Lấy ID Học kỳ từ form
    $idugv = $_SESSION['giangvien_id']; // Sử dụng ID của giảng viên lưu trong biến phiên
    $idtrt = 2; // Lấy ID Trạng thái từ form
    $ngaybatdau = $_POST['ngaybatdau'];
    $ngayketthuc = $_POST['ngayketthuc'];

    // Chuẩn bị câu truy vấn với Prepared Statements của PDO
    $stmt = $conn->prepare("INSERT INTO dangkygv (idhp, idhocky, idugv, idtrt, ngaybatdau, ngayketthuc) VALUES (:idhp, :idhocky, :idugv, :idtrt, :ngaybatdau, :ngayketthuc)");

    // Gán giá trị cho các tham số
    $stmt->bindParam(':idhp', $idhp);
    $stmt->bindParam(':idhocky', $idhocky);
    $stmt->bindParam(':idugv', $idugv);
    $stmt->bindParam(':idtrt', $idtrt);
    $stmt->bindParam(':ngaybatdau', $ngaybatdau);
    $stmt->bindParam(':ngayketthuc', $ngayketthuc);

    if ($stmt->execute()) {
        // Đăng ký giảng viên thành công, chuyển hướng người dùng đến trang teacher_dkgv.php
        header('Location: teacher_dkgv.php');
        exit();
    } else {
        echo "Lỗi: " . $stmt->errorInfo()[2];
    }

    // Đóng kết nối
    $stmt = null;
    $conn = null;
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
    <title>Đăng Ký Học phần Giảng Viên</title>
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
        <h1 class="title" style="color: lightyellow;text-shadow: 2px 2px 4px red;">Đăng Ký Học phần Giảng Viên</h1>
        <form action="" method="POST" enctype="multipart/form-data" style="background: var(--pink);">

            <!-- Các trường còn lại giữ nguyên -->
            <label for="idhp">Học phần:</label>
            <select name="idhp" class="box" required>
                <option value="" selected disabled>Chọn học phần</option>
                <?php
                // Lấy danh sách học phần từ CSDL để đổ vào trường chọn select
                $query_hocphan = "SELECT idhp, tenhp FROM hocphan";
                $result_hocphan = $conn->query($query_hocphan);
                foreach ($result_hocphan as $row) {
                    echo '<option value="' . $row['idhp'] . '">' . $row['tenhp'] . '</option>';
                }
                ?>
            </select><br>

            <label for="idhocky">Học kỳ:</label>
            <select name="idhocky" class="box" required>
                <option value="" selected disabled>Chọn học kỳ</option>
                <?php
                // Lấy danh sách học kỳ từ CSDL để đổ vào trường chọn select
                $query_hocky = "SELECT idhk, tenhk FROM hocky";
                $result_hocky = $conn->query($query_hocky);
                foreach ($result_hocky as $row) {
                    echo '<option value="' . $row['idhk'] . '">' . $row['tenhk'] . '</option>';
                }
                ?>
            </select><br>

            <!-- Giờ không cần chọn tên giảng viên vì đã tự động lấy từ session -->
            <!-- ... -->

         

            <!-- Thêm các trường cho ngày bắt đầu và ngày kết thúc -->
            <label for="ngaybatdau" >Ngày bắt đầu:</label>
            <input type="date" name="ngaybatdau"  style="background: var(--pink);" required><br>

            <label for="ngayketthuc">Ngày kết thúc:</label>
            <input type="date" name="ngayketthuc"  style="background: var(--pink);" required><br>

            <div class="flex-btn">
                <input type="submit" class="btn" value="Đăng ký">
            </div>
        </form>
    </section>

 <section class="show-dkgv">
    <h1 class="title" style="color: lightyellow; text-shadow: 2px 2px 4px red;">Danh sách thời khóa biểu của giảng viên</h1>

    <div style="background: var(--pink);">
        <table style="width: 100%; border-collapse: collapse; border: 10px; text-align: center;">
            <tr style="font-size: 20px; color: blue;">
                <th>Tên học phần</th>
                <th>Học kỳ</th>
                <th>Tên giảng viên</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th colspan="2">Chỉnh sửa</th>
            </tr>

            <?php
            $show_tkb = $conn->prepare("SELECT tkb.*, hp.tenhp, hk.tenhk, gv.tengv 
                                       FROM dangkygv tkb
                                       INNER JOIN hocphan hp ON tkb.idhp = hp.idhp
                                       INNER JOIN hocky hk ON tkb.idhocky = hk.idhk
                                       INNER JOIN usergv gv ON tkb.idugv = gv.id
                                       WHERE tkb.idugv = :idugv
                                       ORDER BY tkb.iddkgv DESC");

            $idugv = $_SESSION['giangvien_id'];
            $show_tkb->bindParam(':idugv', $idugv);

            $show_tkb->execute();

            if ($show_tkb->rowCount() > 0) {
                while ($fetch_tkb = $show_tkb->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <tr style="font-size: 20px;">
                        <td><?= $fetch_tkb['tenhp']; ?></td>
                        <td><?= $fetch_tkb['tenhk']; ?></td>
                        <td><?= $fetch_tkb['tengv']; ?></td>
                        <td><?= $fetch_tkb['ngaybatdau']; ?></td>
                        <td><?= $fetch_tkb['ngayketthuc']; ?></td>
                        <td><a href="teacher_update_dkgv.php?update=<?= $fetch_tkb['iddkgv']; ?>" class="option-btn">Cập nhật</a></td>
                        <td>
                            <a href="teacher_dkgv.php?delete=<?= $fetch_tkb['iddkgv']; ?>" class="delete-btn" onclick="return confirm('Xóa học phần này của giảng viên?');">Xóa</a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="7">Danh sách thời khóa biểu trống!</td></tr>';
            }
            ?>
        </table>
    </div>
</section>
</body>
</html>
