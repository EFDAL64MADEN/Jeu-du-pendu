<?php

session_start();
unset($_SESSION['mot']);
header('Location: index.php');

?>