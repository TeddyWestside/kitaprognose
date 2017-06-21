<?php
require "Datenbereitstellung.php";

include "../connection.php";

$cl_datenbereitstellung = new Datenbereitstellung();
$cl_datenbereitstellung->aktualisiere_datenbestand();
?>
