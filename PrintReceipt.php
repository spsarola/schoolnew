<?php
include("Include.php");
echo "<title>$APPLICATIONNAME</title>"
?>
<style>
*{font-family:verdana; margin:0 auto;}
table.responsive {  border:1px solid #2e2e2e; font-size:11px; background: whitesmoke;  border-collapse: collapse;  width:99%;  margin:0 auto;  margin-bottom:10px; margin-top:10px;}
//table.responsive tr:hover {   background: lightsteelblue !important;}
table.responsive th, table.responsive td {  border: 1px #2e2e2e solid;  padding: 0.2em;  padding-left:10px; vertical-align:top}
table.responsive th {  background: gainsboro;  text-align: left;}
table.responsive caption {  margin-left: inherit;  margin-right: inherit;}
table.responsive tr:hover{background-color:#ddd;}
</style>
<?php

$query1="Select Width,HeaderContent from printoption,header,masterentry where
	printoption.HeaderId=header.HeaderId and
	printoption.PrintCategory=masterentry.MasterEntryId and
	MasterEntryName='PrintCategory' and MasterEntryValue='Fee Receipt' ";
$check1=mysqli_query($CONNECTION,$query1);
$count1=mysqli_num_rows($check1);
if($count1>0)
{
	$row1=mysqli_fetch_array($check1);
	$Width=$row1['Width']."cm";
	$HeaderContent=$row1['HeaderContent'];
}	
else
{
	$Width=$DEFAULTPRINTSIZE."cm";
	$HeaderContent="";
}
$TransactionId=$_GET['TransactionId'];
$query="select TransactionDate,Token,StudentName,TransactionSession,FatherName,MotherName,Mobile,ClassName,SectionName from transaction,studentfee,registration,admission,class,section where
	TransactionId='$TransactionId' and
	TransactionHead='Fee' and
	TransactionStatus='Active' and
	transaction.TransactionHeadId=admission.AdmissionId and
	studentfee.AdmissionId=admission.AdmissionId and
	registration.RegistrationId=admission.RegistrationId and
	studentfee.SectionId=section.SectionId and 
	class.ClassId=section.ClassId and
	ClassStatus='Active' and
	SectionStatus='Active'";
$check=mysqli_query($CONNECTION,$query);
$count=mysqli_num_rows($check);
if($TransactionId=="" || $count==0)
{
	$Message="This is not a valid link!!";
	$Type="error";
	SetNotification($Message,$Type);
	header("Location:Payment");
}
else
{
	$row=mysqli_fetch_array($check);
	$Token=$row['Token'];
	$TransactionDate=date("d M Y,h:ia",$row['TransactionDate']);
	$StudentName=$row['StudentName'];
	$Session=$row['TransactionSession'];
	$FatherName=$row['FatherName'];
	$MotherName=$row['MotherName'];
	$Mobile=$row['Mobile'];
	$ClassName=$row['ClassName'];
	$SectionName=$row['SectionName'];
	
	echo "<div style=\"width:$Width;\">";
	$Print=$HeaderContent;
	$Print.="<p style=\"float:right;margin:10px; font-weight:bold; font-size:11px;\">Date Time of Payment : $TransactionDate</p>";
	$Print.="<table class=responsive>
		<Tr>
		<th>Student Name</th><td>$StudentName</td>
		<th>Father Name</th><td>$FatherName</td>
		</tr>
		<Tr>
		<th>Mother Name</th><td>$MotherName</td>
		<th>Mobile</th><td>$Mobile</td>
		</tr>
		<th>Class</th><td>$ClassName</td>
		<th>Section</th><td>$SectionName</td>
		</tr>";
	$Print.="<table class=responsive><tr>
		<Th>Fee Type</th><th>Amount</th></tr>";
	$query2="Select MasterEntryValue as FeeName,feepayment.Amount as Paid from fee,feepayment,masterentry where
		feepayment.Token='$Token' and
		fee.FeeId=feepayment.FeeType and
		fee.FeeType=masterentry.MasterEntryId ";
	$check2=mysqli_query($CONNECTION,$query2);
	$SumAmount=0;
	while($row2=mysqli_fetch_array($check2))
	{
		$FeeName=$row2['FeeName'];
		$Amount=$row2['Paid'];
		$Print.="<Tr><Td>$FeeName</Td><Td>$Amount $CURRENCY</td></tr>";
		$SumAmount+=$Amount;
	}	
	$Print.="<tr><th>Total</th><th>$SumAmount $CURRENCY</th></table>";
	$Print.="<p style=\"float:right;margin:10px; font-weight:bold; font-size:11px;\">Sign</p>";
	echo $Print;
	echo "</div>";
}
?>