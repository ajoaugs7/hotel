<?php	
include("dbcon.php");
$c_id = $_GET['id'];
$sql = "update category set status=2 where c_id=".$c_id;

$conn->query($sql);

 header('location:viewcategory.php');
?>