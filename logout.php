<?php
// Oturumu sonlandır ve çıkış yap
session_start();
session_unset();
session_destroy();
header('Location: index.php');
exit;
?>
