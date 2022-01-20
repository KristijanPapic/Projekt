
<?php
include "connection.php";
$sql = "DELETE FROM currency_rate;";
mysqli_query($conn, $sql) or die(mysqli_error($conn));
$sql = "DELETE FROM rate_update_time;";
mysqli_query($conn, $sql) or die(mysqli_error($conn));
$time = $_POST['update_time'];
$sql = "INSERT INTO `rate_update_time`(`time`) VALUES ($time)";
mysqli_query($conn, $sql) or die(mysqli_error($conn));

foreach ($_POST['rates'] as $key => $value){
$sql = "INSERT INTO `currency_rate`(`code`, `rate`) VALUES ('$key',$value)";
mysqli_query($conn, $sql) or die(mysqli_error($conn));
}


?>

