<?php
include "header.php";
unset ($_SESSION["user"]);
unset ($_SESSION["admin"]);
header("location:agenda.php");

include "footer.php";
?>