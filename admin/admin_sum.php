<?php
@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('Location:../admin_login.php');
   exit;
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
   <title>Trang tổng tài khoản</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>
   
<?php include 'admin_header.php'; ?>


<section class="user-accounts">
   <h1 class="title" style="color: lightyellow;text-shadow: 2px 2px 4px red;">Tài khoản người dùng</h1>


<div class="box-container">

<!-- Hiển thị tài khoản admin -->
<?php
   $select_admins = $conn->prepare("SELECT * FROM `admin` WHERE idpq = 1");
   $select_admins->execute();
   while($fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC)){
?>
<div class="box">
   <img src="../uploaded_img/<?= $fetch_admins['image']; ?>" alt="">
   <p> user id : <span><?= $fetch_admins['id']; ?></span></p>
   <!-- Thay thế 'tensv' bằng 'tk' -->
   <p>Tên : <span><?= $fetch_admins['tk']; ?></span></p>
   <p> <br></p>
   <p> Loại tài khoản: <span style="color: orange;">Admin</span></p>
</div>
<?php
}
?>

      <!-- Hiển thị tài khoản giáo viên -->
<?php
   $select_teachers = $conn->prepare("SELECT * FROM `usergv` WHERE idpq = 2");
   $select_teachers->execute();
   while($fetch_teachers = $select_teachers->fetch(PDO::FETCH_ASSOC)){
?>
<div class="box">
   <img src="../uploaded_img/<?= $fetch_teachers['image']; ?>" alt="">
   <p> user id : <span><?= $fetch_teachers['id']; ?></span></p>
   <!-- Chỉnh sửa trường 'tensv' thành 'tengv' -->
   <p>Tên : <span><?= $fetch_teachers['tengv']; ?></span></p>
   <!-- Chỉnh sửa trường 'email' thành 'cccd' hoặc trường khác phù hợp -->
   <p> Email : <span><?= $fetch_teachers['email']; ?></span></p>
   <p> Loại tài khoản: <span style="color: green;">Giáo viên</span></p>
</div>
<?php
}
?>

<!-- Hiển thị tài khoản sinh viên -->
<?php
   $select_students = $conn->prepare("SELECT * FROM `usersv` WHERE idpq = 3");
   $select_students->execute();
   while($fetch_students = $select_students->fetch(PDO::FETCH_ASSOC)){
?>
<div class="box"">
   <img src="../uploaded_img/<?= $fetch_students['image']; ?>" alt="">
   <p> user id : <span><?= $fetch_students['id']; ?></span></p>
   <!-- Chỉnh sửa trường 'tensv' thành 'tensv' -->
   <p>Tên : <span><?= $fetch_students['tensv']; ?></span></p>
   <!-- Chỉnh sửa trường 'email' thành 'email' hoặc trường khác phù hợp -->
   <p> Email : <span><?= $fetch_students['email']; ?></span></p>
   <p> Loại tài khoản: <span style="color: blue;">Sinh viên</span></p>
</div>
<?php
}
?>
</div>
<div class="box" style="margin-left: 25%; margin-top: 5%;">
   <button style="background: none;"><a href="admin_sum_admin.php" class="select-btn">Trang tổng admin</a> </button>
   <button style="background: none;"><a href="admin_sum_student.php" class="select-btn">Trang tổng sinh viên</a> </button>
   <button style="background: none;"><a href="admin_sum_giangvien.php" class="select-btn">Trang tổng giảng viên</a> </button>
</div>

</section>
<img src="../images/images.jpg" alt="Ảnh lỗi" class="full-screen-image">

<script src="../js/script.js"></script>

</body>
</html>
