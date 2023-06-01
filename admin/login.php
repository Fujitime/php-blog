<?php
include "../koneksi.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION["username"] = $username;
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Login gagal. Invalid username atau password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="../assets/css/style.css" rel="stylesheet">
        <title>Login</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto flex justify-center items-center h-screen">
        <div class="w-full max-w-sm">
            <h1 class="text-3xl font-bold text-center mb-8">Login</h1>

            <form action="login.php" method="POST" class="bg-white shadow-md rounded px-8 py-6">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                    <input type="text" name="username" id="username" class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:border-blue-500" placeholder="Masukkan username..." required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input type="password" name="password" id="password" class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:border-blue-500" placeholder="Masukkan password..." required>
                </div>
                <a class="inline-block mt-4 border border-blue-500 text-blue-500 px-4 py-2 rounded-lg" href="/">&larr; Back</a>
                <input type="submit" value="Login" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <div class="flex justify-center">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
