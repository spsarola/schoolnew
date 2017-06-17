<?php
include("Include.php");
IsLoggedIn();
$Action=$_POST['Action'];
if($Action=="")
header("Location:LogIn");
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="ManageHouse")
{	
	$HouseName=$_POST['HouseName'];
	$HouseId=$_POST['HouseId'];
	$Session=$CURRENTSESSION;
	
	if($HouseId!="")
	$Update=" and HouseId!='$HouseId' ";
	$query1="select * from house where HouseName='$HouseName' and Session='$Session' $Update ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0)
	{
		while($row1=mysqli_fetch_array($check1))
		{
		$OldHouseStatus=$row1['HouseStatus'];
		if($OldHouseStatus=="Deleted" && $count1>0)
		$count1=0;
		else
		$count1++;
		}
	}
	
	if($HouseId!="")
	{
		$addupdate="updated";
		$query2="select HouseStatus from house where HouseId='$HouseId' and HouseStatus='Active' ";
		$check2=mysqli_query($CONNECTION,$query2);
		$CurrentHouseStatus=mysqli_num_rows($check2);
	}
	else
		$addupdate="added";
	
	if($HouseName=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($HouseId!="" && $CurrentHouseStatus==0)
	{
		$Message="This house is deleted. You cannot update the deleted house!!";
		$Type="error";
	}
	elseif($count1>0)
	{
		$Message="This house is already added!!";
		$Type="error";			
	}
	else
	{	
		$DateTimeStamp=strtotime($Date);
		if($HouseId=="")
		$query="insert into house(HouseName,HouseStatus,Session,DOE,DOEUsername) values('$HouseName','Active','$Session','$DateTimeStamp','$USERNAME') ";
		else
		$query="update house set HouseName='$HouseName' where HouseId='$HouseId' and HouseStatus='Active' ";
		
		mysqli_query($CONNECTION,$query);
		$Message="House $addupdate successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	if($HouseId=="")
	header("Location:ManageHouse");	
	else
	header("Location:ManageHouse/UpdateHouse/$HouseId");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="HouseStudentAssign")
{	
	$HouseId=$_POST['HouseId'];
	$Student=$_POST['Student'];
	$SectionId=$_POST['SectionId'];
	
	$query="select HouseId,Student from house where HouseId='$HouseId' and HouseStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	
	if($HouseId=="" || $Student=="")
	{
		$Message="All the fields are mandatory!!";
		$Type=error;
	}
	elseif($count==0)
	{
		$Message="This is not a valid House Id!!";
		$Type=error;
	}
	else
	{
	
		$query1="select AdmissionId from studentfee where Session='$CURRENTSESSION' and Section='$SectionId' ";
		$check1=mysqli_query($CONNECTION,$query1);
		while($row1=mysqli_fetch_array($check1))
			$AdmissionIdArray[]=$row1['AdmissionId'];
			
			
		
	}
}
///////////////////////////////////////////////////////////////////////////////////////////
else
header("location:DashBoard");
?>