<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id === false) {
        echo "ID post tidak valid.";
        exit();
    }

    // Gunakan parameterisasi untuk mencegah SQL Injection
    $query = "SELECT * FROM posts WHERE id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $title = htmlspecialchars($data['title']);
        $content = htmlspecialchars($data['content']);
        $image = $data['file_name'];
    } else {
        echo "Data post tidak ditemukan.";
        exit();
    }
} else {
    echo "ID post tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo $title; ?></title>
    <link href="./assets/css/style.css" rel="stylesheet">
    <style>
        .post-body {
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <?php include "./templates/header.php"; ?>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold"><?php echo $title; ?></h1>

        <?php if (!empty($image)) : ?>
            <img src="uploads/img/<?php echo $image; ?>" width="500" alt="Gambar Postingan" class="mt-4 mb-8 max-w-full mx-auto">
        <?php endif; ?>

        <p class="post-body mt-4 text-gray-700"><?php echo $content; ?></p>

        <a class="inline-block mt-4 border border-blue-500 text-blue-500 px-4 py-2 rounded-lg" href="index.php">&larr; Back</a>
    </div>
    <?php include "./templates/footer.php"; ?>
</body>
</html>
