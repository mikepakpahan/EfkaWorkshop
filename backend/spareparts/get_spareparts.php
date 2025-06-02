<?php
require '../config/db.php';

$result = $conn->query("SELECT * FROM spareparts");
$spareparts = [];

while ($row = $result->fetch_assoc()) {
  $spareparts[] = $row;
}

echo json_encode($spareparts);
?>
