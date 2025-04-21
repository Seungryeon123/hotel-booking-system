<?php
session_start();

// Hapuskan sesi admin
session_unset();
session_destroy();

// Alihkan pengguna ke halaman log masuk admin
header('Location: login_admin.php');
exit();
?>
