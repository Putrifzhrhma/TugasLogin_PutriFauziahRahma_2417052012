<?php
session_start();

require 'koneksi.php';

if (!isset($_SESSION['nama']) || $_SESSION['nama'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: dashboard.php");
    exit();
}

$user = $result->fetch_assoc();

if (isset($_POST['update'])) {

    $nama = trim($_POST['nama']);
    $password = $_POST['password'];

    if (!empty($nama) && !empty($password)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE users SET nama = ?, password = ? WHERE id = ?");

        $update->bind_param("ssi", $nama, $hashed_password, $id);

        if ($update->execute()) {

            header("Location: dashboard.php");
            exit();
        }

        $update->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>

<h2>Edit Data Pengguna</h2>

<form method="POST">

    <label>Nama Pengguna</label>
    <br>

    <input
        type="text"
        name="nama"
        value="<?php echo htmlspecialchars($user['nama']); ?>"
        required
    >

    <br><br>

    <label>Password Baru</label>
    <br>

    <input
        type="password"
        name="password"
        required
    >

    <br><br>

    <button type="submit" name="update">
        Update
    </button>

</form>

</body>
</html>
