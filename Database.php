<?php
include("Config.php");
$ErrorMessage=$ErrorType=$TableError=$DatabaseError=null;
if(!$CONNECTION)
{
$ErrorMessage="Database not found!! Please fix this error!!!";
$ErrorType=1;
$DatabaseError=1;
}
elseif(mysqli_num_rows(mysqli_query($CONNECTION,"SHOW TABLES LIKE 'tablename'"))!=1 && $ErrorMessage=="") 
{
$ErrorMessage="Primary table not found!! Please fix this error!!!";
$ErrorType=2;
$TableError=1;
}
if($ErrorMessage=="")
{
	$query201="select TableName from tablename ";
	$check201=mysqli_query($CONNECTION,$query201);
	while($row201=mysqli_fetch_array($check201))
	{
		$TableName=$row201['TableName'];
		if(mysqli_num_rows(mysqli_query($CONNECTION,"SHOW TABLES LIKE '$TableName'"))!=1)
		{
			$ErrorMessage="$TableName table not found!! Please fix this error!!!";
			$ErrorType=3;
			$SecondaryTableError=1;
			break;
		}
	}
}
if($PageName=="Installer" && $DatabaseError!=1 && $TableError!=1 && $SecondaryTableError!=1)
header("Location:LogIn");
?>