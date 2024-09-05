<?php
@include '../config.php'; // Kết nối đến CSDL

// Check if the form is submitted and the update_dangkyhp button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_dangkyhp'])) {
    $iddk = $_POST['iddk'];
    $idhp = $_POST['idhp'];
    $idhocky = $_POST['idhocky'];
    $idng = $_POST['idng'];
    $idtrt = 2;

    // Prepare and execute the SQL update query
    $stmt = $conn->prepare("UPDATE dangkyhp SET idhp = :idhp, idhocky = :idhocky, idng = :idng, idtrt = :idtrt WHERE iddk = :iddk");
    $stmt->bindParam(':idhp', $idhp);
    $stmt->bindParam(':idhocky', $idhocky);
    $stmt->bindParam(':idng', $idng);
    $stmt->bindParam(':idtrt', $idtrt);
    $stmt->bindParam(':iddk', $iddk);

    if ($stmt->execute()) {
        // Update successful, redirect back to student_dkhp.php
        header('Location: student_dkhp.php');
        exit();
    } else {
        echo "Lỗi: " . $stmt->errorInfo()[2];
    }
}
?>
