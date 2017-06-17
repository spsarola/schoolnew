<?php
include("Config.php");
$Action=isset($_POST['Action']) ? $_POST['Action'] : '';
$TotalFeeTypeAmount="";
if($Action=="Fee")
{
if(!empty($_POST) && isset($_POST))
{
  $FeeType = $_POST['FeeType'];
  $Amount = $_POST['Amount'];
  $Token=$_POST['Token'];
  $AdmissionId=$_POST['AdmissionId'];
  $SectionId=$_POST['SectionId'];
  $CURRENTSESSION=$_POST['CurrentSession'];
  $query1="select FeePaymentId from feepayment where Token='$Token' and FeeType='$FeeType' ";
  $check1=mysqli_query($CONNECTION,$query1);
  $count1=mysqli_num_rows($check1);
  
  $query2="select FeeStructure from studentfee where Session='$CURRENTSESSION' and AdmissionId='$AdmissionId' and SectionId='$SectionId' ";
  $check2=mysqli_query($CONNECTION,$query2);
  $row2=mysqli_fetch_array($check2);
  $FeeStructure=explode(",",$row2['FeeStructure']);
  foreach($FeeStructure as $FeeStructureValue)
  {
	$FeeStructureSubArray=explode("-",$FeeStructureValue);
	$FeeTypeValue=$FeeStructureSubArray[0];
	$FeeAmountValue=$FeeStructureSubArray[1];
	if($FeeTypeValue==$FeeType)
	$TotalFeeTypeAmount=$FeeAmountValue;
  }
  
  $query3="select SUM(feepayment.Amount) as PaidFeeType,MasterEntryValue as FeeName from feepayment,transaction,fee,masterentry where
	feepayment.Token=transaction.Token and
	feepayment.FeeType=fee.FeeId and
	fee.FeeType=masterentry.MasterEntryId and
	transaction.TransactionHead='Fee' and
	transaction.TransactionHeadId='$AdmissionId' and
	transaction.TransactionSession='$CURRENTSESSION' and 
	transaction.TransactionStatus='Active' and
	feepayment.FeeType='$FeeType' group by feepayment.FeeType ";
	$check3=mysqli_query($CONNECTION,$query3);
	$row3=mysqli_fetch_array($check3);
	$PaidFeeType=$row3['PaidFeeType'];
	$PaidFeeName=$row3['FeeName'];
	$TotalFeeTypeAmount-=$PaidFeeType;
  
  if($Token=="" || $FeeType=="" || $Amount=="")
	echo "<div class=\"alert alert-error\">All the fields are mandatory!!</div>";
  elseif(!is_numeric($Amount) || $Amount<=0)
	echo "<div class=\"alert alert-error\">Amount should be numeric!!</div>";
  elseif($Amount>$TotalFeeTypeAmount)
	echo "<div class=\"alert alert-error\">Selected fee is only $TotalFeeTypeAmount $CURRENCY is due!!</div>";
  elseif($count1>0)
	echo "<div class=\"alert alert-error\">This fee type is already added!!</div>";
  else
  {
	$sql = "INSERT INTO feepayment(Token,FeeType,Amount,FeePaymentStatus) values('$Token','$FeeType','$Amount','Pending') ";
	$result = mysqli_query($CONNECTION,$sql);
	echo "<div class=\"alert alert-success\">fee added successfully!!</div>";
   }
}
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('table#links td a.delete').click(function()
		{
			if (confirm("Are you sure you want to delete this row?"))
			{
				var id = $(this).parent().parent().attr('id');
				var parent = $(this).parent().parent();
				$.ajax(
				{
				   type: "POST",
				   url: "/DeleteRow.php",
				   data: { id: id, Action: 'test'},
				   cache: false,

				   success: function()
				   {
						parent.fadeOut('slow', function() {$(this).remove();});
				   },
				   error: function()
				   {
				   }
				 });                
			}
		});
	});
</script>
<table id="links" class="responsive table table-bordered">
	<thead>
		<tr>
			<th>Fee Type</th>
			<th>Amount</th>
			<th><span class="icomoon-icon-cancel"></span></th>
	</thead>
	<tbody>
	<?php
	$sql = "select MasterEntryValue,feepayment.Amount,FeePaymentId from feepayment,fee,masterentry where 
		fee.FeeId=feepayment.FeeType and 
		fee.FeeType=masterentry.MasterEntryId and 
		Token='$Token' and
		FeePaymentStatus='Pending' ";
	$result = mysqli_query($CONNECTION,$sql);
	while($row = mysqli_fetch_array($result))
	{
	?>
	<tr id="<?php echo $row['FeePaymentId']; ?>">
		<td><?php echo $row['MasterEntryValue']; ?></td>
		<td><?php echo $row['Amount']; ?></td>
		<td><a href="#" class="delete"><span class="icomoon-icon-cancel"></span></a></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>
<?php
}
elseif($Action=="ListBook")
{
if(!empty($_POST) && isset($_POST))
{
  $BookId = $_POST['BookId'];
  $AccessionNo = $_POST['AccessionNo'];
  $Token=$_POST['Token'];
  $query1="select AccessionNo from listbook where AccessionNo='$AccessionNo' and (ListBookStatus='Active' or (ListBookStatus='Pending' and Token='$Token')) ";
  $check1=mysqli_query($CONNECTION,$query1);
  $count1=mysqli_num_rows($check1);
  
  if($Token=="" || $BookId=="" || $AccessionNo=="")
	echo "<div class=\"alert alert-error\">All the fields are mandatory!!</div>";
  elseif($count1>0)
	echo "<div class=\"alert alert-error\">This accession no \"$AccessionNo\" is already used!!</div>";
  else
  {
	$Date=strtotime(isset($Date));
	$sql = "INSERT INTO listbook(Token,BookId,AccessionNo,ListBookStatus) values('$Token','$BookId','$AccessionNo','Pending') ";
	$result = mysqli_query($CONNECTION,$sql);
	echo "<div class=\"alert alert-success\">book added successfully!!</div>";
   }
}
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('table#links td a.delete').click(function()
		{
			if (confirm("Are you sure you want to delete this row?"))
			{
				var id = $(this).parent().parent().attr('id');
				var parent = $(this).parent().parent();
				$.ajax(
				{
				   type: "POST",
				   url: "DeleteRow.php",
				   data: { id: id, Action: 'ListBook'},
				   cache: false,

				   success: function()
				   {
						parent.fadeOut('slow', function() {$(this).remove();});
				   },
				   error: function()
				   {
				   }
				 });                
			}
		});
	});
</script>
<table id="links" class="responsive table table-bordered">
	<thead>
		<tr>
			<th>Book Name</th>
			<th>Author Name</th>
			<th>Accession No</th>
			<th><span class="icomoon-icon-cancel"></span></th>
	</thead>
	<tbody>
	<?php
	$sql = "select ListBookId,BookName,AuthorName,AccessionNo from book,listbook where 
			book.BookId=listbook.BookId and 
			ListBookStatus='Pending' and
			Token='$Token' ";
	$result = mysqli_query($CONNECTION,$sql);
	while($row = mysqli_fetch_array($result))
	{
	?>
	<tr id="<?php echo $row['ListBookId']; ?>">
		<td><?php echo $row['BookName']; ?></td>
		<td><?php echo $row['AuthorName']; ?></td>
		<td><?php echo $row['AccessionNo']; ?></td>
		<td><a href="#" class="delete"><span class="icomoon-icon-cancel"></span></a></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>
<?php
}
?>