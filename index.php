<!DOCTYPE html>
<html>

<head>
    <title>NativuBlogs - Halaman utama</title>
    <link href="./assets/css/style.css" rel="stylesheet">
</head>

<body>
    <?php include "./templates/header.php"; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Blogs</h1>

        <?php
        include "koneksi.php";

        $query = "SELECT * FROM posts ORDER BY published_at DESC";

        $result = mysqli_query($koneksi, $query);

        if (!$result) {
            echo "Error: " . mysqli_error($koneksi);
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $title = htmlspecialchars($row['title']);
                $content = htmlspecialchars($row['content']);
                $image = $row['file_name'];

                $title = implode(' ', array_slice(explode(' ', $title), 0, 10));
                $words = explode(' ', $content);
                if (count($words) > 10) {
                    $words = array_slice($words, 0, 10);
                    $content = implode(' ', $words) . " ...";
                } else {
                    $content = implode(' ', $words);
                }

        ?>

                    <div class="bg-white shadow rounded-lg px-6 py-4 mb-4">
                        <h2 class="text-xl font-bold mb-2"><?php echo $title; ?></h2>

                        <?php if (!empty($image)) : ?>
                        <img src="uploads/img/<?php echo $image; ?>" width="300" alt="Gambar Postingan" class="mb-4 max-w-full h-auto">
                    <?php endif; ?>
                    <p class="text-gray-500 italic text-xs "><?php echo date('d M Y', strtotime($row['published_at'])); ?></p>

                    <p class="truncate mb-4"><?php echo $content; ?></p>

                    <a href="post.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">Baca Selengkapnya</a>
                </div>


        <?php
            }
        }
        mysqli_close($koneksi);
        ?>

    </div>
</body>

</html>