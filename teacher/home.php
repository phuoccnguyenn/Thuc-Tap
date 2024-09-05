<?php

@include '../config.php';

session_start();

$giangvien_id = $_SESSION['giangvien_id'];

if (!isset($giangvien_id)) {
    header('Location:../index.php');
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
   <title>Trang chủ</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">

</head>

   
<?php @include 'header.php'; ?>

<!-- Sidebar -->
<div class="w3-sidebar w3-bar-block" style="display:none;z-index:5" id="mySidebar">
  <button class="w3-bar-item w3-button w3-xxlarge" onclick="w3_close()">Trang chủ </button>
      <a href="thoikhoabieu.php" class="w3-bar-item w3-button">Thời khóa biểu</a> 
      <a href="teacher_dkgv.php" class="w3-bar-item w3-button">Đăng ký giảng viên</a>
</div>

<!-- Đen phía dưới -->
<div class="w3-overlay" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<!-- 
<div class="home"> -->

<button class="w3-button w3-white w3-xxlarge" onclick="w3_open()">&#9776;</button>    
<center><h1 style="margin-top: 0px; margin-bottom: 50px; font-size: 50px; color: #00FF00; font-weight: bold;">Trường sư phạm kỹ thuật Vĩnh Long</h1></center>
<center><video width="900" height="500" style="text-align: center;" controls>
    <source src="../video/vlute_gv.mp4" type="video/mp4">
    Lỗi video
</video></center>
<div style="padding-bottom: 50px;"></div>


<?php include '../student/footer.php'; ?>


<script src="../js/script.js"></script>
 
</body>

</html>