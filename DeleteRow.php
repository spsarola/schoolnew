<?php
include("Config.php");
$id=$_POST['id'];
$Action=$_POST['Action'];
if($Action=="test")
{
$a="delete from feepayment where FeePaymentId='$id' ";
mysqli_query($CONNECTION,$a);
}
elseif($Action=="ListBook")
{
$a="delete from listbook where ListBookId='$id' ";
mysqli_query($CONNECTION,$a);
}
?>