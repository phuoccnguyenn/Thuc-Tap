<header class="header">

   <div class="flex">

      <a href="home.php" class="logo">
      <img src="../images/logo.png" alt="logo lỗi" width="100" height="100">   
      </a>

      <nav class="navbar">
         <a href="home.php">Trang chủ</a>
         <a href="about.php">Giới thiệu</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="#" class="fas fa-search"></a>
         <div id="user-btn" class="fas fa-user"></div>


      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `usersv` WHERE id = ?");
            $select_profile->execute([$sinhvien_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="../uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <p><?= $fetch_profile['tensv']; ?></p>
         <a href="update_profile_sv.php" class="btn">Cập nhật hồ sơ</a>
         <a href="logout.php" class="delete-btn">Đăng xuất</a>

      </div>

   </div>

</header>