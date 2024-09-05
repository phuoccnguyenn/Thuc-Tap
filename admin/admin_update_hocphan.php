<?php
require_once '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

// Kiểm tra đăng nhập
if (!isset($admin_id)) {
    header('Location: ../admin_login.php'); 
    exit(); 
}

$message = array(); // Khởi tạo mảng để lưu thông báo

if (isset($_POST['update_hocphan'])) {
    $idhpm = $_POST['idhpm'];
    $mahp = $_POST['mahp'];
    $tenhp = $_POST['tenhp'];
    $tinchi = intval($_POST['tinchi']);
    $sotien = $tinchi * 360;
    $teacher = $_POST['teacher'];
    $idhk = $_POST['idhk'];
    $soluongsv = intval($_POST['soluongsv']);
    $soluonggv = intval($_POST['soluonggv']);

    try {
        $stmt = $conn->prepare("CALL sp_UpdateHocPhan(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$idhpm, $mahp, $tenhp, $tinchi , $sotien, $teacher, $idhk, $soluongsv, $soluonggv]);

        if ($stmt->rowCount() > 0) {
            $message[] = "Cập nhật học phần thành công!";
        } else {
            $message[] = "Không có thay đổi hoặc cập nhật thất bại.";
        }
    } catch (PDOException $e) {
        $message[] = 'Lỗi khi cập nhật học phần: ' . $e->getMessage();
    }
}

// Các truy vấn để lấy thông tin cần thiết cho form
$stmt = $conn->prepare("SELECT id, tengv FROM usergv");
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT idhk, tenhk FROM hocky");
$stmt->execute();
$hockies = $stmt->fetchAll(PDO::FETCH_ASSOC);

$update_idhp = $_GET['update'];
$select_hocphan = $conn->prepare("SELECT * FROM `hocphan` WHERE idhp = ?");
$select_hocphan->execute([$update_idhp]);
$fetch_hocphan = $select_hocphan->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi"> 
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="../images/logo.png">

   <title>Cập nhật học phần</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="update-admin">

   <h1 class="title">Cập nhật học phần</h1>   
   <?php
      // Hiển thị thông báo
      if (!empty($message)) {
         foreach ($message as $msg) {
            echo '<p class="message">' . $msg . '</p>';
         }
      }

      $update_idhp = $_GET['update'];
      $select_hocphan = $conn->prepare("SELECT * FROM `hocphan` WHERE idhp = ?");
      $select_hocphan->execute([$update_idhp]);
      if($select_hocphan->rowCount() > 0){
         while($fetch_hocphan = $select_hocphan->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input style="font-size: 20px; text-align: center;" type="hidden" name="idhpm" value="<?= $fetch_hocphan['idhp']; ?>">

      <label for="mahocphan" style="font-size: 20px; color: #134220;">Mã học phần: &nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input style="text-align: center;font-size: 20px; border: 2px solid #000; padding: 5px;" id="mahocphan" type="text" name="mahp" placeholder="Nhập mã học phần" required class="box" value="<?= $fetch_hocphan['mahp']; ?>"><br>


      <br><label for="tenhocphan" style="font-size: 20px; color: #134220;">Tên học phần: &nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input style="text-align: center;font-size: 20px;border: 2px solid #000; padding: 5px;" type="text" name="tenhp" placeholder="Nhập tên học phần" required class="box" value="<?= $fetch_hocphan['tenhp']; ?>"><br>

      <br><label for="tinchi" style="font-size: 20px; color: #134220;">Tín chỉ: &nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input style="text-align: center;font-size: 20px;border: 2px solid #000; padding: 5px;" type="number" name="tinchi" min="0" placeholder="Nhập số tín chỉ" required class="box" value="<?= $fetch_hocphan['tinchi']; ?>"><br>

      <label for="tengiangvien" style="font-size: 20px; color: #134220;">Tên giảng viên: &nbsp;&nbsp;&nbsp;&nbsp;</label>
      <select name="teacher" class="box" required style="text-align: center;font-size: 20px; border: 2px solid #000; padding: 5px;">
          <option value="" selected disabled>Chọn giảng viên</option>
          <?php foreach ($teachers as $teacher): ?>
              <option value="<?= $teacher['id']; ?>" <?php if($fetch_hocphan['iddkgv'] == $teacher['id']) echo 'selected'; ?>>
                  <?= $teacher['tengv']; ?>
              </option>
          <?php endforeach; ?>
      </select>


      <br><label for="tinchi" style="font-size: 20px; color: #134220;">Học kỳ: &nbsp;&nbsp;&nbsp;&nbsp;</label>
      <select name="idhk" class="box" required style="text-align: center;font-size: 20px;border: 2px solid #000; padding: 5px;">
         <option value="" selected disabled>Chọn học kỳ</option>
         <?php foreach ($hockies as $hocky) : ?>
             <option value="<?php echo $hocky['idhk']; ?>" <?php if($fetch_hocphan['idhk'] == $hocky['idhk']) echo 'selected'; ?>>
                 <?php echo $hocky['tenhk']; ?>
             </option>
         <?php endforeach; ?>
     </select>
      <br><label for="soluongsv" style="font-size: 20px; color: #134220;">Số lượng sinh viên: &nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input style="text-align: center;font-size: 20px;border: 2px solid #000; padding: 5px;" type="number" name="soluongsv" min="0" placeholder="Nhập số lượng sinh viên" required class="box" value="<?= $fetch_hocphan['soluongsv']; ?>"><br>

      <br><label for="soluonggv" style="font-size: 20px; color: #134220;">Số lượng giảng viên: &nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input style="text-align: center;font-size: 20px;border: 2px solid #000; padding: 5px;" type="number" name="soluonggv" min="0" placeholder="Nhập số lượng giảng viên" required class="box" value="<?= $fetch_hocphan['soluonggv']; ?>"><br>

      <div style="padding-bottom: 50px;"></div>

      <div class="flex-btn">
         <input type="submit" class="btn" value="Cập nhật học phần" name="update_hocphan"></div>
      <div class="flex-btn">  
         <a href="admin_hphan.php" class="option-btn">Trở lại</a>
      </div>
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">Không có học phần nào được tìm thấy!</p>';
      }
   ?>

</section>


<script src="../js/script.js"></script>

</body>
</html>
