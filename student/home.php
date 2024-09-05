<?php
session_start();

include '../config.php';

$sinhvien_id = $_SESSION['sinhvien_id'];

if (!isset($sinhvien_id)) {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="../images/logo.png">
   <title>Trang chủ</title>
   <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/style.css">
   <script>
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 'q') {
                window.location.href = '../admin_login.php';
            }
        });
    </script>
</head>
<body>
<?php @include 'header.php'; ?>

<!-- Sidebar -->
<div class="w3-sidebar w3-bar-block" style="display:none; z-index:5" id="mySidebar">
    <button class="w3-bar-item w3-button w3-xxlarge" onclick="w3_close()">Trang chủ</button>
    <a href="thoikhoabieu.php" class="w3-bar-item w3-button">Thời khóa biểu sinh viên</a>
    <a href="student_dkhp.php" class="w3-bar-item w3-button">Đăng ký học phần</a>
    <a href="thanhtoan.php" class="w3-bar-item w3-button">Thanh toán</a>
</div>

<!-- Đen phía dưới -->
<div class="w3-overlay" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<!-- <div class="home"> -->
<button class="w3-button w3-white w3-xxlarge" onclick="w3_open()">&#9776;</button>
<center><h1 style="margin-top: -50px; margin-bottom: 50px; font-size: 50px; color: #00FF00; font-weight: bold;">Trường sư phạm kỹ thuật Vĩnh Long</h1></center>
<center><video width="800" height="500" style="text-align: center; float: left;" controls>
    <source src="../video/vlute.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video></center>


<div class="home" style="float: right; margin-left: -10px;"> 
<center><form id="form-sv">

<center><form id="form-sv">
<center><div class="col-md-6" id="TBCont">
<div class="box box-warning box-solid">
<div class="box-header"><h3 class="box-title", style = "color: red">Thông báo</h3></div>
<div class="box-body" id="TBLeft"><table class="table table-hover table-striped table-responsive"><tbody><tr><td style="cursor:pointer" onclick="viewTBDetail(1071)"><span class="text-black">Kiểm tra, xác nhận lịch thi và thi Học kỳ phụ, năm học 2022-2023</span><br><span class="text-sm text-info">Ngày đăng: 25/02/2023</span></td></tr><tr><td style="cursor:pointer" onclick="viewTBDetail(1070)"><span class="text-black">Kiểm tra và xác nhận lịch thi học kỳ 1 - đợt 1, năm học 2022-2023</span><br><span class="text-sm text-info">Ngày đăng: 14/11/2022</span></td></tr><tr><td style="cursor:pointer" onclick="viewTBDetail(1069)"><span class="text-black">Kiểm tra và xác nhận lịch thi và thi học kỳ Hè, năm học 2021-2022</span><br><span class="text-sm text-info">Ngày đăng: 25/08/2022</span></td></tr><tr><td style="cursor:pointer" onclick="viewTBDetail(1068)"><span class="text-black">Kiểm tra và xác nhận lịch thi và thi học kỳ Phụ, năm học 2021-2022</span><br><span class="text-sm text-info">Ngày đăng: 27/02/2022</span></td></tr><tr><td style="cursor:pointer" onclick="viewTBDetail(1067)"><span class="text-black">V/v kiểm tra Tiếng Anh đầu vào Khóa 46, năm học 2021-2022</span><br><span class="text-sm text-info">Ngày đăng: 15/11/2021</span></td></tr><tr><td style="cursor:pointer" onclick="viewTBDetail(1066)"><span class="text-black">Kiểm tra và xác nhận lịch thi Học kỳ 2 - Đợt 2, năm học 2020-2021</span><br><span class="text-sm text-info">Ngày đăng: 14/06/2021</span></td></tr><tr><td style="cursor:pointer" onclick="viewTBDetail(1065)"><span class="text-black">Kiểm tra, xác nhận TKB và nộp học phí HK hè, 2020-2021</span><br><span class="text-sm text-info">Ngày đăng: 11/06/2021</span></td></tr><tr><td style="cursor:pointer" onclick="viewTBDetail(1064)"><span class="text-black">Theo dõi cập nhật TKB và lịch thi Đợt 1 của HK2, 2020-2021</span><br><span class="text-sm text-info">Ngày đăng: 28/05/2021</span></td></tr><tr><td></td></tr></tbody></table>                                            
</div>
</div>
</div></center>
</form></center>
</div>

<div style="padding-bottom: 50px;"></div>
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script>
</body>
</html>
