<?php
session_start();
session_unset();
session_destroy();
setcookie("sess", "", time() - 3600, '/', ".gestaoambiental.com.br", false, true);
header("Location: /login/login.php");
exit;
?>
