<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
</head>
<body>
    <script>
        alert('Terimakasih telah berkunjung');
        document.location.href='login.php';
    </script>
</body>
</html>
