<?php
session_start();
@include '../config.php'; // Kết nối đến CSDL
@include '../sql/update_dkhp_student.php'; // Kết nối đến CSDL

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['sinhvien_id'])) {
    header('Location: ../index.php');
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Form Cập nhật Học Phần</title>
</head>
<style>
    table,
    th,
    td {
        border: 3px solid #e26a6a;
        padding: 8px;
        text-align: center;
        white-space: nowrap;
    }
</style>
<body>
   
<?php include 'header.php'; ?>

<section class="update-gv_gv">

   <h1 class="title">Cập nhật đăng ký học phần của sinh viên</h1>   
   <?php
      if (!empty($message)) {
         foreach ($message as $msg) {
            echo '<p class="message">' . $msg . '</p>';
         }
      }

      if (isset($_GET['update']) && is_numeric($_GET['update'])) {
         $update_iddk = $_GET['update'];
         $select_dangkyhp = $conn->prepare("SELECT * FROM dangkyhp WHERE iddk = ?");
         $select_dangkyhp->execute([$update_iddk]);
         if ($select_dangkyhp->rowCount() > 0) {
            $fetch_dangkyhp = $select_dangkyhp->fetch(PDO::FETCH_ASSOC);
   ?>
   <form action="student_update_dkhp.php" method="post" enctype="multipart/form-data"  style="background: var(--pink);">
      <input type="hidden" name="iddk" value="<?= $fetch_dangkyhp['iddk']; ?>">

      <label for="idhp" style="font-size: 20px; color: #134220;">Học phần:</label>
      <select name="idhp" class="box" required style="text-align: center;font-size: 20px; border: 2px solid #000; padding: 5px;">
         <option value="" selected disabled>Chọn học phần</option>
         <?php
            // Lấy danh sách học phần từ CSDL để đổ vào trường chọn select
            $query_hocphan = "SELECT idhp, tenhp FROM hocphan";
            $result_hocphan = $conn->query($query_hocphan);
            foreach ($result_hocphan as $row) {
               echo '<option value="' . $row['idhp'] . '"';
               if ($row['idhp'] == $fetch_dangkyhp['idhp']) {
                  echo ' selected';
               }
               echo '>' . $row['tenhp'] . '</option>';
            }
         ?>
      </select><br>

      <label for="idhocky" style="font-size: 20px; color: #134220;">Học kỳ:</label>
      <select name="idhocky" class="box" required style="text-align: center;font-size: 20px;border: 2px solid #000; padding: 5px;">
         <option value="" selected disabled>Chọn học kỳ</option>
         <?php
            // Lấy danh sách học kỳ từ CSDL để đổ vào trường chọn select
            $query_hocky = "SELECT idhk, tenhk FROM hocky";
            $result_hocky = $conn->query($query_hocky);
            foreach ($result_hocky as $row) {
               echo '<option value="' . $row['idhk'] . '"';
               if ($row['idhk'] == $fetch_dangkyhp['idhocky']) {
                  echo ' selected';
               }
               echo '>' . $row['tenhk'] . '</option>';
            }
         ?>
      </select><br>

      <label for="idng" style="font-size: 20px; color: #134220;">Ngân hàng:</label>
      <select name="idng" class="box" required style="text-align: center;font-size: 20px; border: 2px solid #000; padding: 5px;">
         <option value="" selected disabled>Chọn ngân hàng</option>
         <?php
            // Lấy danh sách ngân hàng từ CSDL để đổ vào trường chọn select
            $query_nganhang = "SELECT idng, tenng FROM nganhang";
            $result_nganhang = $conn->query($query_nganhang);
            foreach ($result_nganhang as $row) {
               echo '<option value="' . $row['idng'] . '"';
               if ($row['idng'] == $fetch_dangkyhp['idng']) {
                  echo ' selected';
               }
               echo '>' . $row['tenng'] . '</option>';
            }
         ?>
      </select><br>

      <div class="flex-btn">
         <input type="submit" class="btn" value="Cập nhật" name="update_dangkyhp">
      </div>
   </form>
   <?php
         } else {
            echo '<p class="empty">Không có đăng ký học phần nào được tìm thấy!</p>';
         }
      }
   ?>

</section>


<script src="../js/script.js"></script>

</body>
</html>
