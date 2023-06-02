<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}


include "../koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $published_at = date('Y-m-d H:i:s');
    $user_id = 1;

    if (empty($title) || empty($content)) {
        echo "<script>alert('Judul dan konten harus diisi.');</script>";
    } else {

        $title = trim($_POST["title"]);
        $content = trim($_POST["content"]);
        
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file_name = $_FILES['file']['name'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_size = $_FILES['file']['size'];
            $file_type = $_FILES['file']['type'];

            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if (in_array($file_extension, $allowed_extensions)) {
                $max_file_size = 2 * 1024 * 1024; 
                if ($file_size <= $max_file_size) {
                    $file_name = uniqid('', true) . '.' . $file_extension;
                    $file_path = realpath('../uploads/img/') . DIRECTORY_SEPARATOR . $file_name;

                    if (!is_dir(realpath('../uploads/img/'))) {
                        mkdir(realpath('../uploads/img/'), 0755, true);
                    }
                    if (strlen($file_name) > 255) {
                        echo "<script>alert('Nama file terlalu panjang.');</script>";
                    } elseif (file_exists($file_path)) {
                        echo "<script>alert('File dengan nama yang sama sudah ada.');</script>";
                    } else {
                        // Pindahkan file yang diunggah ke direktori yang ditentukan
                        if (move_uploaded_file($file_tmp, $file_path)) {
                            // Simpan informasi file ke database
                            $query = "INSERT INTO posts (title, content, published_at, user_id, file_name) VALUES (?, ?, ?, ?, ?)";
                            $stmt = mysqli_prepare($koneksi, $query);
                            mysqli_stmt_bind_param($stmt, "sssis", $title, $content, $published_at, $user_id, $file_name);

                            if (mysqli_stmt_execute($stmt)) {
                                header("Location: index.php");
                                exit();
                            } else {
                                echo "<script>alert('Gagal menambahkan data: " . mysqli_error($koneksi) . "');</script>";
                            }

                            mysqli_stmt_close($stmt);
                        } else {
                            echo "<script>alert('Gagal mengunggah file.');</script>";
                        }
                    }
                } else {
                    echo "<script>alert('Ukuran file terlalu besar. Maksimal 2MB');</script>";
                }
                       } else {
                echo "<script>alert('Jenis file tidak diizinkan.');</script>";
            }
        } else {
            echo "<script>alert('Terjadi kesalahan saat mengunggah file.');</script>";
        }
    }
}
?>

<link href="../assets/css/style.css" rel="stylesheet">

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Tambah</title>
    <script>
        const previewImage = (event) => {
            const reader = new FileReader();
            reader.onload = () => {
                const output = document.getElementById('preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>
<body class="bg-gray-100">
    <h1 class="text-3xl font-bold text-center mt-8">Tambah Data</h1>

    <form method="POST" action="" enctype="multipart/form-data" class="max-w-md mx-auto bg-white rounded p-8 mt-8">
        <label for="file" class="block font-medium text-gray-700">Pilih file untuk diunggah:</label>
        <input type="file" name="file" id="file" required class="border border-gray-300 px-3 py-2 mt-1 rounded-md w-full" onchange="previewImage(event)">
        <br>
        <div class="mb-4">
            <label for="preview" class="block font-medium text-gray-700">Preview:</label>
            <img id="preview" src="" alt="input gambar 'jpg', 'jpeg', 'png', 'gif' " width="200" style="max-width: 200px;">
        </div>
        <label for="title" class="block font-medium text-gray-700 mt-4">Judul:</label>
        <input type="text" name="title" id="title" class="border border-gray-300 px-3 py-2 mt-1 rounded-md w-full" required>
        <br>
        <label for="content" class="block font-medium text-gray-700 mt-4">Isi Konten:</label>
        <textarea name="content" id="content" class="border border-gray-300 px-3 py-2 mt-1 rounded-md w-full" required></textarea>
        <br>
        <input type="submit" value="Submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded mt-4">
    </form>

    <a href="index.php" class="block text-blue-500 hover:text-blue-600 mt-4 text-center">&larr; Kembali ke Daftar</a>
</body>
</html>
