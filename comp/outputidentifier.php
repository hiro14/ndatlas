<?php
require('nachtzug.php');
$tooyoung = mysqli_query($conn, "SELECT querykey FROM `query_id`"); //需要优化 mysql 代码，提高效率
$identifier_list = array();
while ($toosimple = mysqli_fetch_assoc($tooyoung)) {
  $identifier_list[] = $toosimple['querykey'];
}
?>