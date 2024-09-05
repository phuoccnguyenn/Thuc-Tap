<?php
@include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('Location:../admin_login.php');
    exit();
}

// Lấy dữ liệu sinh viên từ cơ sở dữ liệu
$select_students = $conn->query("SELECT usersv.id, usersv.tensv, usersv.email, usersv.idtrt, trangthai.trangthai
                                 FROM usersv
                                 INNER JOIN trangthai ON usersv.idtrt = trangthai.idtrt");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $new_status = $_POST['new_status'];

    // Cập nhật trạng thái trong bảng usersv
    $update_status_query = $conn->prepare("UPDATE usersv SET idtrt = (SELECT idtrt FROM trangthai WHERE trangthai = :new_status) WHERE id = :student_id");
    $update_status_query->bindParam(':student_id', $student_id);
    $update_status_query->bindParam(':new_status', $new_status);
    $update_status_query->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="../images/logo.png">
   <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <title>Trang chủ hệ thống</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

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
</style>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">
   <!-- Hiển thị bảng dữ liệu sinh viên -->
   <table style="width: 100%; border-collapse: collapse; border: 10px; text-align: center; ">
      <thead>
         <tr style="font-size: 20px; color: blue;">
            <th>ID</th>
            <th>Tên sinh viên</th>
            <th>Email</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
         </tr>
      </thead>
      <tbody>
         <?php while ($row = $select_students->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
               <td><?= $row['id']; ?></td>
               <td><?= $row['tensv']; ?></td>
               <td><?= $row['email']; ?></td>
               <td>
                  <form action="update_status.php" method="post">
                     <input type="hidden" name="student_id" value="<?= $row['id']; ?>">
                     <select name="new_status">
                        <option value="Đã đăng ký" <?= isset($row['trangthai']) && $row['trangthai'] === 'Đã đăng ký' ? 'selected' : ''; ?>>Đã đăng ký</option>
                        <option value="Chưa đăng ký" <?= isset($row['trangthai']) && $row['trangthai'] === 'Chưa đăng ký' ? 'selected' : ''; ?>>Chưa đăng ký</option>
                        <option value="Đã thanh toán" <?= isset($row['trangthai']) && $row['trangthai'] === 'Đã thanh toán' ? 'selected' : ''; ?>>Đã thanh toán</option>
                        <option value="Hủy đăng ký" <?= isset($row['trangthai']) && $row['trangthai'] === 'Hủy đăng ký' ? 'selected' : ''; ?>>Hủy đăng ký</option>
                     </select>
                  </form>
               </td>
               <td><button type="submit">Cập nhật</button></td>
            </tr>
         <?php endwhile; ?>
      </tbody>

   </table>
</section>

</body>
</html>
