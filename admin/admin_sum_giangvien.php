<?php
@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('Location:../admin_login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_usergv = $conn->prepare("DELETE FROM `usergv` WHERE id = ?");
   $delete_usergv->execute([$delete_id]);
   header('location:admin_sum.php');
   exit; // Thêm dòng này để kết thúc chương trình sau khi xóa và chuyển hướng.
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

   <title>Tài khoản giáo viên</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="user-accounts">
   <h1 class="title">Tài khoản Giảng viên:</h1>
   <div class="box-container">
      <?php
         $select_students = $conn->prepare("SELECT * FROM `usergv` WHERE idpq = 2");
         $select_students->execute();
         while($fetch_students = $select_students->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <img src="../uploaded_img/<?= $fetch_students['image']; ?>" alt="">
         <p>Id giảng viên: <span><?= $fetch_students['id']; ?></span></p>
         <p>Tên giảng viên: <span><?= $fetch_students['tengv']; ?></span></p>
         <p> Email : <span><?= $fetch_students['email']; ?></span></p>
         <p> Loại tài khoản: <span style="color: blue;">Giảng viên</span></p>
         <a href="admin_sum.php?delete=<?= $fetch_teachers['id']; ?>" onclick="return confirm('Xóa tài khoản giảng viên không?');" class="delete-btn">Xóa</a>
      </div>
      <?php
      }
      ?>
   </div>
</section>
<a href="admin_sum.php" class="option-btn" style="width: 10%; float: right;">Trở lại</a>
<img src="../images/images.jpg" alt="Ảnh lỗi" class="full-screen-image">

<script src="../js/script.js"></script>

</body>
</html>
