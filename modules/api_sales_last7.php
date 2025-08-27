<?php
require '../config/db.php';
$labels = []; $totals = [];
for ($i=6;$i>=0;$i--) {
    $day = date('Y-m-d', strtotime("-$i days"));
    $res = $conn->query("SELECT IFNULL(SUM(total),0) as s FROM ventas WHERE DATE(fecha)='$day'")->fetch_assoc();
    $labels[] = date('d/m', strtotime($day));
    $totals[] = floatval($res['s']);
}
header('Content-Type: application/json');
echo json_encode(['labels'=>$labels,'totals'=>$totals]);
