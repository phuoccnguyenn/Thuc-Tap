<?php
@include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $tk = filter_var($_POST['tk'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $hashedPassword = md5($password); // Mã hóa mật khẩu dưới dạng MD5

    try {
        // Kiểm tra bảng 'admin'
        $sql_admin = "SELECT * FROM `admin` WHERE tk = ? AND password = ?";
        $stmt_admin = $conn->prepare($sql_admin);
        $stmt_admin->execute([$tk, $hashedPassword]);
        $rowCount_admin = $stmt_admin->rowCount();

        if ($rowCount_admin > 0) {
            $_SESSION['admin_id'] = $stmt_admin->fetch(PDO::FETCH_ASSOC)['id'];
            header('location: admin/admin_page.php');
            exit();
        }

        // Nếu không tìm thấy người dùng trong bất kỳ bảng nào
        $message[] = 'Tài khoản hoặc mật khẩu không chính xác!';
    } catch (PDOException $e) {
        echo "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng nhập Admin</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/components.css">
<script>
    document.addEventListener('keydown', function(event) {
        if (event.ctrlKey && event.key === 'q') {
            window.location.href = '../index.php';
        }
    });
</script>

</head>
<body>

<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>
   

   <div class="login">
      <form action="" method="POST">
      <h3>ĐĂNG NHẬP ADMIN</h3>
      <input type="text" name="tk" class="box" placeholder="Nhập tk của bạn" required>
      <div class="hidden-show">
         <input type="password" name="password" id="password" class="box" placeholder="Nhập mật khẩu của bạn" required>
         <span><img src="images/eye.png" alt="" width="30" onclick="showHidden()"/></span>
      </div>
      <input type="submit" value="Đăng nhập" class="btn" name="submit">
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
               document.getElementById("password").setAttribute("type","text");
               isBool = false;
            }else{
               document.getElementById("password").setAttribute("type","password");
               isBool = true;

            }
      }
   </script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
   <script>
       document.addEventListener('DOMContentLoaded', function() {
           var hammer = new Hammer(document.body);

           hammer.get('swipe').set({ direction: Hammer.DIRECTION_DOWN, pointers: 2 });
           hammer.on('swipedown', function(event) {
               window.location.href = '../index.php';
           });
       });
   </script>

</body>
</html>
