<?php
@include '../config.php';

session_start();

$stmt = $conn->prepare("SELECT id, tengv FROM usergv");
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT idhk, tenhk FROM hocky");
$stmt->execute();
$hockies = $stmt->fetchAll(PDO::FETCH_ASSOC);

@include '../sql/add_hp.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../admin_login.php');
    exit; // Kết thúc thực thi mã khi chuyển hướng đến trang đăng nhập
}

$message = array(); // Khởi tạo mảng rỗng để lưu các thông báo lỗi

if (isset($_POST['add_hp'])) {
    $mahp = $_POST['mahp'];
    $tenhp = $_POST['tenhp'];
    $tinchi = $_POST['tinchi'];
    $tien_chi = $_POST['tien_chi'];
    $id = $_POST['teacher'];
    $soluongsv = $_POST['soluongsv'];
    $soluonggv = $_POST['soluonggv'];
    $idhk = $_POST['idhk'];

    // Kiểm tra và làm sạch dữ liệu đầu vào
    $mahp = filter_var($mahp, FILTER_SANITIZE_STRING);
    $tenhp = filter_var($tenhp, FILTER_SANITIZE_STRING);
    $tinchi = filter_var($tinchi, FILTER_SANITIZE_STRING);
    $tien_chi = filter_var($tien_chi, FILTER_SANITIZE_STRING);
    $soluongsv = filter_var($soluongsv, FILTER_SANITIZE_STRING);
    $soluonggv = filter_var($soluonggv, FILTER_SANITIZE_STRING);

    try {
        // Chuyển đổi biến $tinchi thành số nguyên
        $tinchi = intval($tinchi);

        // Kiểm tra nếu $tinchi là số nguyên hợp lệ trước khi thực hiện phép nhân
        if (is_int($tinchi)) {
            $sotien = $tinchi * $tien_chi;
            // Tiếp tục thực hiện câu truy vấn INSERT vào cơ sở dữ liệu với $sotien đã tính toán
            $insert = $conn->prepare("INSERT INTO `hocphan` (mahp, tenhp, tinchi,tien_chi, sotien, iddkgv, idhk, soluongsv, soluonggv) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)");
            $insert->execute([$mahp, $tenhp, $tinchi,$tien_chi, $sotien, $id, $idhk, $soluongsv, $soluonggv]);
            $message[] = 'Thêm học phần thành công!';
        } else {
            // Xử lý lỗi khi $tinchi không phải số hợp lệ
            $message[] = 'Lỗi: Giá trị tinchi không hợp lệ!';
        }
    } catch (PDOException $e) {
        $message[] = 'Lỗi khi thêm học phần: ' . $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    $delete_idhp = $_GET['delete'];
    try {
        $delete_hocphan = $conn->prepare("DELETE FROM `hocphan` WHERE idhp = ?");
        $delete_hocphan->execute([$delete_idhp]);
        $message[] = 'Xóa học phần thành công!';
    } catch (PDOException $e) {
        $message[] = 'Lỗi khi xóa học phần: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm học phần</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
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
        <h1 class="title" style="color: lightyellow; text-shadow: 2px 2px 4px red;">Thêm học phần mới</h1>

        <?php if (!empty($message)) { ?>
            <div class="message">
                <?php foreach ($message as $msg) {
                    echo "<p>$msg</p>";
                } ?>
            </div>
        <?php } ?>

        <form action="" method="POST" enctype="multipart/form-data" style=" background: var(--pink);">
            <div class="flex">
                <div class="inputBox">
                    <input type="text" name="mahp" class="box" placeholder="Nhập Mã học phần" required>
                    <input type="text" name="tien_chi" class="box" placeholder="Nhập tiền theo chỉ" required>

                    <select name="teacher" class="box" required>
                        <option value="" selected disabled>Chọn giảng viên</option>
                        <?php foreach ($teachers as $teacher) : ?>
                            <option value="<?php echo $teacher['id']; ?>"><?php echo $teacher['tengv']; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="idhk" class="box" required>
                        <option value="" selected disabled>Chọn học kỳ</option>
                        <?php foreach ($hockies as $hocky) : ?>
                            <option value="<?php echo $hocky['idhk']; ?>"><?php echo $hocky['tenhk']; ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div class="inputBox">
                    <input type="text" name="tenhp" class="box" placeholder="Nhập tên môn" required>
                    <input type="text" name="tinchi" class="box" placeholder="Nhập tín chỉ" required>
                    <input type="text" name="soluongsv" class="box" placeholder="Nhập số lượng SV" required>
                    <input type="text" name="soluonggv" class="box" placeholder="Nhập số lượng GV" required>
                </div>
            </div>

            <input type="submit" class="btn" value="Thêm học phần" name="add_hp">
        </form>
    </section>

    <section class="show-hocphan">
        <h1 class="title" style="color: lightyellow; text-shadow: 2px 2px 4px red;">Học phần đã được thêm vào</h1>

        <?php if (!empty($message)) { ?>
            <div class="message">
                <?php foreach ($message as $msg) {
                    echo "<p>$msg</p>";
                } ?>
            </div>
        <?php } ?>

        <div>
            <table style="width: 100%; border-collapse: collapse; border: 10px; text-align: center; background: var(--pink);">
                <tr style="font-size: 20px; color: blue;">
                    <th>Mã học phần</th>
                    <th>Tên môn</th>
                    <th>Tín chỉ</th>
                    <th>Tiền theo chỉ</th>
                    <th>Giảng viên</th>
                    <th>Số lượng SV</th>
                    <th>Số lượng GV</th>

                    <th colspan="2">Chỉnh sửa</th>
                </tr>

                <?php
                $show_hocphan = $conn->prepare("SELECT hp.idhp, hp.mahp, hp.tenhp, hp.tinchi, hp.tien_chi, hp.soluonggv, hp.soluongsv, gv.tengv 
                           FROM `hocphan` hp INNER JOIN `usergv` gv ON hp.iddkgv = gv.id");


                $show_hocphan->execute();
                if ($show_hocphan->rowCount() > 0) {
                    while ($fetch_hocphan = $show_hocphan->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <tr style="font-size: 20px;">
                            <td><?= $fetch_hocphan['mahp']; ?></td>
                            <td><?= $fetch_hocphan['tenhp']; ?></td>
                            <td><?= $fetch_hocphan['tinchi']; ?></td>
                            <td><?= $fetch_hocphan['tien_chi']; ?></td>
                            <td><?= $fetch_hocphan['tengv']; ?></td>
                            <td><?= $fetch_hocphan['soluongsv']; ?></td>
                            <td><?= $fetch_hocphan['soluonggv']; ?></td>

                            <td><a href="admin_update_hocphan.php?update=<?= $fetch_hocphan['idhp']; ?>" class="option-btn">Cập nhật</a></td>
                            <td>
                                <a href="admin_hphan.php?delete=<?= $fetch_hocphan['idhp']; ?>" class="delete-btn" onclick="return confirm('Xóa học phần này?');">Xóa học phần</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="8">Học phần chưa được thêm vào!!!</td></tr>';
                }
                ?>
            </table>
        </div>
    </section>
<img src="../images/images.jpg" alt="Ảnh lỗi" class="full-screen-image">

<a href="admin_page.php" class="option-btn" style="width: 10%; float: right;">Trở lại</a>
<script src="../js/script.js"></script>
    
</body>

</html>
