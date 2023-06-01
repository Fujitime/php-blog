<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include "../koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Gunakan prepared statement untuk mencegah SQL Injection
    $query = "SELECT * FROM posts WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_array($result);

    if ($data) {
        $title = $data['title'];
        $content = $data['content'];
        $file_name = $data['file_name'];
    } else {
        echo "Data post tidak ditemukan.";
        exit();
    }
} else {
    echo "ID post tidak ditemukan.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $file_changed = false;

 
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    
    // Check if a new file is uploaded
    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];

        // Periksa jenis file yang diizinkan
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        if (in_array($file_extension, $allowed_extensions)) {
            // Periksa ukuran file yang diunggah
            $max_file_size = 2 * 1024 * 1024; // 2MB

            if ($file_size <= $max_file_size) {
                // Sanitasi nama file
                $file_name = uniqid('', true) . '.' . $file_extension;
                $file_path = realpath('../uploads/img/') . DIRECTORY_SEPARATOR . $file_name;

                // Validasi direktori tujuan
                if (!is_dir(realpath('../uploads/img/'))) {
                    mkdir(realpath('../uploads/img/'), 0755, true);
                }

                // Pindahkan file yang diunggah ke direktori yang ditentukan
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $file_changed = true;
                } else {
                    echo "<script>alert('Gagal mengunggah file.');</script>";
                }
            } else {
                echo "<script>alert('Ukuran file terlalu besar. Maksimal 2MB');</script>";
            }
        } else {
            echo "<script>alert('Jenis file tidak diizinkan.');</script>";
        }
    }

    // Gunakan prepared statement untuk mencegah SQL Injection
    $update_query = "UPDATE posts SET title=?, content=?";
    $params = array($title, $content);

    if ($file_changed) {
        $update_query .= ", file_name=?";
        $params[] = $file_name;
    }

    $update_query .= " WHERE id=?";
    $params[] = $id;

    $update_stmt = mysqli_prepare($koneksi, $update_query);
mysqli_stmt_bind_param($update_stmt, str_repeat("s", count($params)), ...$params);
$update_result = mysqli_stmt_execute($update_stmt);
if ($update_result) {
    echo "<script>alert('Data berhasil diupdate.'); window.location.href = 'index.php';</script>";
    exit();
} else {
    echo "<script>alert('Gagal mengupdate data: " . mysqli_error($koneksi) . "');</script>";
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="../assets/css/style.css" rel="stylesheet">
    <title>Edit Blog</title>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
        
    </script>
</head>
<body class="bg-gray-100">
    <h1 class="text-3xl font-bold text-center mt-8">Edit Blog</h1>

    <div class="max-w-md mx-auto bg-white rounded p-8 mt-8">
        <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-4">
                <label for="file" class="block font-medium text-gray-700">Image:</label>
                <input type="file" id="file" name="file" class="border border-gray-300 px-3 py-2 mt-1 rounded-md w-full" onchange="previewImage(event)">
            </div>
            <div class="mb-4">
                <label for="preview" class="block font-medium text-gray-700">Preview:</label>
                <img id="preview" src="../uploads/img/<?php echo $data['file_name']; ?>" alt="Preview" width="200" style="max-width: 200px;">
            </div>
            <div class="mb-4">
                <label for="title" class="block font-medium text-gray-700">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required class="border border-gray-300 px-3 py-2 mt-1 rounded-md w-full">
            </div>
            <div class="mb-4">
                <label for="content" class="block font-medium text-gray-700">Content:</label>
                <textarea id="content" name="content" required class="border border-gray-300 px-3 py-2 mt-1 rounded-md w-full"><?php echo htmlspecialchars($content); ?></textarea>
            </div>
           
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <a href="index.php" class="block text-blue-500 hover:text-blue-600 mt-4 text-center">&larr; Kembali ke List</a>
</body>
</html>
