<?php
@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('Location:../admin_login.php');
    exit();
};
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

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
   <style>
       /* Thêm CSS để làm ảnh full màn hình */
       .full-screen-image {
           position: fixed;
           top: 0;
           left: 0;
           width: 100%;
           height: 100%;
           object-fit: cover;
           z-index: -1; /* Đảm bảo ảnh nằm phía sau nội dung */
       }
       .w3-white, .w3-hover-white:hover {
          color: #000!important;
          background-color: #9776 !important;
}
   </style>
<body>
   <?php include 'admin_header.php'; ?>

<!-- Sidebar -->
<div class="w3-sidebar w3-bar-block" style="display:none;z-index:5; background-color: #f483f7;" id="mySidebar">
  <button class="w3-bar-item w3-button w3-xxlarge" onclick="w3_close()">Trang chủ </button>
      <a href="register.php" class="w3-bar-item w3-button">ĐK admin</a>
      <a href="register_gv.php" class="w3-bar-item w3-button">ĐK giảng viên</a> 
      <a href="register_sv.php" class="w3-bar-item w3-button">Đk sinh viên</a>
      <a href="admin_hphan.php" class="w3-bar-item w3-button">Học phần cho sinh viên</a>
      <a href="admin_tkb.php" class="w3-bar-item w3-button">Thời khóa biểu</a>
      <a href="admin_sum.php" class="w3-bar-item w3-button">Tổng tài khoản</a>
      <a href="test.php" class="w3-bar-item w3-button">Học phí trong học kỳ </a>
      <a href="test1.php" class="w3-bar-item w3-button">Số lượng</a>
      <a href="thanhtoanadmin.php" class="w3-bar-item w3-button">Thanh toán</a>
</div>

<!-- Đen phía dưới -->
<div class="w3-overlay" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<button class="w3-button w3-white w3-xxlarge" onclick="w3_open()">&#9776;</button>    
<div style="padding-top: 500px;"></div>
<!-- <img src="../images/images.jpg" alt="Ảnh lỗi"> -->
<img src="../images/images.jpg" alt="Ảnh lỗi" class="full-screen-image">


<script src="../js/script.js"></script>

</body>
</html>