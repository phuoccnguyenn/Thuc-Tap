<?php
session_start();
include '../config.php'; // Kết nối đến CSDL
$sinhvien_id = $_SESSION['sinhvien_id'];

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['sinhvien_id'])) {
    header('Location: ../index.php');
    exit();
}

// Kiểm tra xem có yêu cầu xóa không
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $iddk = $_GET['delete'];

    // Sử dụng Prepared Statements để tránh SQL Injection
    $stmt = $conn->prepare("DELETE FROM dangkyhp WHERE iddk = :iddk");
    $stmt->bindParam(':iddk', $iddk);

    if ($stmt->execute()) {
        // Xóa thành công, chuyển hướng người dùng đến trang student_dkhp.php
        header('Location: student_dkhp.php');
        exit();
    } else {
        echo "Lỗi: " . $stmt->errorInfo()[2];
    }
}
// Kiểm tra xem form đã được gửi đi hay chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sử dụng Prepared Statements để tránh SQL Injection
    $idhp = $_POST['idhp'];
    $idusv = $_SESSION['sinhvien_id']; // Lấy ID Sinh viên từ session, vì người dùng đã đăng nhập
    $idhocky = $_POST['idhocky']; // Lấy ID Học kỳ từ form
    $idng = $_POST['idng']; // Lấy ID Ngân hàng từ form
    $idtrt = 2; // Giá trị trạng thái mặc định

    // Chuẩn bị câu truy vấn với Prepared Statements của PDO
    $stmt = $conn->prepare("INSERT INTO dangkyhp (iddk, idhp, idusv, idhocky, idng, idtrt) VALUES (NULL, :idhp, :idusv, :idhocky, :idng, :idtrt)");
    
    // Gán giá trị cho các tham số
    $stmt->bindParam(':idhp', $idhp);
    $stmt->bindParam(':idusv', $idusv);
    $stmt->bindParam(':idhocky', $idhocky);
    $stmt->bindParam(':idng', $idng);
    $stmt->bindParam(':idtrt', $idtrt);

    

    if ($stmt->execute()) {
        // Đăng ký học phần thành công, chuyển hướng người dùng đến trang student_dkhp.php
        header('Location: student_dkhp.php');
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
    <title>Form Đăng Ký Học Phần</title>
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
        <h1 class="title" style="color: lightyellow;text-shadow: 2px 2px 4px red;">Form Đăng Ký Học Phần</h1>
        <form action="" method="POST" enctype="multipart/form-data" style="background: var(--pink);">

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

        <label for="idng">Ngân hàng:</label>
        <select name="idng" class="box" required>
            <option value="" selected disabled>Chọn ngân hàng</option>
            <?php
            // Lấy danh sách ngân hàng từ CSDL để đổ vào trường chọn select
            $query_nganhang = "SELECT idng, tenng FROM nganhang";
            $result_nganhang = $conn->query($query_nganhang);
            foreach ($result_nganhang as $row) {
                echo '<option value="' . $row['idng'] . '">' . $row['tenng'] . '</option>';
            }
            ?>
        </select><br>

      <br>


            <div class="flex-btn">
                <input type="submit" class="btn" value="Đăng ký">
            </div>
        </form>
    </section>


    <section class="show-dkhp">
        <h1 class="title" style="color: lightyellow; text-shadow: 2px 2px 4px red;">Danh sách học phần đã đăng ký</h1>

        <div style="background: var(--pink);">
            <table style="width: 100%; border-collapse: collapse; border: 10px; text-align: center;">
                <tr style="font-size: 20px; color: blue;">
                    <th>Tên học phần</th>
                    <th>Học kỳ</th>
                    <th>Tên Ngân Hàng</th>
                    <th>Trạng Thái</th>
                    <th colspan="2">Chỉnh sửa</th>
                </tr>

                <?php
                $show_dkhp = $conn->prepare("SELECT dk.*, hp.tenhp, hk.tenhk, ng.tenng, tt.trangthai
                                            FROM dangkyhp dk
                                            INNER JOIN hocphan hp ON dk.idhp = hp.idhp
                                            INNER JOIN hocky hk ON dk.idhocky = hk.idhk
                                            INNER JOIN nganhang ng ON dk.idng = ng.idng
                                            INNER JOIN trangthai tt ON dk.idtrt = tt.idtrt
                                            WHERE dk.idusv = :idusv
                                            ORDER BY dk.iddk DESC");
                $show_dkhp->bindParam(':idusv', $_SESSION['sinhvien_id']);
                $show_dkhp->execute();

                if ($show_dkhp->rowCount() > 0) {
                    while ($fetch_dkhp = $show_dkhp->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <tr style="font-size: 20px;">
                            <td><?= $fetch_dkhp['tenhp']; ?></td>
                            <td><?= $fetch_dkhp['tenhk']; ?></td>
                            <td><?= $fetch_dkhp['tenng']; ?></td>
                            <td><?= $fetch_dkhp['trangthai']; ?></td>
                            <td><a href="student_update_dkhp.php?update=<?= $fetch_dkhp['iddk']; ?>" class="option-btn">Cập nhật</a></td>
                            <td>
                                <a href="student_dkhp.php?delete=<?= $fetch_dkhp['iddk']; ?>" class="delete-btn" onclick="return confirm('Xóa đăng ký học phần này?');">Xóa</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="6">Danh sách đăng ký học phần trống!</td></tr>';
                }
                ?>
            </table>
        </div>
    </section>
<script src="../js/script.js"></script>

</body>
</html>
