<?php

@include '../config.php';

session_start();

$sinhvien_id = $_SESSION['sinhvien_id'];

if(!isset($sinhvien_id)){
   header('location:../index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="../images/logo.png">
   <title>Giới thiệu</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style.css">

   <!-- w3s -->
   <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


</head>
<body>
<?php include 'header.php'; ?>
<!-- Sidebar -->
<div class="w3-sidebar w3-bar-block" style="display:none; z-index:5" id="mySidebar">
    <button class="w3-bar-item w3-button w3-xxlarge" onclick="w3_close()">Trang chủ</button>
    <a href="thoikhoabieu.php" class="w3-bar-item w3-button">Thời khóa biểu sinh viên</a>
    <a href="student_dkhp.php" class="w3-bar-item w3-button">Đăng ký học phần</a>
    <a href="thanhtoan.php" class="w3-bar-item w3-button">Thanh toán</a>
</div>


<!-- Đen phía dưới -->
<div class="w3-overlay" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<!-- 
<div class="home"> -->

<button class="w3-button w3-white w3-xxlarge" onclick="w3_open()">&#9776;</button>    
<!-- <div style="padding-top: 500px;"></div> -->

<section class="about" style="padding: 0 2rem 2rem 2rem;">
   <div class="row">
        <center><h1 style="color: darkred; font-size: 50px;">GIỚI THIỆU TRƯỜNG</h1></center>
        <hr>
         <div>
             <h4 style="text-align: center;color: red;padding-left: 250px;font-size: 25px;"><strong>TRƯỜNG ĐẠI HỌC SƯ PHẠM KỸ THUẬT VĨNH LONG</strong><br><strong>Huân chương lao động hạng Nhì, hạng Ba</strong><br><strong>Biểu tượng vàng Nguồn nhân lực Việt Nam</strong></h4><p style="text-align: justify;">
         </div>
</div>
<div>
   <hr class="half-width-rule">
   <br>
</div>
         
         <p style="font-size: 20px">Trường Đại học Sư phạm Kỹ thuật Vĩnh Long là trường công lập thuộc hệ thống đào tạo quốc dân của Việt Nam, là Trường trung ương đóng trên địa bàn thành phố Vĩnh Long. Hiện tại nhà trường do Bộ Lao động – Thương binh và Xã hội quản lý về nhân sự, ngân sách; Bộ Giáo dục và Đào tạo quản lý về chuyên môn.</p>
         <p style="font-size: 20px">Với vị trí nằm giữa trung tâm Đồng bằng sông Cửu Long, là một trong những trường trọng điểm về dạy nghề của cả nước, do dự án “Giáo dục kỹ thuật và dạy nghề” đầu tư.
     Trong những năm qua trường đào tạo và cung cấp nguồn nhân lực chất lượng cao hàng ngàn giáo viên dạy nghề và cán bộ kỹ thuật cho các tỉnh đồng bằng sông Cửu Long và các tỉnh thành khác của đất nước.</p>
         <p style="font-size: 20px">Qua thăm dò, hiện nay đội ngũ này đã phát huy năng lực tại nơi làm việc rất tốt, rất nhạy bén trong quá trình tiếp thu công nghệ mới, có nhiều người đã trở thành cán bộ chủ chốt ở các trường dạy nghề, trung tâm dạy nghề hoặc các cơ quan, đơn vị, nhà máy xí nghiệp, …phục vụ đắc lực cho sự nghiệp công nghiệp hóa – hiện đại hóa đất nước.</p>
     
     <div>
   <br>
</div>

         <h1 style="color: darkred; font-size: 30px;">MỤC TIÊU ĐÀO TẠO</h1><hr>
         <hr class="half-width-rule" style="width: 50%; float: left;"/>
         <br>
         <p style="font-size: 20px">* Đào tạo ra đội ngũ giáo viên dạy nghề và cán bộ kỹ thuật có trình độ đại học và sau đại học cho các trường đại học, cao đẳng, các cơ sở giáo dục trong cả nước nói chung và Đồng bằng sông Cửu Long nói riêng.</p>
         <p style="font-size: 20px">* Nắm vững lý thuyết và kỹ năng nghề đã học. Vận dụng thành thạo các kỹ năng để hành nghề. Có phẩm chất đạo đức, lương tâm nghề nghiệp, có tư duy khoa học, năng động, sáng tạo; có tinh thần trách nhiệm, ý thức cộng đồng và tác phong công nghiệp.</p>
         <p style="font-size: 20px">* Nắm vững các kiến thức cơ bản về nghiệp vụ sư phạm, vận dụng tổng hợp các phương pháp giảng dạy tiên tiến một cách có hệ thống, giúp đỡ HS-SV trong các hoạt động thực hành.</p>

         
     
         <h1 style="color: darkred; font-size: 30px">MÔI TRƯỜNG - PHƯƠNG TIỆN HỌC TẬP</h1><hr>
         <hr class="half-width-rule" style="width: 50%; float: left;"/>
         <br>
         <p style="font-size: 20px">Môi trường học tập lành mạnh với những thiết bị máy móc thực hành hiện đại, phương pháp giảng dạy tiên tiến, trang bị cho học sinh, sinh viên những kỹ năng chuyên môn nghề nghiệp và khả năng tin học và ngoại ngữ để làm việc.</p>
         <p style="font-size: 20px">Nhà trường xác định chất lượng đội ngũ giảng viên có ý nghĩa quan trọng đến việc đảm bảo chất lượng đào tạo, do đó đã không ngừng bổ sung các giảng viên có trình độ và phẩm chất tốt để phục vụ cho mục tiêu đào tạo, không ngừng cải tiến phương pháp đào tạo theo hướng phát huy tính năng động của học sinh, sinh viên thông qua phương phát học tập thảo luận nhóm, thuyết trình,… Kết quả học tập đạt loại khá, giỏi của học sinh, sinh viên ngày càng được nâng cao.</p>
         <p style="font-size: 20px">Với hơn 40 phòng học lý thuyết, xưởng thực hành rộng rãi, thoáng mát, hệ thống âm thanh, máy chiếu đa năng, phòng thí nghiệm chuyên dụng, máy tính nối mạng internet, wireless, thư viện hàng ngàn đầu sách giúp HSSV tra cứu thông tin, khu tự học đáp ứng nhu cầu thảo luận, phát triển, tự sáng tạo… Hệ thống ký túc xá đáp ứng trên 500 chỗ nội trú hàng năm.</p>
<!-- 
         <h1 style="color: darkred;">ĐỘI NGŨ GIẢNG VIÊN</h1><hr><br>
         <p style="font-weight: bold;">Đội ngũ giảng viên cơ hữu đến tháng 4/2018:</p>
         <p style="font-size: 20px">Tổng giảng viên cơ hữu: 302</p>
         <p style="font-size: 20px">Phó giáo sư: 05</p>
         <p style="font-size: 20px">Tiến sĩ: 30</p>
         <p style="font-size: 20px">Thạc sĩ và Nghiên cứu sinh: 228</p>
         <p style="font-size: 20px">Đại học và Cao học: 39</p> -->

         <h1 style="color: darkred;font-size: 30px;">CHÍNH SÁCH ƯU TIÊN</h1><hr>
         <hr class="half-width-rule" style="width: 50%; float: left;"/>
         
         <br>
         <p style="font-size: 20px">Thực hiện theo Quy chế tuyển sinh đại học và cao đẳng do Bộ GD&ĐT và Bộ LĐTB&XH ban hành; thí sinh thuộc diện ưu tiên được cộng điểm ưu tiên theo mức điểm được hưởng vào tổng điểm chung để xét tuyển. Ngoài ra, các sinh viên trúng tuyển vào trường ĐHSPKT Vĩnh Long được hưởng thêm các ưu tiên sau:</p>
         <p style="font-size: 20px">
            1. Học phí theo Nghị định 86/2015/NĐ-CP và miễn học phí 100% cho sinh viên trúng tuyển bậc đại học sư phạm kỹ thuật;
            2. Xét cấp học bổng từng học kỳ đối với sinh viên loại khá, giỏi trở lên;
            3. Được hưởng trợ cấp xã hội và ưu đãi giáo dục theo quy định của Nhà nước;
            4. Được tạm hoãn nghĩa vụ quân sự và hỗ trợ thủ tục vay vốn học tập;
            5. Được tư vấn giới thiệu việc làm, xuất khẩu lao động sau khi ra trường.
         </p>

         <h1 style="color: darkred;font-size: 30px;">CHƯƠNG TRÌNH ĐÀO TẠO</h1><hr>
         <hr class="half-width-rule" style="width: 50%; float: left;"/>
         <br>
         <p style="font-size: 20px">
            1. Chương trình đào tạo hiệu quả, đáp ứng thị trường lao động trong và ngoài nước;
            2. Trường Đại học duy nhất trên toàn quốc sinh viên ra trường có chứng chỉ kỹ năng nghề bậc 3/5;
            3. Được đào tạo tiếng Nhật và giới thiệu việc làm tại Nhật Bản sau khi tốt nghiệp ra trường.
         </p>

</div>
</section>
<?php include 'footer.php'; ?>

<script src="../js/script.js"></script>

</body>
</html>