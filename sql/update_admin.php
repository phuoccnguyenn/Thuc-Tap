<?php
function updateAdminProfile($conn, $admin_id, $tk, $image, $image_tmp_name, $old_image, $old_pass, $new_pass, $confirm_pass)
{
    try {
        $tk = filter_var($tk, FILTER_SANITIZE_STRING);
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_folder = '../uploaded_img/'.$image;

        // Thực hiện câu truy vấn SQL kiểm tra tài khoản trùng
        $check_username = $conn->prepare("SELECT * FROM admin WHERE tk = ? AND id <> ?");
        $check_username->execute([$tk, $admin_id]);
        if ($check_username->rowCount() > 0) {
            return array('success' => false, 'message' => 'Tên tài khoản đã được sử dụng.');
        }

        // Thực hiện câu truy vấn SQL cập nhật thông tin admin
        $stmt = $conn->prepare("UPDATE admin SET tk = ?, image = ? WHERE id = ?");
        $stmt->execute([$tk, $image, $admin_id]);

        if (!empty($image)) {
            if ($image_size > 2000000) {
                return array('success' => false, 'message' => 'Ảnh đại diện quá lớn.');
            } else {
                $stmt = $conn->prepare("UPDATE admin SET image = ? WHERE id = ?");
                $stmt->execute([$image, $admin_id]);
                if ($stmt) {
                    move_uploaded_file($image_tmp_name, $image_folder);
                    unlink('../uploaded_img/'.$old_image);
                } else {
                    return array('success' => false, 'message' => 'Cập nhật ảnh đại diện thất bại.');
                }
            }
        }

        // Thực hiện cập nhật mật khẩu nếu có
        if (!empty($new_pass) && !empty($confirm_pass)) {
            if ($new_pass !== $confirm_pass) {
                return array('success' => false, 'message' => 'Mật khẩu mới và xác nhận mật khẩu không khớp.');
            } else {
                // Kiểm tra mật khẩu cũ
                $fetch_pass = $conn->prepare("SELECT password FROM admin WHERE id = ?");
                $fetch_pass->execute([$admin_id]);
                $row = $fetch_pass->fetch();
                if ($row['password'] !== md5($old_pass)) {
                    return array('success' => false, 'message' => 'Mật khẩu cũ nhập sai.');
                } else {
                    // Thực hiện cập nhật mật khẩu
                    $update_pass_query = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
                    $update_pass_query->execute([md5($confirm_pass), $admin_id]);
                }
            }
        }

        return array('success' => true, 'message' => 'Cập nhật thông tin admin thành công.');
    } catch (PDOException $e) {
        return array('success' => false, 'message' => 'Có lỗi xảy ra khi cập nhật thông tin admin: ' . $e->getMessage());
    }
}

?>
