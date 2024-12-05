<?php
// logout.php

session_start();

// Hapus semua data sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Arahkan pengguna ke halaman login
header('Location: ../login.php');
exit;
?>
