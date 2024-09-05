<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qlsv";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["mssv"]) && isset($_GET["tenhk"])) {
    $mssv = $_GET["mssv"];
    $tenhk = $_GET["tenhk"];
    
    // Truy vấn để lấy thông tin sinh viên và tên học kỳ
    $sql_sinhvien = "SELECT id, mssv, tensv FROM usersv WHERE mssv = ?";
    $stmt_sinhvien = $conn->prepare($sql_sinhvien);
    $stmt_sinhvien->bind_param("s", $mssv);
    $stmt_sinhvien->execute();
    $result_sinhvien = $stmt_sinhvien->get_result();

    if ($result_sinhvien->num_rows > 0) {
        $row_sinhvien = $result_sinhvien->fetch_assoc();
        $idsv = $row_sinhvien["id"];
        $tensv = $row_sinhvien["tensv"];

        // Tính tổng số tín chỉ dựa trên tên học kỳ và học phần đã đăng ký
        $sql_tongtinchi = "SELECT SUM(hp.tinchi) AS tong_tinchi
        FROM dangkyhp dk
        INNER JOIN hocphan hp ON dk.idhp = hp.idhp
        INNER JOIN hocky hk ON dk.idhocky = hk.idhk
        WHERE dk.idusv = ? AND hk.tenhk = ?";
        $stmt_tongtinchi = $conn->prepare($sql_tongtinchi);
        $stmt_tongtinchi->bind_param("is", $idsv, $tenhk);
        $stmt_tongtinchi->execute();
        $result_tongtinchi = $stmt_tongtinchi->get_result();

        echo "<h3>Tổng tiền học phí của sinh viên $tensv trong học kỳ $tenhk</h3>";

        if ($result_tongtinchi !== false && $result_tongtinchi->num_rows > 0) {
            $row_tongtinchi = $result_tongtinchi->fetch_assoc();
            $tong_tinchi = $row_tongtinchi["tong_tinchi"];
            $tong_tien = $tong_tinchi * 360; // Tính tổng tiền dựa trên tổng số tín chỉ nhân 360
        } else {
            $tong_tinchi = 0;
            $tong_tien = 0;
        }
    } else {
        $tensv = "";
        $tong_tinchi = 0;
        $tong_tien = 0;
    }
} else {
    //header("Location: thanhtoanadmin.php"); // Chuyển hướng về trang trước nếu không có MSSV
    //exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $mssv = $_POST["mssv"]; // Lấy giá trị mssv từ form POST
    $idsv = $_POST["idsv"];
    $tensv = $_POST["tensv"];
    $tong_tien = $_POST["tong_tien"];
    $sotienthanhtoan = isset($_POST["sotienthanhtoan"]) ? $_POST["sotienthanhtoan"] : null;
    
    $sodu = $tong_tien - $sotienthanhtoan; // Tính số dư
    
    // Kiểm tra xem đã có dữ liệu cho mssv trong bảng tongtien chưa
    $sql_check = "SELECT * FROM tongtien WHERE idsv = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $idsv);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Cập nhật số tiền đã thanh toán và số dư cho mssv trong bảng tongtien
        $sql_update = "UPDATE tongtien SET sotienthanhtoan = ?, sodu = ? WHERE idsv = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("iii", $sotienthanhtoan, $sodu, $idsv);
        if ($stmt_update->execute()) {
            echo "<p style='font-size: 18px;'>Cập nhật thành công.</p>";
        } else {
            echo "<p style='font-size: 18px; color: red;'>Có lỗi xảy ra: " . $stmt_update->error  . "</p>";
        }
    } else {
        // Thêm dữ liệu mới vào bảng tongtien
        $sql_insert = "INSERT INTO tongtien (idsv, tongtien, sotienthanhtoan, sodu) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iiii", $idsv, $tong_tien, $sotienthanhtoan, $sodu);
        if ($stmt_insert->execute()) {
            echo "<p style='font-size: 18px;'>Cập nhật thành công.</p>";
        } else {
            echo "<p style='font-size: 18px; color: red;'>Có lỗi xảy ra: " . $stmt_insert->error  . "</p>";
        }
    }
}
?>
<?php
    $sql_bank_info = "SELECT nh.tenng, nh.stk, tt.hinhthuc
    FROM tongtien tti
    INNER JOIN dangkyhp dk ON tti.idsv = dk.idusv
    INNER JOIN nganhang nh ON dk.idng = nh.idng
    INNER JOIN thanhtoan tt ON dk.idtt = tt.idtt
    WHERE tti.idsv = ?";
    $stmt_bank_info = $conn->prepare($sql_bank_info);
    $stmt_bank_info->bind_param("i", $idsv);
    $stmt_bank_info->execute();
    $result_bank_info = $stmt_bank_info->get_result();

    if ($result_bank_info !== false && $result_bank_info->num_rows > 0) {
    $row_bank_info = $result_bank_info->fetch_assoc();
    $tennganhang = $row_bank_info["tenng"];
    $soTaikhoan = $row_bank_info["stk"];
    $hinhThucThanhToan = $row_bank_info["hinhthuc"];
    }
?>
    
<!DOCTYPE html>
<html>
<head>
    <title>Tổng tiền</title>
    <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/x-icon" href="../images/logo.png">
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/components.css">
</head>
<body>
    <h1 style="text-align: center; font-size: 50px; color: blue;">Thông tin sinh viên</h1 style="text-align: center;">
    <p style=" font-size: 30px; color: black;">MSSV: <?php echo $mssv; ?></p>
    <p style=" font-size: 30px; color: black;">Tên sinh viên: <?php echo $tensv; ?></p>
    <h1 style="text-align: center; font-size: 50px; color: blue;">Tổng tiền cần thanh toán</h2>
    <p  style=" font-size: 30px; color: black;">Tổng tiền: <?php echo $tong_tien; ?></p>
    <?php if (isset($tennganhang) && isset($soTaikhoan) && isset($hinhThucThanhToan)) : ?>
        <h3 style="text-align: center; font-size: 50px; color: blue;">Thông tin ngân hàng</h3>
        <p  style=" font-size: 30px; color: black;">Tên ngân hàng: <?php echo $tennganhang; ?></p>
        <p  style=" font-size: 30px; color: black;">Số tài khoản: <?php echo $soTaikhoan; ?></p>
        <p  style=" font-size: 30px; color: black;">Hình thức thanh toán: <?php echo $hinhThucThanhToan; ?></p>
    <?php endif; ?>
    

    <form method="post">
        <input type="hidden" name="mssv" value="<?php echo $mssv; ?>">
        <input type="hidden" name="tensv" value="<?php echo $tensv; ?>">
        <input type="hidden" name="idsv" value="<?php echo $idsv; ?>">
        <input type="hidden" name="tong_tien" value="<?php echo $tong_tien; ?>">
        <input type="hidden" name="tennganhang" value="<?php echo $tennganhang; ?>">
        <input type="hidden" name="soTaikhoan" value="<?php echo $soTaikhoan; ?>">
        <input type="hidden" name="hinhThucThanhToan" value="<?php echo $hinhThucThanhToan; ?>">
        
        <label for="sotienthanhtoan"  style=" font-size: 30px; color: black;">Số tiền đã thanh toán:</label>
        <input type="number" name="sotienthanhtoan"  style=" font-size: 30px; color: black;">
        <br>
        
        <input type="submit" name="update" value="Cập nhật" style="margin:auto; display:block; background-color: blueviolet; font-size: 50px;">
    </form>

    <?php
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    ?>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) : ?>
    <?php if ($sodu == 0) : ?>
        <p>Sinh viên đã hoàn thành học phí.</p>
        <?php
        $idtrt = ($sodu == 0) ? 3 : 4; // Set idtrt based on the payment status

        // Retrieve the registration status from the trangthai table
        $sql_trangthai = "SELECT trangthai FROM trangthai WHERE idtrt = ?";
        $stmt_trangthai = $conn->prepare($sql_trangthai);
        $stmt_trangthai->bind_param("i", $idtrt);
        $stmt_trangthai->execute();
        $result_trangthai = $stmt_trangthai->get_result();
        
        if ($result_trangthai->num_rows > 0) {
            $row_trangthai = $result_trangthai->fetch_assoc();
            $trangthai_dk = $row_trangthai["trangthai"];
            echo "<p>Trạng thái : " . $trangthai_dk . "</p>";
            
            // Update registration status in the dangkyhp table
            $sql_update_trangthai = "UPDATE dangkyhp SET idtrt = ? WHERE idusv = ?";
            $stmt_update_trangthai = $conn->prepare($sql_update_trangthai);
            $stmt_update_trangthai->bind_param("ii", $idtrt, $idsv);
            if ($stmt_update_trangthai->execute()) {
                echo "";
            } else {
                echo "" . $stmt_update_trangthai->error;
            }
        } else {
            echo "Không tìm thấy trạng thái đăng ký.";
        }
        require './PHPMailer/src/Exception.php';
        require './PHPMailer/src/PHPMailer.php';
        require './PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'huytran45690@gmail.com'; // Thay đổi thành địa chỉ email gửi
        $mail->Password = 'obofktkctkwpqtfa'; // Thay đổi thành mật khẩu email gửi
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->isHTML(true);
        $mail->setFrom('huytran45690@gmail.com','HE THONG THANH TOAN'); // Thay đổi theo địa chỉ email và tên của bạn
        $mail->addAddress($mssv,'@st.vlute.edu.vn'); // Thay đổi thành địa chỉ email người nhận thông báo
        $mail->Subject = 'Hoàn thành học phí';
        // Tạo nội dung email
        $emailContent = "Sinh viên $tensv (MSSV: $mssv) đã hoàn thành học phí. Chi tiết:<br>
        Tổng tiền: $tong_tien<br>
        Số tiền đã thanh toán: $sotienthanhtoan<br>
        Số dư: $sodu<br>
        Tên ngân hàng: $tennganhang<br>
        Số tài khoản: $soTaikhoan<br>
        Hình thức thanh toán: $hinhThucThanhToan";
        $mail->Body = $emailContent;
        try {
            $mail->send();
            echo 'Email thông báo đã được gửi đi.';
        } catch (Exception $e) {
            echo 'Lỗi khi gửi email: ' . $mail->ErrorInfo;
        }
        ?>
    <?php elseif ($tong_tien > $sotienthanhtoan) : ?>
        <?php
         $idtrt = ($tong_tien > $sotienthanhtoan) ?4:3;
         $sql_trangthai = "SELECT trangthai FROM trangthai WHERE idtrt = ?";
         $stmt_trangthai = $conn->prepare($sql_trangthai);
         $stmt_trangthai->bind_param("i", $idtrt);
         $stmt_trangthai->execute();
         $result_trangthai = $stmt_trangthai->get_result();

         if ($result_trangthai->num_rows > 0) {
             $row_trangthai = $result_trangthai->fetch_assoc();
             $trangthai_dk = $row_trangthai["trangthai"];
             echo "<p>Trạng thái : " . $trangthai_dk . "</p>";

             // Update registration status in the dangkyhp table
             $sql_update_trangthai = "UPDATE dangkyhp SET idtrt = ? WHERE idusv = ?";
             $stmt_update_trangthai = $conn->prepare($sql_update_trangthai);
             $stmt_update_trangthai->bind_param("ii", $idtrt, $idsv);
             if ($stmt_update_trangthai->execute()) {
                 echo "";
             } else {
                 echo "" . $stmt_update_trangthai->error;
             }
         } else {
             echo "Không tìm thấy trạng thái đăng ký.";
         }
        ?>
        <p>Sinh viên chưa hoàn thành học phí <?php echo $sodu; ?></p>
    <?php else : ?>
        <?php
            $sodu = $sotienthanhtoan - $tong_tien; 
            $idtrt = ($sodu>0) ? 3 : 4;
            $sql_trangthai = "SELECT trangthai FROM trangthai WHERE idtrt = ?";
            $stmt_trangthai = $conn->prepare($sql_trangthai);
            $stmt_trangthai->bind_param("i", $idtrt);
            $stmt_trangthai->execute();
            $result_trangthai = $stmt_trangthai->get_result();

            if ($result_trangthai->num_rows > 0) {
                $row_trangthai = $result_trangthai->fetch_assoc();
                $trangthai_dk = $row_trangthai["trangthai"];
                echo "<p>Trạng thái : " . $trangthai_dk . "</p>";

                // Update registration status in the dangkyhp table
                $sql_update_trangthai = "UPDATE dangkyhp SET idtrt = ? WHERE idusv = ?";
                $stmt_update_trangthai = $conn->prepare($sql_update_trangthai);
                $stmt_update_trangthai->bind_param("ii", $idtrt, $idsv);
                if ($stmt_update_trangthai->execute()) {
                    echo "Trạng thái đã được cập nhật.";
                } else {
                    echo "Lỗi khi cập nhật trạng thái: " . $stmt_update_trangthai->error;
                }
            } else {
                echo "Không tìm thấy trạng thái đăng ký.";
            }
            $scheduledTime = date('Y-m-d H:i:s', strtotime('+1 day'));
        
            require './PHPMailer/src/Exception.php';
            require './PHPMailer/src/PHPMailer.php';
            require './PHPMailer/src/SMTP.php';

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'huytran45690@gmail.com'; // Thay đổi thành địa chỉ email gửi
            $mail->Password = 'obofktkctkwpqtfa'; // Thay đổi thành mật khẩu email gửi
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->isHTML(true);
            $mail->setFrom('huytran45690@gmail.com','HE THONG THANH TOAN'); // Thay đổi theo địa chỉ email và tên của bạn
            $mail->addAddress($mssv.'@st.vlute.edu.vn'); // Thay đổi thành địa chỉ email người nhận thông báo
            $mail->Subject = 'Hoàn thành học phí';
            // Tạo nội dung email
            $emailContent = "Sinh viên $tensv (MSSV: $mssv) đã hoàn thành học phí. Chi tiết:<br>
            Tổng tiền: $tong_tien<br>
            Số tiền đã thanh toán: $sotienthanhtoan<br>
            Số dư: $sodu<br>
            Tên ngân hàng: $tennganhang<br>
            Số tài khoản: $soTaikhoan<br>
            Hình thức thanh toán: $hinhThucThanhToan<br>
            Hẹn ngày giờ thanh toán tiền thừa: $scheduledTime";
            $mail->Body = $emailContent;
            try {
                $mail->send();
                echo 'Email thông báo đã được gửi đi.';
            } catch (Exception $e) {
                echo 'Lỗi khi gửi email: ' . $mail->ErrorInfo;
            }
            ?>
        <p>Bạn đã chuyển dư: <?php echo $sodu; ?> </p>
    <?php endif; ?>
    <?php
        // Determine the registration status based on $sodu
        
    ?>
    
<?php endif; ?>
<a href="thanhtoanadmin.php" class="option-btn" style="width: 10%; float: right;">Trở lại</a>

</body>
</html>

<?php
$conn->close();
?>
