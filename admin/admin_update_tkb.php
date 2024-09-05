<?php
// admin_update_tkb.php
@include '../config.php';
session_start();

$stmt = $conn->prepare("SELECT id, tengv FROM usergv");
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT idhp, tenhp FROM hocphan");
$stmt->execute();
$hocphanes = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Tương tự như trong config.php, kết nối đến cơ sở dữ liệu và kiểm tra phiên làm việc

// Lấy ID thời khóa biểu cần cập nhật từ tham số URL
$update_idtkb = $_GET['update'];

@include '../sql/updatetkb.php';


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật thời khóa biểu</title>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<style>
    table,
    th,
    td {
        border: 1px solid #ccc;
    }
</style>
<body>
    <?php include 'admin_header.php'; ?>

    <section class="update-admin">
        <h1 class="title">Cập nhật thời khóa biểu</h1>

        <?php if (!empty($message)) { ?>
            <div class="message">
                <?php foreach ($message as $msg) {
                    echo "<p>$msg</p>";
                } ?>
            </div>
        <?php } ?>

        <?php if (isset($fetch_tkb)) { ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="flex">
                <div class="inputBox">
                    <label for="phonghoc" style="font-size: 20px; color: #134220;">Phòng học: &nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input type="text" name="phonghoc" class="box" placeholder="Nhập phòng học" required value="<?= $fetch_tkb['phonghoc']; ?>">
                    <label for="ngaybatdau" style="font-size: 20px; color: #134220;">Ngày bắt đầu: &nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input type="text" name="ngaybatdau" class="box" placeholder="Ngày bắt đầu (YYYY-MM-DD)" required value="<?= $fetch_tkb['ngaybatdau']; ?>">
                    <label for="ngayketthuc" style="font-size: 20px; color: #134220;">Ngày kết thúc: &nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input type="text" name="ngayketthuc" class="box" placeholder="Ngày kết thúc (YYYY-MM-DD)" required value="<?= $fetch_tkb['ngayketthuc']; ?>">
                </div>    
                <div class="inputBox">
                    <label for="idhp" style="font-size: 20px; color: #134220;">Mã học phần: &nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <select name="idhp" class="box" required>
                        <option value="" selected disabled>Chọn học phần</option>
                        <?php foreach ($hocphanes as $hocphan) : ?>
                            <option value="<?php echo $hocphan['idhp']; ?>" <?= $fetch_tkb['idhp'] == $hocphan['idhp'] ? 'selected' : ''; ?>><?php echo $hocphan['tenhp']; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="idgv" style="font-size: 20px; color: #134220;"> Chọn giảng viên: &nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <select name="idgv" class="box" required>
                        <option value="" selected disabled>Chọn giảng viên</option>
                        <?php foreach ($teachers as $teacher) : ?>
                            <option value="<?php echo $teacher['id']; ?>" <?= $fetch_tkb['idgv'] == $teacher['id'] ? 'selected' : ''; ?>><?php echo $teacher['tengv']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="flex-btn">
                <input type="submit" class="btn" value="Cập nhật thời khóa biểu" name="update_tkb"></div>
            <div class="flex-btn">
                <a href="admin_tkb.php" class="option-btn">Trở lại</a>
            </div>
        </form>
        <?php } else { ?>
            <p>Không tìm thấy thông tin thời khóa biểu cần cập nhật!</p>
        <?php } ?>
    </section>
<script>
  $(document).ready(function() {
    // Chọn phần tử input có name="ngaybatdau" và name="ngayketthuc", sau đó kích hoạt date picker cho cả hai trường
    $("input[name='ngaybatdau'], input[name='ngayketthuc']").datepicker({
      dateFormat: "yy-mm-dd", // Định dạng ngày tháng năm sau khi chọn
      changeMonth: true,      // Hiển thị dropdown để chọn tháng
      changeYear: true        // Hiển thị dropdown để chọn năm
    });
  });
</script>
</body>
</html>
