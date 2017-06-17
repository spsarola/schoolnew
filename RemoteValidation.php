<?php
include("Include.php");
$Action=$_GET['Action'];
if($Action=="MobileValidation")
{
$Id=$_GET['Id'];
$request = $_REQUEST[$Id];
if(!is_numeric($request) || strlen($request)!=$MOBILENUMBERDIGIT)
$valid='false';
else
$valid='true';
echo $valid;
}
elseif($Action=="LandlineValidation")
{
$Id=$_GET['Id'];
$request = $_REQUEST[$Id];
if(!is_numeric($request) || strlen($request)!=$LANDLINENUMBERDIGIT)
$valid='false';
else
$valid='true';
echo $valid;
}
elseif($Action=="EmailValidation")
{
$Id=$_GET['Id'];
$request = $_REQUEST[$Id];
if (!filter_var($request, FILTER_VALIDATE_EMAIL))
$valid='false';
else
$valid='true';
echo $valid;
}
elseif($Action=="IsNumeric")
{
$Id=$_GET['Id'];
$request = $_REQUEST[$Id];
if(!is_numeric($request))
$valid='false';
else
$valid='true';
echo $valid;
}
elseif($Action=="IsAmountWithoutZero")
{
$Id=$_GET['Id'];
$request = $_REQUEST[$Id];
if(!is_numeric($request) || $request<=0)
$valid='false';
else
$valid='true';
echo $valid;
}
elseif($Action=="IsAmountWithZero")
{
$Id=$_GET['Id'];
$request = $_REQUEST[$Id];
if(!is_numeric($request) || $request<0)
$valid='false';
else
$valid='true';
echo $valid;
}
elseif($Action=="IsValidAdmissionNo")
{
$Id=$_GET['Id'];
$request = $_REQUEST[$Id];
$query="Select AdmissionNo from studentfee where AdmissionNo='$request' ";
$check=mysqli_query($CONNECTION,$query);
$count=mysqli_num_rows($check);
if($count>0)
$valid='false';
else
$valid='true';
echo $valid;
}
elseif($Action=="MarksValidation")
{
$Id=$_GET['Id'];
$MM=$_GET['MM'];
$request = $_REQUEST[$Id];
if(!is_numeric($request) || $request<0 || $request>$MM)
$valid='false';
else
$valid='true';
echo $valid;
}
?>