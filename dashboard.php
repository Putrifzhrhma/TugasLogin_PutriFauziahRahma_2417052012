<?php
session_start();

require 'koneksi.php';

if (!isset($_SESSION['nama'])) {
    header("Location: auth.php");
    exit();
}

if (isset($_GET['hapus'])) {

    if ($_SESSION['nama'] != 'admin') {
        header("Location: dashboard.php");
        exit();
    }

    $id = $_GET['hapus'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Selamat Datang di Dashboard</h2>

<p>Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</p>

<a href="logout.php">
    <button>Logout</button>
</a>

<hr>

<?php
if ($_SESSION['nama'] == 'admin') {

    $query = mysqli_query($conn, "SELECT id, nama FROM users");
?>

<h3>Manajemen Data User</h3>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Aksi</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($query)) { ?>

    <tr>
        <td><?php echo $row['id']; ?></td>

        <td>
            <?php echo htmlspecialchars($row['nama']); ?>
        </td>

        <td>

            <a href="edit.php?id=<?php echo $row['id']; ?>">
                <button>Edit</button>
            </a>

            <a href="dashboard.php?hapus=<?php echo $row['id']; ?>">
                <button>Hapus</button>
            </a>

        </td>
    </tr>

    <?php } ?>

</table>

<?php } ?>

</body>
</html>