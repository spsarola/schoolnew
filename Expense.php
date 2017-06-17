<?php
$PageName="Expense";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
include("Include.php");
IsLoggedIn();
include("Template/HTML.php");
?>    

<?php
include("Template/Header.php");
?>

<?php
include("Template/Sidebar.php");
?>

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Expense Account"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$GetSupplierId=$SupplierList=$addquery=$ShowTransactionOfExpense="";
				$SNo=0;
				if($Action=="Payment")
				{
					$ExpenseId=$_GET['UniqueId'];
					if($USERTYPE!="MasterUser" && $USERTYPE!="Webmaster")
					$addquery=" and Username='$USERNAME' ";
					$query1="select * from expense where ExpenseId='$ExpenseId' and ExpenseStatus='Active' $addquery";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0)
					{
						$row1=mysqli_fetch_array($check1);
						$ExpenseAmount=round($row1['ExpenseAmount'],2);
						$AmountPaid=round($row1['AmountPaid'],2);
						$Balance=$ExpenseAmount-$AmountPaid;
					}
					$UpdateExpenseId=$ExpenseId;
				}
				
				$check=mysqli_query($CONNECTION,"select * from supplier where SupplierStatus='Active' order by SupplierName ");
				$count=mysqli_num_rows($check);
				if($count>0)
				{
					while($row=mysqli_fetch_array($check))
					{
						$SupplierName=$row['SupplierName'];
						$SupplierId=$row['SupplierId'];
						if($SupplierId==$GetSupplierId)
							$SupplierSelected="Selected";
						else
							$SupplierSelected="";
						$SupplierList.="<option value=$SupplierId $SupplierSelected>$SupplierName</option>";
					}
				}				
				?>
				
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Expense</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="Expense" id="Expense" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">For</label>
												<div class="span8 controls sel">
												<?php 
												$ExpenseAccount="ExpenseAccount";
												GetCategoryValue($ExpenseAccount,$ExpenseAccount,'','','','','',1,''); 
												?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Supplier</label>
												<div class="span8 controls sel">
												<select tabindex="2" name="SupplierId" id="SupplierId" class="nostyle" style="width:100%;">
												<option></option>
												<?php echo $SupplierList; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Amount</label>
												<input tabindex="3" class="span8" id="Amount" type="text" name="Amount" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Date of Expense</label>
												<input tabindex="4" class="span8" id="ExpenseDate" type="text" name="ExpenseDate" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Expense Remarks</label>
												<div class="span8 controls-textarea">
												<textarea tabindex="5" name="ExpenseRemarks" id="ExpenseRemarks"></textarea>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Amount Paid</label>
												<input tabindex="6" class="span4 styled" id="Payment" type="checkbox" name="Payment" value="Yes" /> Payment
												<input tabindex="6" class="span4" id="AmountPaid" type="text" name="AmountPaid" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Account</label>
												<div class="span8 controls sel">
												<select tabindex="7" class="nostyle" name="Account" id="Account" style="width:100%;">
												<option></option>
												<?php
												echo $LISTACCOUNT;
												?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Date of Payment</label>
												<input tabindex="8" class="span8" id="DOP" type="text" name="DOP" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Payment Remarks</label>
												<div class="span8 controls-textarea">
												<textarea tabindex="9" name="PaymentRemarks" id="PaymentRemarks"></textarea>
												</div>
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="Expense" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<input type="hidden" name="ExpenseAccountType" value="<?php echo $ExpenseAccount; ?>" readonly>
									<?php $ButtonContent="Add"; ActionButton($ButtonContent,10); ?>
								</form>
                            </div>
                        </div>
                    </div>
					
					<div class="span8">
					<?php
					$query="select ExpenseId,ExpenseAmount,ExpenseDate,SupplierName,AmountPaid,ExpenseRemarks,MasterEntryValue from expense,supplier,masterentry where 
						expense.ExpenseAccountType=masterentry.MasterEntryId and
						ExpenseStatus='Active' and
						expense.SupplierId=supplier.SupplierId
						order by ExpenseId ";
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					$DATA=array();
					$QA=array();
					while($row=mysqli_fetch_array($result))
					{
						$ListExpenseId=$row['ExpenseId'];	
						$ListExpenseAccountName=$row['MasterEntryValue'];	
						$ListSupplierName=$row['SupplierName'];	
						$ListRemarks=$row['ExpenseRemarks'];	
						$ListExpenseAmount=round($row['ExpenseAmount'],2);	
						$ListAmountPaid=round($row['AmountPaid'],2);		
						$ExpenseTime=date("h:i a",$row['ExpenseDate']);
						if($ExpenseTime=="12:00 am")
						$ListExpenseDate=date("d M Y",$row['ExpenseDate']);	
						else
						$ListExpenseDate=date("d M Y,h:i a",$row['ExpenseDate']);
						$MakePayment="<a href=Expense/Payment/$ListExpenseId><span class=\"icomoon-icon-coin\"></span></a>";
						$Delete="<a href=DeletePopUp/DeleteExpense/$ListExpenseId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel\"></span></a>";
						$Note="<a href=Note/Expense/$ListExpenseId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-clipboard-3\"></span></a>";
						$SNo++;
						$QA[]=array($ListExpenseAccountName,$ListSupplierName,$ListExpenseAmount,$ListAmountPaid,$ListExpenseDate,$ListRemarks,$MakePayment,$Delete,$Note);
					}
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);	
					
					if($Action=="Payment" && $count1>0)
					{
						$query2="select TransactionAmount,TransactionDate,AccountName,TransactionId from transaction,accounts where
							transaction.TransactionFrom=accounts.AccountId and 
							TransactionHead='Expense' and
							TransactionHeadId='$ExpenseId' and
							TransactionStatus='Active' ";
						$check2=mysqli_query($CONNECTION,$query2);
						$count2=mysqli_num_rows($check2);
						while($row2=mysqli_fetch_array($check2))
						{
							$ShowTransactionAmount=$row2['TransactionAmount'];
							$ShowTransactionDate=date("d M Y,h:ia",$row2['TransactionDate']);
							$ShowTransactionFrom=$row2['AccountName'];
							$ShowTransactionId=$row2['TransactionId'];
							$DeletePayment="<a href=/DeletePopUp/DeleteExpensePayment/$ExpenseId-$ShowTransactionId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel\"></span></a>";
							$ShowTransactionOfExpense.="<tr>
							<td>$ShowTransactionId</td>
							<td>$ShowTransactionFrom</td>
							<td>$ShowTransactionAmount $CURRENCY</td>
							<Td>$ShowTransactionDate</td>
							<Td>$DeletePayment</td>
							</tr>";
						}
						?>
						<div class="box gradient">
							<div class="title">
								<h4><span>Payment List</span></h4>
                                <a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix" style="padding:5px;">
								<?php if($count2>0) { ?>
								<table cellpadding="0" cellspacing="0" border="0" class="responsive table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Receipt No</th>
											<th>Account</th>
											<th>Amount Paid</th>
											<th>Date of Payment</th>
											<th><span class="icomoon-icon-cancel"></span></th>
										</tr>
									</thead>
									<tbody>
									<?php
										echo $ShowTransactionOfExpense; 
									?>
									</tbody>
								</table>
								<?php } ?>
						<?php
						if($Balance>0)
						{
						?>
							<div class="alert alert-info">Total Amount remaining : <?php echo "<b>$Balance $CURRENCY</b>"; ?></div>
								<form class="form-horizontal" action="Action" name="ExpenseMakePayment" id="ExpenseMakePayment" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Amount Paid</label>
												<input tabindex="21" class="span8" id="RemainingAmountPaid" type="text" name="RemainingAmountPaid" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Account</label>
												<div class="span8 controls sel">
												<select tabindex="22" class="nostyle" name="RemainingAccount" id="RemainingAccount" style="width:100%;">
												<option></option>
												<?php
												echo $LISTACCOUNT;
												?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Date of Payment</label>
												<input tabindex="23" class="span8" id="RemainingDOP" type="text" name="RemainingDOP" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Remarks</label>
												<div class="span8 controls-textarea">
												<textarea tabindex="24" name="RemainingRemarks" id="RemainingRemarks"></textarea>
												</div>
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ExpenseMakePayment" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="ExpenseId" value="<?php echo $UpdateExpenseId; ?>" readonly>
										<?php } ?>
										<?php $ButtonContent="Add Payment"; ActionButton($ButtonContent,25); ?>
								</form>
						<?php
						}
						else
						echo "<div class=\"alert alert-error\">No amount remaining!!</div>";
						?>
							</div>
						</div>
						<?php
					}
					?>
					
						<div class="box gradient">
							<div class="title">
								<h4><span>Expense List</span></h4>
                                <a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="ExpenseTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>For</th>
											<th>Supplier Name</th>
											<th>Amount </th>
											<th>Paid </th>
											<th>Expense Date </th>
											<th>Remarks</th>
											<th><span class="icomoon-icon-coin"></span></th>
											<th><span class="icomoon-icon-cancel"></span></th>
											<th><span class="icomoon-icon-clipboard-3 tip" title="Note"></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
	
<script type="text/javascript">
$(document).ready(function() {

	$('#ExpenseTable').dataTable({
		"sPaginationType": "two_button",
		"bJQueryUI": false,
		"bAutoWidth": false,
		"bLengthChange": false,  
		"bProcessing": true,
		"bDeferRender": true,
		"sAjaxSource": "plugins/Data/data1.txt",
		"fnInitComplete": function(oSettings, json) {
		  $('.dataTables_filter>label>input').attr('id', 'search');
			$('#myModal').modal({ show: false});
			$('#myModal').on('hidden', function () {
				console.log('modal is closed');
			})
			$("a[data-toggle=modal]").click(function (e) {
			lv_target = $(this).attr('data-target');
			lv_url = $(this).attr('href');
			$(lv_target).load(lv_url);
			});	
		}
	});
		
	if($('#DOP').length) {
	$('#DOP').datetimepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
	if($('#RemainingDOP').length) {
	$('#RemainingDOP').datetimepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
	if($('#ExpenseDate').length) {
	$('#ExpenseDate').datetimepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
	$("input, textarea, select").not('.nostyle').uniform();
	$("#ExpenseAccount").select2();
	$('#ExpenseAccount').select2({placeholder: "Select"});
	$("#SupplierId").select2();
	$('#SupplierId').select2({placeholder: "Select"});
	$("#Account").select2();
	$('#Account').select2({placeholder: "Select"});
	$("#RemainingAccount").select2();
	$('#RemainingAccount').select2({placeholder: "Select"});
	$("#Expense").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			<?php echo $ExpenseAccount; ?>: {
				required: true,
			},
			SupplierId: {
				required: true,
			},
			Amount: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=Amount"
			},
			ExpenseDate: {
				required: true,
			},
			AmountPaid: {
				required: "#Payment:checked",
				remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=AmountPaid"
			},
			Account: {
				required: "#Payment:checked",
			},
			DOP: {
				required: "#Payment:checked",
			}
		},
		messages: {
			<?php echo $ExpenseAccount; ?>: {
				required: "Please enter this!!",
			},
			SupplierId: {
				required: "Please select this!!",
			},
			Amount: {
				required: "Please enter this!!",
				remote: jQuery.format("Numeric & greater than zero!!"),
			},
			ExpenseDate: {
				required: "Please enter this!!",
			},
			AmountPaid: {
				required: "Please enter this!!",
				remote: jQuery.format("Numeric & greater than zero!!"),
			},
			Account: {
				required: "Please select this!!",
			},
			DOP: {
				required: "Please enter this!!",
			}
		}   
	});
	$("#ExpenseMakePayment").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			RemainingAmountPaid: {
				required : true,
				remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=RemainingAmountPaid"
			},
			RemainingAccount: {
				required : true,
			},
			RemainingDOP: {
				required : true,
			},
			RemainingRemarks: {
				required : true,
			}
		},
		messages: {
			RemainingAmountPaid: {
				required: "Please enter this!!",
				remote: jQuery.format("Numeric & greater than zero!!"),
			},
			RemainingAccount: {
				required: "Please select this!!",
			},
			RemainingDOP: {
				required: "Please enter this!!",
			},
			RemainingRemarks: {
				required: "Please enter this!!",
			}
		}   
	});
});
</script>	    
<?php
include("Template/Footer.php");
?>