<?php
include '../config.php';

if (isset($_POST['submit'])) {
   $tengv = $_POST['tengv'];
   $tengv = filter_var($tengv, FILTER_SANITIZE_STRING);

   $cccd = $_POST['cccd'];
   $cccd = filter_var($cccd, FILTER_SANITIZE_STRING);

   $chucvu = $_POST['chucvu'];
   $chucvu = filter_var($chucvu, FILTER_SANITIZE_STRING);

   $idkhoa = $_POST['idkhoa']; // Không cần filter_var vì giá trị được lấy từ danh sách chọn

   $danhhieu = $_POST['danhhieu'];
   $danhhieu = filter_var($danhhieu, FILTER_SANITIZE_STRING);

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $pass = $_POST['pass'];
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $cpass = $_POST['cpass'];
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;

   // Kiểm tra trường idkhoa
   $checkKhoa = $conn->prepare("SELECT * FROM `khoa` WHERE idkhoa = ?");
   $checkKhoa->execute([$idkhoa]);
   if ($checkKhoa->rowCount() == 0) {
      $message[] = 'Không tìm thấy mã khoa này!';
   } else {
      $select = $conn->prepare("SELECT * FROM `usergv` WHERE email = ?");
      $select->execute([$email]);

      if ($select->rowCount() > 0) {
         $message[] = 'Email này đã được sử dụng!';
      } else {
         if ($pass != $cpass) {
            $message[] = 'Xác nhận mật khẩu không khớp!';
         } else {
            // Tạo MD5 cho mật khẩu
            $hashed_password = md5($pass);

            $insert = $conn->prepare("INSERT INTO `usergv`(tengv,cccd,chucvu,idkhoa,danhhieu, email, password, image,idpq) VALUES(?,?,?,?,?,?,?,?,?)");
            $idpq = '2';
            $insert->execute([$tengv, $cccd, $chucvu, $idkhoa, $danhhieu, $email, $hashed_password, $image, $idpq]);

            if ($insert) {
               if ($image_size > 2000000) {
                  $message[] = 'Ảnh đại diện quá lớn!';
               } else {
                  move_uploaded_file($image_tmp_name, $image_folder);
                  $message[] = 'Đăng ký thành công!';
                  header('location:../index.php');
               }
            }
         }
      }
   }
}

// Lấy danh sách khoa từ cơ sở dữ liệu
$statement = $conn->prepare("SELECT idkhoa, tenkhoa FROM `khoa`");
$statement->execute();
$khoa_list = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="../images/logo.png">

   <title>Đăng ký sinh viên</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/components.css">

</head>
<body>


<div class="login">
<form action="" enctype="multipart/form-data" method="POST">
      <h3>Đăng ký giảng viên</h3> 
      <input type="text" name="tengv" class="box" placeholder="Nhập tên: " required>
      <input type="email" name="email" class="box" placeholder="Nhập email: " required>
      <input type="text" name="cccd" class="box" placeholder="Nhập căn cước công dân: " required>
      <input type="text" name="chucvu" class="box" placeholder="Nhập chức vụ: " required>
      <select name="idkhoa" class="box" required>
            <option value="" disabled selected>Chọn khoa của bạn</option>
            <?php foreach ($khoa_list as $khoa) { ?>
               <option value="<?php echo $khoa['idkhoa']; ?>"><?php echo $khoa['tenkhoa']; ?></option>
            <?php } ?>
      </select>
      <input type="text" name="danhhieu" class="box" placeholder="Nhập danh hiệu:" required>

      <div class="hidden-show">
            <input type="password" name="pass"  id="pass" class="box" placeholder="Nhập mật khẩu của bạn" required>
            <span><img src="images/eye.png" alt="" width="30" onclick="showHidden()"/></span>       
      </div>        
      <div class="hidden-show">
            <input type="password" name="cpass"  id="cpass"  class="box" placeholder="Nhập lại mật khẩu" required>
            <span><img src="images/eye.png" alt="" width="30" onclick="showHidden2()"/></span>
      </div> 
      <input type="file" name="image"  class="box" required accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="Đăng ký " class="btn" name="submit">
      <a href="admin_page.php" type="submit" class="btn" name="submit">Quay về</a>
   </form>
</div>

<style>
      
      span{
        display:block;
        position: absolute;
        right: 10px;
         top: 17px;
      }
      span img {
         cursor:pointer;
      }
      .hidden-show{
         position: relative;
      }
   </style>
   <script >
      isBool =true;
      function showHidden(){
            if(isBool){
               document.getElementById("pass").setAttribute("type","text");
               isBool = false;
            }else{
               document.getElementById("pass").setAttribute("type","password");
               isBool = true;

            }
      }
   </script>
   <script >
      isBool =true;
      function showHidden2(){
            if(isBool){
               document.getElementById("cpass").setAttribute("type","text");
               isBool = false;
            }else{
               document.getElementById("cpass").setAttribute("type","password");
               isBool = true;

            }
      }
   </script>



            
</body>
</html>