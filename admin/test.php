<?php
@include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('Location:../admin_login.php');
    exit();
};

try {
    $conn = new PDO($db_name, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Truy vấn dữ liệu sotien của các học phần
    $sql = "SELECT hp.tenhp, hp.tinchi
            FROM hocphan hp";
    $result = $conn->query($sql);

    $data = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[$row['tenhp']] = $row['tinchi'];
    }
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="../images/logo.png">
    <link rel="stylesheet" href="../css/admin_style.css">
    <title>Biểu đồ cột - Thống kê học phần</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1 style="color: red; text-align: center; font-size: 50px; margin: 10px">Biểu đồ cột - Thống kê học phần</h1>
    <div style="width: 80%; margin: 0 auto;">
        <canvas id="myChart"></canvas>
    </div>
    <script>
        var data = <?php echo json_encode($data); ?>;
        var labels = Object.keys(data);
        var values = Object.values(data);

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tín chỉ',
                    data: values,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
<a href="admin_page.php" class="option-btn" style="width: 10%; float: right;">Trở lại</a>
</body>
</html>
