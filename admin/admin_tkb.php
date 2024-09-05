<?php
// config.php
@include '../config.php';

// Bắt đầu phiên làm việc
session_start();

$stmt = $conn->prepare("SELECT id, tengv FROM usergv");
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT idhp, tenhp FROM hocphan");
$stmt->execute();
$hocphanes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT iddk FROM dangkyhp");
$stmt->execute();
$dangkyhps = $stmt->fetchAll(PDO::FETCH_ASSOC);

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../admin_login.php');
    exit; // Kết thúc thực thi mã khi chuyển hướng đến trang đăng nhập
}
@include '../sql/addtkb.php';

if (isset($_GET['delete'])) {
    $delete_idtkb = $_GET['delete'];
    try {
        $delete_tkb = $conn->prepare("DELETE FROM `thoikhoabieu` WHERE idtkb = ?");
        $delete_tkb->execute([$delete_idtkb]);
        $message[] = 'Xóa thời khóa biểu thành công!';
    } catch (PDOException $e) {
        $message[] = 'Lỗi khi xóa thời khóa biểu: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm thời khóa biểu</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
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
        border: 3px solid #e26a6a;
        padding: 8px;
        text-align: center;
        white-space: nowrap;
    }
</style>


<body>
    <?php include 'admin_header.php'; ?>

    <section class="add-admin">
        <h1 class="title" style="color: lightyellow;text-shadow: 2px 2px 4px red;">Thêm thời khóa biểu</h1>

        <?php if (!empty($message)) { ?>
            <div class="message">
                <?php foreach ($message as $msg) {
                    echo "<p>$msg</p>";
                } ?>
            </div>
        <?php } ?>

        <form action="" method="POST" enctype="multipart/form-data" style=" background: var(--pink);">
            <div class="flex" >
                <div class="inputBox">
                    <input type="text" name="phonghoc" class="box" placeholder="Nhập phòng học" required> 
                    <input type="text" name="ngaybatdau" class="box" placeholder="Ngày bắt đầu (YYYY-MM-DD)" required readonly>
                    <input type="text" name="ngayketthuc" class="box" placeholder="Ngày kết thúc (YYYY-MM-DD)" required readonly>
                    <input type="text" name="phonghoc" class="box" placeholder="Phòng học" required>
                    <input type="text" name="thoigian" class="box" placeholder="Thời gian" required>
                </div>    
                <div class="inputBox" >
                    <select name="idhp" class="box" required >
                        <option value="" selected disabled>Chọn học phần</option>
                        <?php foreach ($hocphanes as $hocphan) : ?>
                            <option value="<?php echo $hocphan['idhp']; ?>"><?php echo $hocphan['tenhp']; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="idgv" class="box" required>
                        <option value="" selected disabled>Chọn giảng viên</option>
                        <?php foreach ($teachers as $teacher) : ?>
                            <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['tengv']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="tiethoc" class="box" placeholder="Tiết học" required>
                    <input type="text" name="ngayhoc" class="box" placeholder="Ngày học (YYYY-MM-DD)" required readonly>
                    <input type="text" name="Tuần học" class="box" placeholder="Tuần học" required>
                </div>
                    <select name="iddk" class="box" required>
                        <option value="" selected disabled>Chọn iddk</option>
                        <?php foreach ($dangkyhps as $dangkyhp) : ?>
                            <option value="<?php echo $dangkyhp['iddk']; ?>"><?php echo $dangkyhp['iddk']; ?></option>
                        <?php endforeach; ?>
                    </select>
            </div>
            <input type="submit" class="btn" value="Thêm thời khóa biểu" name="add_tkb">
        </form>
    </section>

    <section class="show-tkb">
        <h1 class="title" style="color: lightyellow; text-shadow: 2px 2px 4px red;;">Danh sách thời khóa biểu</h1>

        <?php if (!empty($message)) { ?>
            <div class="message">
                <?php foreach ($message as $msg) {
                    echo "<p>$msg</p>";
                } ?>
            </div>
        <?php } ?>

        <div style=" background: var(--pink);">
            <table  style="width: 100%; border-collapse: collapse; border: 10px; text-align: center; ">
                <tr style="font-size: 20px; color: blue;">
                    <th>Mã học phần</th>
                    <th>Mã giảng viên</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Phòng học</th>
                    <th colspan="2">Chỉnh sửa</th>
                </tr>

                <?php
                $show_tkb = $conn->prepare("SELECT tkb.*, hp.mahp, gv.tengv FROM thoikhoabieu tkb
                                           INNER JOIN hocphan hp ON tkb.idhp = hp.idhp
                                           INNER JOIN usergv gv ON tkb.idgv = gv.id");

                $show_tkb->execute();

                if ($show_tkb->rowCount() > 0) {
                    while ($fetch_tkb = $show_tkb->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <tr style="font-size: 20px;">
                            <td><?= $fetch_tkb['mahp']; ?></td>
                            <td><?= $fetch_tkb['tengv']; ?></td>
                            <td><?= $fetch_tkb['ngaybatdau']; ?></td>
                            <td><?= $fetch_tkb['ngayketthuc']; ?></td>
                            <td><?= $fetch_tkb['phonghoc']; ?></td>
                            <td><a href="admin_update_tkb.php?update=<?= $fetch_tkb['idtkb']; ?>" class="option-btn">Cập nhật</a></td>
                            <td>
                                <a href="admin_tkb.php?delete=<?= $fetch_tkb['idtkb']; ?>" class="delete-btn" onclick="return confirm('Xóa thời khóa biểu này?');">Xóa</a>
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
<img src="../images/images.jpg" alt="Ảnh lỗi" class="full-screen-image">

<script>
  $(document).ready(function() {
    // Chọn phần tử input có name="ngaybatdau" và name="ngayketthuc", sau đó kích hoạt date picker cho cả hai trường
    $("input[name='ngaybatdau'], input[name='ngayketthuc'], input[name='ngayhoc']").datepicker({
      dateFormat: "yy-mm-dd", // Định dạng ngày tháng năm sau khi chọn
      changeMonth: true,      // Hiển thị dropdown để chọn tháng
      changeYear: true        // Hiển thị dropdown để chọn năm
    });
  });
</script>

<a href="admin_page.php" class="option-btn" style="width: 10%; float: right;">Trở lại</a>

<script src="../js/script.js"></script>

</body>

</html>
