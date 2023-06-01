<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include "../koneksi.php";
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Validasi bahwa delete_id adalah angka
    if (!ctype_digit($delete_id)) {
        echo "<script>alert('ID yang dihapus tidak valid.');</script>";
        exit();
    }

    // Mengambil informasi file sebelum data dihapus
    $file_query = "SELECT file_name FROM posts WHERE id = ?";
    $file_stmt = mysqli_prepare($koneksi, $file_query);
    mysqli_stmt_bind_param($file_stmt, "i", $delete_id);
    mysqli_stmt_execute($file_stmt);
    mysqli_stmt_bind_result($file_stmt, $file_name);
    mysqli_stmt_fetch($file_stmt);
    mysqli_stmt_close($file_stmt);

    // Menghapus data dari database
    $delete_query = "DELETE FROM posts WHERE id = ?";
    $delete_stmt = mysqli_prepare($koneksi, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $delete_id);
    $delete_result = mysqli_stmt_execute($delete_stmt);

    if ($delete_result) {
        // Hapus file jika data berhasil dihapus
        $file_path = realpath('../uploads/img/') . DIRECTORY_SEPARATOR . $file_name;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        echo "<script>alert('Data berhasil dihapus.');</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');</script>";
    }
}

$no = 1;
$data = mysqli_query($koneksi, "SELECT * FROM posts");
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="../assets/css/style.css" rel="stylesheet">
    <title>List</title>
</head>
<body class="bg-gray-100">
    <h1 class="text-3xl font-bold text-center mt-8">Daftar List</h1>
    <div class="flex justify-center gap-5 ">
        <a href="tambah.php">
            <button class="block  bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded mt-2">Tambah Data +</button>
        </a>
        <a href="logout.php">
            <button class="block bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded mt-2">Logout</button>
        </a>
    </div>
    <table class="mx-auto my-8">
        <thead>
            <tr>
                <th class="border px-4 py-2">No</th>
                <th class="border px-4 py-2">Judul</th>
                <th class="border px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($d = mysqli_fetch_array($data)) { ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo $no++; ?></td>
                    <td class="border px-4 py-2"><?php echo $d['title']; ?></td>
                    <td class="border px-4 py-2">
                    <div class="flex justify-center gap-2 ">
                        <a href="edit.php?id=<?php echo $d['id']; ?>">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded mr-2">EDIT</button>
                        </a>
                        <a href="?delete_id=<?php echo $d['id']; ?>" onclick="return confirm('Yakin Dihapus!?')">
                            <button class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">HAPUS</button>
                        </a>
            </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

   
    
</body>
</html>

