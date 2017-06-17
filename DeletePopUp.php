<?php 
include("Include.php"); 
$Action=$_GET['Action'];
$Id=$_GET['Id'];

function PopUpHead($Title)
{
echo "<div class=\"modal-header\">
    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">X</button>
    <h3 id=\"myModalLabel\">$Title</h3>
  </div>
  <div class=\"modal-body\">";
}

function DeleteForm($ActionContent,$Id)
{
$TOKEN=$_SESSION['TOKEN'];
?>
<form class="form-horizontal" action="ActionDelete" name="Delete" id="Delete" method="Post">
	<div class="form-row row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<label class="form-label span4" for="Password">Password</label>
				<input tabindex="1" class="span8" id="Password" type="password" name="Password" required />
			</div>
		</div>
	</div>	
	<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
	<input type="hidden" name="Action" value="<?php echo $ActionContent; ?>" readonly>
	<input type="hidden" name="Id" value="<?php echo $Id; ?>" readonly>
	<?php $ButtonContent="Confirm Delete"; ActionButton($ButtonContent,2); ?>									
</form>
<?php
}

if($Action=="DeleteFee")
{
	$Title="Delete Fee Receipt No \"$Id\" ??";
	PopUpHead($Title);

	$query="select TransactionAmount,TransactionFrom,Username,AccountName from transaction,accounts,admission where
		transaction.TransactionFrom=accounts.AccountId and 
		TransactionId='$Id' and TransactionStatus='Active' and
		TransactionHead='Fee' and transaction.TransactionHeadId=admission.AdmissionId and 
		TransactionHead='Fee' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	$row=mysqli_fetch_array($check);
	$Username=$row['Username'];
	$TransactionAmount=$row['TransactionAmount'];
	$AccountName=$row['AccountName'];
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid fee receipt!!</div>
	<?php } elseif($USERNAME!='masteruser' && $Username!=$USERNAME) { ?>
	<div class="alert alert-error">This payment was not received by you. You cannot delete it!!</div>
	<?php } else { ?>
	<div class="alert alert-info"><?php echo "<b>$TransactionAmount $CURRENCY</b> will be deducted from <b>$AccountName</b>!!"; ?></div>
	<?php 
		$ActionContent="DeleteFee"; 
		DeleteForm($ActionContent,$Id);
	} 
}
elseif($Action=="DeleteIssueBook")
{
	$Title="Delete Book Issue Id \"$Id\" ??";
	PopUpHead($Title);
	
	$query="select BookReturn,Books,IRTo from bookissue where BookIssueId='$Id' and BookIssueStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	$row=mysqli_fetch_array($check);
	$BookReturn=$row['BookReturn'];
	$Books=$row['Books'];
	$IRTo=$row['IRTo'];	
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Book Issue Id!!</div>
	<?php } elseif($BookReturn!="") { ?>
	<div class="alert alert-error">Some of the books are already returned!! It cannot be deleted now!!</div>
	<?php } else { 
	$ActionContent="DeleteIssueBook"; 
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeleteHeaderFooter")
{
	
	$query="select MasterEntryValue,HeaderTitle from header,masterentry where 
		header.HRType=masterentry.MasterEntryId and HeaderId='$Id' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	$row=mysqli_fetch_array($check);
	$HRType=$row['MasterEntryValue'];
	$HeaderTitle=$row['HeaderTitle'];
	
	$Title="Delete $HRType \"$HeaderTitle\" ??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You will not be able to undo it!!</div>
	<?php
	$ActionContent="DeleteHeaderFooter"; 
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeletePrintOption")
{
	$query="select * from printoption,masterentry where 
	PrintOptionId='$Id' and 
	PrintOptionStatus='Active' and
	printoption.PrintCategory=masterentry.MasterEntryId ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	$row=mysqli_fetch_array($check);
	$PrintCategoryName=$row['MasterEntryValue'];
	
	$Title="Delete Print Option \"$PrintCategoryName\" ??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You will not be able to undo it!!</div>
	<?php
	$ActionContent="DeletePrintOption"; 
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeleteSupplier")
{
	$query="select SupplierId from supplier where SupplierId='$Id' and SupplierStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$SupplierName=$row['SupplierName'];
	}	
	
	$query1="select SupplierId from purchase where SupplierId='$Id' union all
		select SupplierId from expense where SupplierId='$Id' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	
	$Title="Delete Supplier \"$SupplierName\" ??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } elseif($count1>0) { ?>
	<div class="alert alert-error">Purchase record is associated with this supplier, You cannot delete it!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You will not be able to undo it!!</div>
	<?php
	$ActionContent="DeleteSupplier"; 
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeleteExpense")
{
	$query="select Username from expense where ExpenseId='$Id' and ExpenseStatus='Active'";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$Username=$row['Username'];
	}
	
	$Title="Delete Expense Id \"$Id\" ??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } elseif($Username!=$USERNAME && ($USERTYPE!='MasterUser' && $USERTYPE!='Webmaster')) { ?>
	<div class="alert alert-error">This expense is not added by you, You cannot delete it!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You cannot recover it after deletion!! All the payments made for this expense will be refunded back to the respective account!!</div>
	<?php
	$ActionContent="DeleteExpense"; 
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeleteExpensePayment")
{
	$Id=explode("-",$Id);
	$ExpenseId=$Id[0];
	$TransactionId=$Id[1];
	$query="select ExpenseId from expense where ExpenseId='$ExpenseId' and ExpenseStatus='Active'";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	
	$query1="select Username,AccountName from transaction,accounts where 
		transaction.TransactionFrom=accounts.AccountId and 
		TransactionHead='Expense' and 
		TransactionHeadId='$ExpenseId' and
		TransactionStatus='Active' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0)
	{
		$row1=mysqli_fetch_array($check1);
		$Username=$row1['Username'];
		$AccountName=$row1['AccountName'];
	}
	
	$Title="Delete Payment Id \"$TransactionId\" ??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } elseif($Username!=$USERNAME && ($USERTYPE!='MasterUser' && $USERTYPE!='Webmaster')) { ?>
	<div class="alert alert-error">This payment is not made by you, You cannot delete it!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You cannot recover it after deletion!! Payments made will be refunded back to the "<b><?php echo "$AccountName";?></b>" !!</div>
	<?php
	$ActionContent="DeleteExpensePayment"; 
	$Id=implode("-",$Id);
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeleteIncome")
{
	$query="select Username,TransactionAmount,AccountName,(OpeningBalance+AccountBalance) as TotalBalance from transaction,accounts where 
		transaction.TransactionFrom=accounts.AccountId and 
		TransactionId='$Id' and 
		TransactionStatus='Active'";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$Username=$row['Username'];
		$AccountName=$row['AccountName'];
		$TransactionAmount=$row['TransactionAmount'];
		$TotalBalance=$row['TotalBalance'];
	}
	
	$Title="Delete Receipt Id \"$Id\" ??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } elseif($Username!=$USERNAME && ($USERTYPE!='MasterUser' && $USERTYPE!='Webmaster')) { ?>
	<div class="alert alert-error">This income is not added by you, You cannot delete it!!</div>
	<?php } elseif($TransactionAmount>$TotalBalance) { ?>
	<div class="alert alert-error"><b><?php echo "$AccountName"; ?></b> has not sufficient balance in it!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You cannot recover it after deletion!! All the payments made for this expense will be deducted from "<b><?php echo "$AccountName"; ?></b>"!!</div>
	<?php
	$ActionContent="DeleteIncome"; 
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeleteSalaryTemplate")
{
	$query="select SalaryStructureName from salarystructure where SalaryStructureId='$Id' and SalaryStructureStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		$row=mysqli_fetch_array($check);
		$SalaryStructureName=$row['SalaryStructureName'];
		$query1="select SalaryStructureId from staffsalary where SalaryStructureId='$Id' and StaffSalaryStatus='Active' ";
		$check1=mysqli_query($CONNECTION,$query1);
		$count1=mysqli_num_rows($check1);
	}
	
	$Title="Delete Salary Structure \"$SalaryStructureName\" ??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } elseif($count1>0) { ?>
	<div class="alert alert-error"><b>This template is already used in some staff salary!! Remove them first!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You cannot recover it after deletion!! </div>
	<?php
	$ActionContent="DeleteSalaryTemplate"; 
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeleteStaffSalarySetup")
{
	$query="select EffectiveFrom from staffsalary where StaffSalaryId='$Id' and StaffSalaryStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	
	$Title="Delete Salary Setup??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You cannot recover it after deletion!! </div>
	<?php
	$ActionContent="DeleteStaffSalarySetup"; 
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeleteStaffSalaryPayment")
{
	$query="select expense.Username from expense,transaction where 
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
	
	$Title="Delete Salary Payment Receipt No \"$Id\" ??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } elseif($Username!=$USERNAME && ($USERTYPE!='MasterUser' && $USERTYPE!='Webmaster')) { ?>
	<div class="alert alert-error">This payment is not made by you, You cannot delete it!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You cannot recover it after deletion!! </div>
	<?php
	$ActionContent="DeleteStaffSalaryPayment"; 
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeleteReading")
{
	$query="select VehicleReadingId from vehiclereading where VehicleReadingId='$Id' and VehicleReadingStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	$Title="Delete Vehicle Reading \"$Id\" ??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You cannot recover it after deletion!! </div>
	<?php
	$ActionContent="DeleteReading"; 
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeleteFuel")
{
	$query="select FuelId from vehiclefuel where FuelId='$Id' and FuelStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	$Title="Delete Fuel \"$Id\" ??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You cannot recover it after deletion!! </div>
	<?php
	$ActionContent="DeleteFuel"; 
	DeleteForm($ActionContent,$Id);
	}
}
elseif($Action=="DeleteExamActivity")
{
	$query="select ExamDetailId from examdetail where ExamDetailId='$Id' and ExamDetailStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	$Title="Delete ExamDetail \"$Id\" ??";
	PopUpHead($Title);
	if($count==0) { ?>
	<div class="alert alert-error">This is not a valid Id!!</div>
	<?php } else { ?>
	<div class="alert alert-info">You cannot recover it after deletion!! </div>
	<?php
	$ActionContent="DeleteExamActivity"; 
	DeleteForm($ActionContent,$Id);
	}
}
?>

<script type="text/javascript">
$(document).ready(function() {
	$('#Delete :input:visible:enabled:first').focus();
	$("#Delete").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			Password: {
				required: true,
			}
		},
		messages: {
			Password: {
				required: "Please enter password!!",
			}
		}   
	});	
});
</script>