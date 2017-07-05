<?php
require "Datenbereitstellung.php";

include "../connection.php";

$gr_datenbereitstellung = new Datenbereitstellung();
$gr_datenbereitstellung->aktualisiere_datenbestand();
?>
