<?php
include "connection.php";
$sql = "SELECT * FROM `currency_rate`";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $key = $row['code'];
        $value = $row['rate'];
      $rate["$key"] = $value;
    }
  } else {
    echo "0 results";
  }
$_SESSION['rate'] = $rate;

$response = json_encode($rate);
echo $response;
?>