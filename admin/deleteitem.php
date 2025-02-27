<?php	
include("dbcon.php");
$i_id = $_GET['id'];
$sql = "update item set status=2 where i_id=".$i_id;

$conn->query($sql);

 header('location:viewitem.php');
?>