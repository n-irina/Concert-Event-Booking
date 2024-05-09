<?php
include "layout.php";
unset ($_SESSION["user"]);
unset ($_SESSION["admin"]);
header("location:agenda.php");
?>