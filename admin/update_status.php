<?php
@include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $student_id = $_POST['student_id'];
   $new_status = $_POST['new_status'];

   // Kiểm tra xem new_status có tồn tại trong bảng trangthai không
   $check_status_query = $conn->prepare("SELECT idtrt FROM trangthai WHERE trangthai = :new_status");
   $check_status_query->bindParam(':new_status', $new_status);
   $check_status_query->execute();
   $status_result = $check_status_query->fetch(PDO::FETCH_ASSOC);

   if ($status_result) {
      $update_query = $conn->prepare("UPDATE usersv SET idtrt = :new_status WHERE id = :student_id");
      $update_query->bindParam(':new_status', $status_result['idtrt']);
      $update_query->bindParam(':student_id', $student_id);

      if ($update_query->execute()) {
         header("Location: admin_thongke.php");
         exit();
      } else {
         echo "Có lỗi xảy ra khi cập nhật trạng thái.";
      }
   } else {
      echo "Trạng thái không hợp lệ.";
   }
}
?>
