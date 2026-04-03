<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: /fotos/visualizar_fotos.php");
    exit;
} else {
    header("Location: /login/login.php");
    exit;
}
?>
