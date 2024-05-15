<?php
session_start();
session_unset();
session_destroy();
header("Location: https://trinhcuti204.000webhostapp.com/Login");
exit;
?>
