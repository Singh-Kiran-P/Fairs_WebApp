<?php
require '../../../server/classes/class.searchFair.php';

$s = new SearchFair();
$msg = $s->getGeoJsonOfAllFairs();
echo json_encode($msg);

