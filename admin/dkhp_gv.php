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

   <title>Trang học phần giảng viên</title>

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
</body>
