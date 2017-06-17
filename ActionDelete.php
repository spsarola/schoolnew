<?php
include("Include.php");
IsLoggedIn();
$Action=$_POST['Action'];
$RandomNumber=$_POST['RandomNumber'];
if($Action=="")
header("Location:LogIn");
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteCall")
{	
	$CallId=$_POST['CallId'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select CallStatus from calling where CallId='$CallId' ");
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$CallStatus=$row['CallStatus'];
	}
	$query2="select Count(FollowUpId) from followup where FollowUpUniqueId='$CallId' and FollowUpStatus='Active' and FollowUpType='Call'";
	
	$check2=mysqli_query($CONNECTION,$query2);
	while($row2=mysqli_fetch_array($check2))
	$CallCount+=$row2['Count(FollowUpId)'];
	
	if($CallId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($count2>0)
	{
		$Message="This call has some follow ups. Delete them first!!";
		$Type="error";
	}
	elseif($CallStatus!='Active' )
	{
		$Message="This call cannot be deleted!!";
		$Type="error";
	}	
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update calling set CallStatus='Deleted',DOD='$Date' where CallId='$CallId' ";
		mysqli_query($CONNECTION,$query);
		$Message="Call Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:Call");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteOCall")
{	
	$CallId=$_POST['CallId'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select CallStatus from ocalling where OCallId='$CallId' ");
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$CallStatus=$row['CallStatus'];
	}
	$query2="select Count(FollowUpId) from followup where FollowUpUniqueId='$CallId' and FollowUpStatus='Active' and FollowUpType='OCall'";
	
	$check2=mysqli_query($CONNECTION,$query2);
	while($row2=mysqli_fetch_array($check2))
	$CallCount+=$row2['Count(FollowUpId)'];
	
	if($CallId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($count2>0)
	{
		$Message="This call has some follow ups. Delete them first!!";
		$Type="error";
	}
	elseif($CallStatus!='Active' )
	{
		$Message="This call cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update Ocalling set CallStatus='Deleted',DOD='$Date' where OCallId='$CallId' ";
		mysqli_query($CONNECTION,$query);
		$Message="Call Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:OCall");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteFollowUp")
{	
	$FollowUpId=$_POST['FollowUpId'];
	$Password=$_POST['Password'];
	$FollowUpType=$_POST['FollowUpType'];
	$FollowUpUniqueId=$_POST['FollowUpUniqueId'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select FollowUpStatus from followup where FollowUpId='$FollowUpId' and FollowUpType='$FollowUpType' and FollowUpUniqueId='$FollowUpUniqueId' ");
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$FollowUpStatus=$row['FollowUpStatus'];
	}
	
	if($FollowUpId=="" || $Password=="" || $FollowUpUniqueId=="" || $FollowUpType=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($FollowUpStatus!='Active' )
	{
		$Message="This follow up cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update followup set FollowUpStatus='Deleted',DOD='$Date' where FollowUpId='$FollowUpId' ";
		mysqli_query($CONNECTION,$query);
		$Message="Follow Up Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:FollowUp/$FollowUpType/$FollowUpUniqueId");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteCalendar")
{	
	$CalendarId=$_POST['CalendarId'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select CalendarStatus from calendar where Username='$USERNAME' and CalendarId='$CalendarId' ");
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$CalendarStatus=$row['CalendarStatus'];
	}
	
	if($CalendarId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($CalendarStatus!='Active' )
	{
		$Message="This calendar cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update calendar set CalendarStatus='Deleted',DOD='$Date',DODUsername='$USERNAME' where CalendarId='$CalendarId' ";
		mysqli_query($CONNECTION,$query);
		$Message="Calendar Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:Calendar");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteCircular")
{	
	$CircularId=$_POST['CircularId'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	if($USERNAME!="masteruser" && $USERNAME!='webmaster')
	$UsernameQuery=" and Username='$USERNAME' ";
	$check=mysqli_query($CONNECTION,"select CircularStatus from circular where CircularId='$CircularId' $UsernameQuery");
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$CircularStatus=$row['CircularStatus'];
	}
	
	if($CircularId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($CircularStatus!='Active' )
	{
		$Message="This circular cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update circular set CircularStatus='Deleted' where CircularId='$CircularId' ";
		mysqli_query($CONNECTION,$query);
		$Message="Circular Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:Circular");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteEnquiry")
{	
	$EnquiryId=$_POST['EnquiryId'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select EnquiryStatus from enquiry where EnquiryId='$EnquiryId' ");
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$EnquiryStatus=$row['EnquiryStatus'];
	}
	$query2="select Count(FollowUpId) from followup where FollowUpUniqueId='$EnquiryId' and FollowUpStatus='Active' and FollowUpType='Enquiry'";
	
	$check2=mysqli_query($CONNECTION,$query2);
	while($row2=mysqli_fetch_array($check2))
	$CallCount+=$row2['Count(FollowUpId)'];
	
	if($EnquiryId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($count2>0)
	{
		$Message="This enquiry has some follow ups. Delete them first!!";
		$Type="error";
	}
	elseif($EnquiryStatus!='Active' )
	{
		$Message="This enquiry cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update enquiry set EnquiryStatus='Deleted',DOD='$Date' where EnquiryId='$EnquiryId' ";
		mysqli_query($CONNECTION,$query);
		$Message="Enquiry Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:Enquiry");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteStudentRegistration")
{	
	$RegistrationId=$_POST['RegistrationId'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select Status from registration where RegistrationId='$RegistrationId' ");
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$Status=$row['Status'];
	}
	$query2="select AdmissionId from admission where RegistrationId='$RegistrationId'";
	
	$check2=mysqli_query($CONNECTION,$query2);
	$count2=mysqli_num_rows($check2);
	
	if($RegistrationId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($count2>0)
	{
		$Message="This registration has already confirmed for admission. It cannot be deleted!!";
		$Type="error";
	}
	elseif($Status=='Deleted' )
	{
		$Message="This registration cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update registration set Status='Deleted',DOD='$Date',DODUsername='$USERNAME' where RegistrationId='$RegistrationId' ";
		mysqli_query($CONNECTION,$query);
		$Message="Registration Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:Registration");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteDRRegister")
{	
	$Id=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select DRStatus,DRType from drregister where Id='$Id' ");
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$DRStatus=$row['DRStatus'];
		$DRType=$row['DRType'];
	}
	
	if($Id=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($DRStatus!='Active' )
	{
		$Message="This list cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update drregister set DRStatus='Deleted',DOD='$Date',DODUsername='$USERNAME' where Id='$Id' ";
		mysqli_query($CONNECTION,$query);
		$Message="Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:DR/$DRType");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteComplaint")
{	
	$ComplaintId=$_POST['ComplaintId'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select ComplaintStatus from complaint where ComplaintId='$ComplaintId' ");
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$ComplaintStatus=$row['ComplaintStatus'];
	}
	
	if($ComplaintId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($ComplaintStatus=='Deleted' )
	{
		$Message="This complaint cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update complaint set ComplaintStatus='Deleted',DOD='$Date',DODUsername='$USERNAME' where ComplaintId='$ComplaintId' ";
		mysqli_query($CONNECTION,$query);
		$Message="Complaint Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:Complaint");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteVisitorBook")
{	
	$VisitorBookId=$_POST['VisitorBookId'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select VisitorBookStatus from visitorbook where VisitorBookId='$VisitorBookId' ");
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$VisitorBookStatus=$row['VisitorBookStatus'];
	}
	
	if($VisitorBookId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($VisitorBookStatus=='Deleted' )
	{
		$Message="This record cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update visitorbook set VisitorBookStatus='Deleted',DOD='$Date',DODUsername='$USERNAME' where VisitorBookId='$VisitorBookId' ";
		mysqli_query($CONNECTION,$query);
		$Message="Visitor Book record Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:VisitorBook");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteIncome")
{	
	$TransactionId=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
		
	if($USERTYPE!="MasterUser" && $USERTYPE!="Webmaster")
	$addquery=" and transaction.Username='$USERNAME'";	
	$query="select TransactionStatus,TransactionFrom,TransactionAmount,(OpeningBalance+AccountBalance) as TotalBalance,AccountName from transaction,accounts where 
		TransactionId='$TransactionId' and 
		TransactionHead='Income' $addquery";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$TransactionStatus=$row['TransactionStatus'];
		$AccountId=$row['TransactionFrom'];
		$Amount=$row['TransactionAmount'];
		$AccountName=$row['AccountName'];
		$TotalBalance=$row['TotalBalance'];
	}
	
	if($TransactionId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($TransactionStatus!='Active' )
	{
		$Message="This transaction cannot be deleted!!";
		$Type="error";
	}
	elseif($Amount>$TotalBalance)
	{
		$Message="$AccountName has not sufficient balance in it!!";
		$Type="error";	
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update transaction set TransactionStatus='Deleted',TransactionDOD='$Date' where TransactionId='$TransactionId' ";
		mysqli_query($CONNECTION,$query);
		$query2="update accounts set AccountBalance=AccountBalance-$Amount where AccountId='$AccountId' ";
		mysqli_query($CONNECTION,$query2);
		$Message="Income Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:Income");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteSupplier")
{	
	$SupplierId=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	$query="select * from supplier where SupplierId='$SupplierId' and SupplierStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$SupplierName=$row['SupplierName'];
	}	
	
	$query2="select SupplierId from purchase where SupplierId='$SupplierId' union all
		select SupplierId from expense where SupplierId='$SupplierId' ";
	$check2=mysqli_query($CONNECTION,$query2);
	$count2=mysqli_num_rows($check2);	
		
	if($SupplierId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count2>0)
	{
		$Message="This supplier is associated with Purchase!! Please delete them first!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$row=mysqli_fetch_array($check);
		$SupplierName=$row['SupplierName'];
		$Date=strtotime($Date);
		$query="update supplier set SupplierStatus='Deleted',DOD='$Date',DODUsername='$USERNAME' where SupplierId='$SupplierId' ";
		mysqli_query($CONNECTION,$query);
		$Message="Supplier Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:Supplier");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteExpensePayment")
{	
	$Id=explode("-",$_POST['Id']);
	$ExpenseId=$Id[0];
	$TransactionId=$Id[1];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	
	if($USERTYPE!='MasterUser' && $USERTYPE!='Webmaster')
	$addquery=" and transaction.Useranme='$USERNAME' ";
	$query="select TransactionStatus,TransactionFrom,TransactionAmount from transaction,expense where 
		TransactionId='$TransactionId' and
		TransactionHead='Expense' and
		TransactionHeadId='$ExpenseId' and
		transaction.TransactionHeadId=expense.ExpenseId and
		ExpenseStatus='Active' 
		$addquery ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$TransactionStatus=$row['TransactionStatus'];
		$AccountId=$row['TransactionFrom'];
		$Amount=$row['TransactionAmount'];
	}
	
	if($TransactionId=="" || $Password=="" || $ExpenseId=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($TransactionStatus!='Active' )
	{
		$Message="This transaction cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update transaction set TransactionStatus='Deleted',TransactionDOD='$Date' where TransactionId='$TransactionId' ";
		mysqli_query($CONNECTION,$query);
		mysqli_query($CONNECTION,"update accounts set AccountBalance=AccountBalance+$Amount where AccountId='$AccountId' ");
		mysqli_query($CONNECTION,"update expense set AmountPaid=AmountPaid-$Amount where ExpenseId='$ExpenseId' ");
		$Message="Expense Payment Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:Expense/Payment/$ExpenseId");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteExpense")
{	
	$ExpenseId=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$query="select * from expense where ExpenseId='$ExpenseId'";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$ExpenseStatus=$row['ExpenseStatus'];
		$ExpenseAmount=$row['ExpenseAmount'];
		$AmountPaid=$row['AmountPaid'];
		$Username=$row['Username'];
	}
	
	if($ExpenseId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($Username!=$USERNAME && ($USERTYPE!='MasterUser' && $USERTYPE!='Webmaster'))
	{
		$Message="This expense is not added by you, You cannot delete it!!";
		$Type="error";
	}
	elseif($ExpenseStatus!='Active' )
	{
		$Message="This transaction cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query1="select TransactionFrom,TransactionAmount from transaction where TransactionHead='Expense' and TransactionHeadId='$ExpenseId' and TransactionStatus='Active' ";
		$check1=mysqli_query($CONNECTION,$query1);
		$count1=mysqli_num_rows($check1);
		if($count1>0)
		{
			while($row1=mysqli_fetch_array($check1))
			{	
				$AccountId=$row1['TransactionFrom'];
				$TransactionAmount=$row1['TransactionAmount'];
				mysqli_query($CONNECTION,"update accounts set AccountBalance=AccountBalance+$TransactionAmount where AccountId='$AccountId' ");
			}	
			$query="update transaction set TransactionStatus='Deleted',TransactionDOD='$Date' where TransactionHeadId='$ExpenseId' and TransactionHead='Expense' ";
			mysqli_query($CONNECTION,$query);
		}
		mysqli_query($CONNECTION,"update expense set AmountPaid='0',ExpenseStatus='Deleted',DOD='$Date',DODUsername='$USERNAME' where ExpenseId='$ExpenseId' ");
		$Message="Expense Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:Expense");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteFee")
{	
	$TransactionId=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	if($USERNAME!='masteruser')
	$AddQuery=" and Username='$USERNAME' ";
	$query="select TransactionId,TransactionFrom,TransactionAmount,Token,TransactionHeadId from transaction,admission where
		TransactionId='$TransactionId' and TransactionStatus='Active' and 
		TransactionHead='Fee' and transaction.TransactionHeadId=admission.AdmissionId 	
		$AddQuery";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$TransactionFrom=$row['TransactionFrom'];
		$TransactionAmount=round($row['TransactionAmount'],2);
		$TransactionHeadId=$row['TransactionHeadId'];
		$Token=$row['Token'];
		
		$query2="Select (OpeningBalance+AccountBalance) as TotalBalance,AccountName from accounts where AccountId='$TransactionFrom' ";
		$check2=mysqli_query($CONNECTION,$query2);
		$row2=mysqli_fetch_array($check2);
		$TotalBalance=$row2['TotalBalance'];
		$AccountName=$row2['AccountName'];
	}
	
	if($TransactionId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type=error;
	}
	elseif($count==0)
	{
		$Message="This is not a valid URL!!";
		$Type=error;
	}
	elseif($Password!=$PASSWORD)
	{
		$Message="Password didn't match!!";
		$Type=error;
	}	
	elseif($TotalBalance<$TransactionAmount)
	{
		$Message="$AccountName has only $TotalBalance $CURRENCY. The transaction needs atleast $TransactionAmount $CURRENCY in it!!";
		$Type=error;
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		mysqli_query($CONNECTION,"update accounts set AccountBalance=AccountBalance-$TransactionAmount where AccountId='$TransactionFrom' ");
		mysqli_query($CONNECTION,"update transaction set TransactionStatus='Deleted' where TransactionId='$TransactionId' ");
		mysqli_query($CONNECTION,"update feepayment set FeePaymentStatus='Deleted' where Token='$Token' ");
		$Message="Receipt deleted successfully!!";
		$Type=success;		
	}
	SetNotification($Message,$Type);
	header("Location:Payment/$TransactionHeadId");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteIssueBook")
{	
	$IssueId=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$query="select BookReturn,Books,IRTo from bookissue where BookIssueId='$IssueId' and BookIssueStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	$row=mysqli_fetch_array($check);
	$BookReturn=$row['BookReturn'];	
	$IRTo=$row['IRTo'];	
	$Books=explode(",",$row['Books']);	
	
	if($IssueId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($BookReturn!="")
	{
		$Message="Some of books are already returned!! It cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$CountBooks=count($Books);
		foreach($Books as $BookValue)
		{
			$i++;
			$BookQuery.="ListBookId='$BookValue'";
			if($i<$CountBooks)
			$BookQuery.=" or ";
		}
		
		$Date=strtotime($Date);
		$query="update bookissue set BookIssueStatus='Deleted',DOD='$Date',DODUsername='$USERNAME' where BookIssueId='$IssueId' ";
		mysqli_query($CONNECTION,$query);
		$query1="update listbook set IRStatus='' where $BookQuery ";
		mysqli_query($CONNECTION,$query1);
		$Message="Book issue deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:IssueAndReturn/$IRTo");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteFee")
{	
	$TransactionId=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$query="select Token,TransactionAmount,TransactionFrom,TransactionHeadId from transaction where TransactionId='$TransactionId' and TransactionStatus='Active' and TransactionHead='Fee' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	$row=mysqli_fetch_array($check);
	$Token=$row['Token'];	
	$AccountId=$row['TransactionFrom'];	
	$TransactionAmount=$row['TransactionAmount'];
	$StudentId=$row['TransactionHeadId'];

	$query1="Select (OpeningBalance+AccountBalance) as TotalBalance,AccountName from accounts where AccountId='$AccountId' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$row1=mysqli_fetch_array($check1);
	$TotalBalance=$row1['TotalBalance'];
	$AccountName=$row1['AccountName'];
	
	if($TransactionId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($TotalBalance<$TransactionAmount)
	{
		$Message="$AccountName has only $TotalBalance $CURRENCY in it!! This transaction cannot be deleted!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update transaction set TransactionStatus='Deleted',TransactionDOD='$Date',TransactionDODUsername='$USERNAME' where TransactionId='$TransactionId' ";
		mysqli_query($CONNECTION,$query);
		$query2="update accounts set AccountBalance=AccountBalance-$TransactionAmount where AccountId='$AccountId' ";
		mysqli_query($CONNECTION,$query2);
		$query3="update feepayment set FeePaymentStatus='Deleted' where Token='$Token' ";
		mysqli_query($CONNECTION,$query3);
		$Message="Fee receipt deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:Payment/$StudentId");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteHeaderFooter")
{	
	$HeaderId=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select HeaderId from header where HeaderId='$HeaderId' ");
	$count=mysqli_num_rows($check);
	
	if($HeaderId=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="delete from header where HeaderId='$HeaderId'";
		mysqli_query($CONNECTION,$query);
		$Message="Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:ManageHeaderAndFooter");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeletePrintOption")
{	
	$Id=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select PrintOptionId from printoption where PrintOptionId='$Id' ");
	$count=mysqli_num_rows($check);
	
	if($Id=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update printoption set PrintOptionStatus='Deleted' where PrintOptionId='$Id'";
		mysqli_query($CONNECTION,$query);
		$Message="Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:PrintOption");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteSalaryTemplate")
{	
	$Id=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$check=mysqli_query($CONNECTION,"select SalaryStructureId from salarystructure where SalaryStructureId='$Id' and SalaryStructureStatus='Active'");
	$count=mysqli_num_rows($check);
	
	$query1="select SalaryStructureId from staffsalary where SalaryStructureId='$Id' and StaffSalaryStatus='Active' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	
	if($Id=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($count1>0)
	{
		$Message="This salary is already used in some staff. Please remove them first!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update salarystructure set SalaryStructureStatus='Deleted' where SalaryStructureId='$Id'";
		mysqli_query($CONNECTION,$query);
		mysqli_query($CONNECTION,"delete from salarystructuredetail where SalaryStructureId='$Id' ");
		$Message="Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:SalaryStructureTemplate");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteStaffSalarySetup")
{	
	$Id=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$query="select StaffId from staffsalary where StaffSalaryId='$Id' and StaffSalaryStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	$row=mysqli_fetch_array($check);
	$StaffId=$row['StaffId'];
	
	if($Id=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query="update staffsalary set StaffSalaryStatus='Deleted' where StaffSalaryId='$Id'";
		mysqli_query($CONNECTION,$query);
		$Message="Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:ManageStaff/$StaffId");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteStaffSalaryPayment")
{	
	$Id=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	
	$query="select StaffId,ExpenseId,expense.Username,TransactionAmount,TransactionFrom from expense,transaction where 
		expense.ExpenseId=transaction.TransactionHeadId and
		StaffId!='' and SalaryMonthYear!='' and SalaryPaymentType!='' and 
		transaction.TransactionHead='Expense' and
		TransactionStatus='Active' and
		ExpenseStatus='Active' and
		TransactionId='$Id' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	$row=mysqli_fetch_array($check);
	$Username=$row['Username'];
	$ExpenseId=$row['ExpenseId'];
	$StaffId=$row['StaffId'];
	$Amount=$row['TransactionAmount'];
	$AccountId=$row['TransactionFrom'];
	
	if($Id=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($USERNAME!=$Username)
	{
		$Message="This payment is not made by you, You cannot delete it!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query1="update expense set AmountPaid='0',ExpenseStatus='Deleted',DOD='$Date',DODUsername='$USERNAME' where ExpenseId='$ExpenseId' ";
		mysqli_query($CONNECTION,$query1);
		$query2="update transaction set TransactionStatus='Deleted',TransactionDOD='$Date',TransactionDODUsername='$USERNAME' where TransactionId='$Id' ";
		mysqli_query($CONNECTION,$query2);
		mysqli_query($CONNECTION,"update accounts set AccountBalance=AccountBalance+$Amount where AccountId='$AccountId' ");
		$Message="Salary Deleted successfully!!";
		$Type="success";
	}
	SetNotification($Message,$Type);
	header("Location:ManageStaff/$StaffId");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteReading")
{	
	$Id=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	$query="select VehicleReadingId from vehiclereading where VehicleReadingId='$Id' and VehicleReadingStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($Id=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query1="update vehiclereading set VehicleReadingStatus='Deleted',DOD='$Date',DODUsername='$USERNAME' where VehicleReadingId='$Id' ";
		mysqli_query($CONNECTION,$query1);
		$Message="Deleted successfully!!";
		$Type="success";
	}
	
	SetNotification($Message,$Type);
	header("Location:Transport");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteFuel")
{	
	$Id=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	$query="select FuelId from vehiclefule where FuelId='$Id' and FuelStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($Id=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$Date=strtotime($Date);
		$query1="update vehiclefuel set FuelStatus='Deleted',DOD='$Date',DODUsername='$USERNAME' where FuelId='$Id' ";
		mysqli_query($CONNECTION,$query1);
		$Message="Deleted successfully!!";
		$Type="success";
	}
	
	SetNotification($Message,$Type);
	header("Location:Transport");	
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteExamActivity")
{
	$Id=$_POST['Id'];
	$Password=$_POST['Password'];
	if(isset($Password))
	$Password=md5($Password);
	$query="select ExamDetailId,ExamId from examdetail where ExamDetailId='$Id' and ExamDetailStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($Id=="" || $Password=="")
	{
		$Message="All the fields are mandatory!!";
		$Type="error";
	}
	elseif($PASSWORD!=$Password)
	{
		$Message="Password didnot match!!";
		$Type="error";
	}
	elseif($count==0)
	{
		$Message="This is not a valid link!!";
		$Type="error";
	}
	elseif($TOKEN!=$RandomNumber)
	{
		$Message="Illegal data posted!!";
		$Type="error";
	}
	else
	{
		$row=mysqli_fetch_array($check);
		$ExamId=$row['ExamId'];
		$Date=strtotime($Date);
		$query1="update examdetail set ExamDetailStatus='Deleted' where ExamDetailId='$Id' ";
		mysqli_query($CONNECTION,$query1);
		$Message="Deleted successfully!!";
		$Type="success";
	}
	
	SetNotification($Message,$Type);
	header("Location:ExamSetup/$ExamId");	
}
else
header("location:DashBoard");
?>