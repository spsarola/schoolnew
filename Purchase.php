<?php
$PageName="Purchase";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
include("Include.php");
IsLoggedIn();

$Token=isset($_GET['Token']) ? $_GET['Token'] : '';
if($Token=="")
$Token=PasswordGenerator(30);

$query199="select Token from purchaselist where Token='$Token' and MaterialType!='Stock' ";
$check199=mysqli_query($CONNECTION,$query199);
$TokenExists=mysqli_num_rows($check199);
if($TokenExists>0)
{
header("Location:Purchase");
exit();
}

$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
$UniqueId=isset($_GET['UniqueId']) ? $_GET['UniqueId'] : '';
if($Action=="Delete" && $UniqueId!="")
{
	$query5="select PurchaseStatus from purchase,purchaselist where
			purchase.Token=purchaselist.Token and
			purchase.Token='$Token' and
			(PurchaseStatus='' or PurchaseStatus='Started') and
			PurchaseListId='$UniqueId' ";
	$check5=mysqli_query($CONNECTION,$query5);
	$count5=mysqli_num_rows($check5);
	if($count5>0)
	{
		$query6="select PurchaseListId from purchaselist where Token='$Token' ";
		$check6=mysqli_query($CONNECTION,$query6);
		$count6=mysqli_num_rows($check6);
		mysqli_query($CONNECTION,"delete from purchaselist where PurchaseListId='$UniqueId' ");
		if($count6==1)
		mysqli_query($CONNECTION,"delete from purchase where Token='$Token' ");
		$Message="Item deleted successfully!!";
		$Type="success";
	}
	else
	{
		$Message="Wrong URL!!";
		$Type="success";	
	}
	SetNotification($Message,$Type);
	header("Location:Purchase/$Token");	
}

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
                <?php $BreadCumb="Purchase"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				<?php
				function Purchase($Token,$TOKEN)
				{
				?>
				<div class="box chart gradient">
					<div class="title">
						<h4>
							<span>Purchase</span>
						</h4>
						<a href="#" class="minimize">Minimize</a>
					</div>
					<div class="content" style="padding-bottom:0;">
						<form class="form-horizontal" action="Action" name="Purchase" id="Purchase" method="Post">
							<div class="form-row row-fluid">
								<div class="span12">
									<div class="row-fluid">
									<label class="form-label span4" for="normal">Stock Type</label> 
										<div class="controls sel span8">   
											<?php 
											GetCategoryValue('StockType','StockType','','','','','',1,''); 
											?>
										</div> 
									</div>
								</div> 
							</div>
							<div class="form-row row-fluid">
								<div class="span12">
									<div class="row-fluid">
										<label class="form-label span4" for="normal">Stock Name</label>
										<div class="span8 controls sel">
										<select tabindex="2" class="nostyle" name="StockId" id="StockId" style="width:100%;" onchange="showdetail(this.value,'PurchaseDiv','PurchaseDiv')">
										<option></option>
										</select>
										</div>
									</div>
								</div>
							</div>	
							<div id="PurchaseDiv"></div>
							<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
							<input type="hidden" name="Token" value="<?php echo $Token; ?>" readonly />
							<input type="hidden" name="PurchaseType" value="Stock" readonly />
							<input type="hidden" name="Action" value="Purchase" readonly />
							<?php $ButtonContent="Transfer"; ActionButton($ButtonContent,6); ?>	
						</form>
					</div>
				</div>
				<?php
				}
				
				function Payment($PurchaseId,$Balance,$Token,$TOKEN)
				{
				$USERNAME=$_SESSION['USERNAME'];
				$USERTYPE=$_SESSION['USERTYPE'];
				$USERTYPEID=$_SESSION['USERTYPEID'];
				$CURRENCY=$_SESSION['CURRENCY'];
				if($USERTYPE=="Webmaster")
				{
				$queryAccount="select AccountId,AccountName,(OpeningBalance+AccountBalance) as TotalAccountBalance from accounts 
					order by AccountName ";
				}
				else
				{
				$queryAccount="select AccountId,AccountName,(OpeningBalance+AccountBalance) as TotalAccountBalance from accounts where 
					ManagedBy='$USERTYPEID' order by AccountName ";												
				}
				$checkAccount=mysqli_query($CONNECTION,$queryAccount);
				while($rowAccount=mysqli_fetch_array($checkAccount))
				{
					$SelectAccountId=$rowAccount['AccountId'];
					$SelectAccountName=$rowAccount['AccountName'];
					$SelectTotalAccountBalance=round($rowAccount['TotalAccountBalance'],2);
					$ListAccount.="<option value=\"$SelectAccountId\">$SelectAccountName Balance : $SelectTotalAccountBalance INR</option>";
				}
				?>
				<div class="box chart gradient">
					<div class="title">
						<h4>
							<span>Payment</span>
						</h4>
						<a href="#" class="minimize">Minimize</a>
					</div>
					<div class="content" style="padding:5px;">
					<div class="alert alert-info"><b>Balance amount : <?php echo "$Balance $CURRENCY"; ?></b></div>
						<?php
						if($Balance>0)
						{
						?>
						<form class="form-horizontal" action="Action" name="PurchasePayment" id="PurchasePayment" method="Post">
							<div class="form-row row-fluid">
								<div class="span12">
									<div class="row-fluid">
										<label class="form-label span4" for="normal">Amount Paid</label>
										<input tabindex="21" class="span8" id="AmountPaid" type="text" name="AmountPaid" placeholder="less than <?php echo "$Balance $CURRENCY"; ?>" />
									</div>
								</div>
							</div>
							<div class="form-row row-fluid">
								<div class="span12">
									<div class="row-fluid">
										<label class="form-label span4" for="normal">Account</label>
										<div class="span8 controls sel">
										<select tabindex="22" class="nostyle" name="Account" id="Account" style="width:100%;">
										<option></option>
										<?php
										echo $ListAccount;
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
										<input tabindex="23" class="span8" id="DOP" type="text" name="DOP" readonly />
									</div>
								</div>
							</div>
							<div class="form-row row-fluid">
								<div class="span12">
									<div class="row-fluid">
										<label class="form-label span4" for="normal">Remarks</label>
										<div class="span8 controls-textarea">
										<textarea tabindex="24" name="PaymentRemarks" id="PaymentRemarks"></textarea>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
								<input type="hidden" name="Action" value="PurchasePayment" readonly>
								<input type="hidden" name="PurchaseId" value="<?php echo $PurchaseId; ?>" readonly>
								<input type="hidden" name="Token" value="<?php echo $Token; ?>" readonly>
								<?php $ButtonContent="Add Payment"; ActionButton($ButtonContent,25); ?>
						</form>
						<?php
						}
						?>
					</div>
				</div>
				<?php
				}
				
				$query2="select PurchaseStatus from purchase where Token='$Token' ";
				$check2=mysqli_query($CONNECTION,$query2);
				$count2=mysqli_num_rows($check2);
				$row2=mysqli_fetch_array($check2);
				$PurchaseStatus=$row2['PurchaseStatus'];
				if($PurchaseStatus=='Active' || $PurchaseStatus=='Cancelled')
				{
					$query3="select SupplierName,DOP,PurchaseId,Total,Paid from purchase,supplier where
						purchase.SupplierId=supplier.SupplierId and
						Token='$Token' ";
					$check3=mysqli_query($CONNECTION,$query3);
					$row3=mysqli_fetch_array($check3);
					$SupplierName=$row3['SupplierName'];
					$DOP=date("d M Y,h:ia",$row3['DOP']);
					$PurchaseId=$row3['PurchaseId'];
					$TotalAmount=round($row3['Total'],2);
					$Paid=round($row3['Paid'],2);
					$Balance=$TotalAmount-$Paid;
					
					if($Paid>0)
					{
						$query4="select TransactionAmount,AccountName,TransactionDate,TransactionId from transaction,accounts where
								TransactionHead='Purchase' and
								transaction.TransactionFrom=accounts.AccountId and 
								TransactionHeadId='$PurchaseId' and
								TransactionStatus='Active' ";
						$check4=mysqli_query($CONNECTION,$query4);
						while($row4=mysqli_fetch_array($check4))
						{
							$TransactionAmount=round($row4['TransactionAmount'],2);
							$AccountName=$row4['AccountName'];
							$TransactionId=$row4['TransactionId'];
							$TransactionDate=date("d M Y,h:ia",$row4['TransactionDate']);
							$DeletePayment="<a href=#DeletePurchasePaymentName onclick=\"showdetail('$TransactionId','DeletePurchasePayment','DeletePurchasePayment')\"><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
							$ListTransactions.="<tr>
												<td>$TransactionId</td>
												<td>$TransactionAmount $CURRENCY</td>
												<td>$AccountName</td>
												<td>$TransactionDate</td>
												<td>$DeletePayment</td>
												</tr>";
						}
					}
				}	
				?>
				
                <div class="row-fluid">
                    <div class="span4">
					<?php
					if($PurchaseStatus=="" || $PurchaseStatus=="Started")
					Purchase($Token,$TOKEN);
					else
					Payment($PurchaseId,$Balance,$Token,$TOKEN);
					?>
					<form class="form-horizontal" action="ActionDelete" name="DeletePurchasePaymentForm" id="DeletePurchasePaymentForm" method="Post">
						<div id="DeletePurchasePayment">
						</div>						
					</form>
                    </div>
					
					<div class="span8">
					<?php
						
						$query1="select StockName,Unit,PurchasePrice,Quantity,OtherInfo,PurchaseListId from purchaselist,stock where
								purchaselist.UniqueId=stock.StockId and
								Token='$Token' ";
						$check1=mysqli_query($CONNECTION,$query1);
						$count1=mysqli_num_rows($check1);
						$ListItems="";
						$FinalPurchasePrice=0;
						if($count1>0)
						{
							while($row1=mysqli_fetch_array($check1))
							{
								$ListPurchaseListId=$row1['PurchaseListId'];
								$ListOtherInfo=$row1['OtherInfo'];
								$ListStockName=$row1['StockName'];
								$ListUnit=$row1['Unit'];
								if($ListUnit!=0)
								$ListUnitName=GetCategoryValueOfId($ListUnit,'Unit');
								$ListPurchasePrice=round($row1['PurchasePrice'],2);
								$ListQuantity=round($row1['Quantity'],2);
								if($ListQuantity==0)
								{
									$TotalPurchasePrice=$ListPurchasePrice;
									$ListQuantityName="-";
								}
								else
								{
									$TotalPurchasePrice=$ListQuantity*$ListPurchasePrice;
									$ListQuantityName="$ListQuantity $ListUnitName";
								}
								$ListItems.="<tr>
										<td>$ListStockName<br>$ListOtherInfo</td>
										<td>$ListQuantityName</td>
										<td>$ListPurchasePrice $CURRENCY</td>
										<td>$TotalPurchasePrice $CURRENCY</td>";
								if($PurchaseStatus=="" || $PurchaseStatus=="Started")
								$ListItems.="<td><a href=Purchase/$Token/Delete/$ListPurchaseListId $ConfirmProceed><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a></td>";
								$ListItems.="</tr>";
								$FinalPurchasePrice+=$TotalPurchasePrice;
							}
							?>
							
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Purchase Cart</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content clearfix" style="padding:5px;">
								<?php 
								if($PurchaseStatus=='Active' || $PurchaseStatus=='Cancelled')
								{
								?>
								<table cellpadding="0" cellspacing="0" border="0" class="responsive table table-bordered" width="100%">
								<thead>
								<tr>
									<td>Purchased From <b><?php echo $SupplierName; ?> on <?php echo $DOP; ?></b></td>
								</tr>
								</thead>
								</table>
								<?php
								}
								?>
							
								<table cellpadding="0" cellspacing="0" border="0" class="responsive table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Item Name</th>
											<th>Quantity</th>
											<th>Price</th>
											<th>Total Price</th>
										<?php if($PurchaseStatus=="" || $PurchaseStatus=="Started") { ?>
											<th><span class="icomoon-icon-cancel tip" title="Delete"></span></th>
										<?php } ?>
										</tr>
									</thead>
									<tbody>
									<?php
										echo $ListItems; 
									?>
									<thead>
										<tr>
											<th colspan="3">Grand Total</th>
											<th><?php echo "$FinalPurchasePrice $CURRENCY"; ?></th>
											<?php if($PurchaseStatus=="" || $PurchaseStatus=="Started") echo "<th></th>"; ?>
										</tr>
									</thead>
									</tbody>
								</table>
								<?php						
								if($PurchaseStatus=='Active' && $Paid>0)
								{
								?>
								<table cellpadding="0" cellspacing="0" border="0" class="responsive table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Receipt No</th>
											<th>Amount</th>
											<th>Payment From</th>
											<th>Date</th>
											<th><span class="icomoon-icon-cancel tip" title="Delete"></span></th>
										</tr>
									</thead>
									<tbody>
									<?php
										echo $ListTransactions; 
									?>
									</tbody>
								</table>									
								<?php
								}
								?>
							</div>
						</div>
						<?php	
						}
						if($PurchaseStatus=="Started")
						{
							$check3=mysqli_query($CONNECTION,"select * from supplier where SupplierStatus='Active' order by SupplierName ");
							$count3=mysqli_num_rows($check3);
							$SupplierList="";
							if($count3>0)
							{
								while($row3=mysqli_fetch_array($check3))
								{
									$ComboSupplierName=$row3['SupplierName'];
									$ComboSupplierId=$row3['SupplierId'];
									$SupplierList.="<option value=$ComboSupplierId>$ComboSupplierName</option>";
								}
							}
					?>
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Purchase Checkout</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content clearfix" style="padding:5px;">
								<form class="form-horizontal" action="Action" name="PurchaseCheckOut" id="PurchaseCheckOut" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Supplier</label>
												<div class="span8 controls sel">
												<select tabindex="11" name="SupplierId" id="SupplierId" class="nostyle" style="width:100%;">
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
												<label class="form-label span4" for="normal">Date of Purchase</label>
												<input tabindex="12" class="span8" id="PurchaseDate" type="text" name="PurchaseDate" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Remarks</label>
												<div class="span8 controls-textarea">
												<textarea tabindex="13" name="Remarks" id="Remarks"></textarea>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<input type="hidden" name="Action" value="PurchaseCheckOut" readonly>
										<input type="hidden" name="PurchaseType" value="Stock" readonly>
										<input type="hidden" name="Token" value="<?php echo $Token; ?>" readonly>
									<?php $ButtonContent="CheckOut"; ActionButton($ButtonContent,14); ?>
								</form>
							</div>
						</div>					
					<?php
						}
					?>
					</div>
                </div>  
            </div>
        </div>
		
		
<script type="text/javascript">
	var cSelect; 
	$(document).ready(function() {
		$("#StockType").select2(); 
		$('#StockType').select2({placeholder: "Select"}); 	
		$("#SupplierId").select2(); 
		$('#SupplierId').select2({placeholder: "Select"}); 	
		$("#Account").select2(); 
		$('#Account').select2({placeholder: "Select"}); 	
		if($('#PurchaseDate').length) {
		$('#PurchaseDate').datetimepicker({ dateFormat: 'dd-mm-yy' });
		}
		cSelect = $("#StockId").select2(); 
		$("#StockType").change(function() { 
			cSelect.select2("val", ""); 
			$("#StockId").load("GetData/GetStockId/" + $("#StockType").val());
		}); 
	
		if($('#DOP').length) {
		$('#DOP').datetimepicker({ dateFormat: 'dd-mm-yy' });
		}
		$('#StockId').select2({placeholder: "Select"}); 	
		$("input, textarea, select").not('.nostyle').uniform();
		$("#Purchase").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				StockType: {
					required: true,
				},
				StockId: {
					required: true,
				},
				Quantity: {
					required: true,
					remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=Quantity",
				},
				PurchasePrice: {
					required: true,
					remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=PurchasePrice",
				}
			},
			messages: {
				StockType: {
					required: "Please select this!!",
				},
				StockId: {
					required: "Please enter this!!",
				},
				Quantity: {
					required: "Please enter this!!",
					remote: jQuery.format("Numeric & greater than 0!!"),
				},
				PurchasePrice: {
					required: "Please enter this!!",
					remote: jQuery.format("Numeric & greater than 0!!"),
				}
			}   
		});
		$("#PurchaseCheckOut").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				SupplierId: {
					required: true,
				},
				PurchaseDate: {
					required: true,
				}
			},
			messages: {
				SupplierId: {
					required: "Please select this!!",
				},
				PurchaseDate: {
					required: "Please enter this!!",
				}
			}   
		});
		$("#PurchasePayment").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				Account: {
					required: true,
				},
				AmountPaid: {
					required: true,
					remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=AmountPaid"
				},
				DOP: {
					required: true,
				},
				PaymentRemarks: {
					required: true,
				}
			},
			messages: {
				Account: {
					required: "Please select this!!",
				},
				AmountPaid: {
					required: "Please enter this!!",
					remote: jQuery.format("Numeric & greater than zero!!"),
				},
				DOP: {
					required: "Please select this!!",
				},
				PaymentRemarks: {
					required: "Please enter this!!",
				}
			}   
		});
		$("#DeletePurchasePaymentForm").validate({
			rules: {
				DeletePurchasePaymentPassword: {
					required: true,
				}
			},
			messages: {
				DeletePurchasePaymentPassword: {
					required: "Please enter this!!",
				}
			}   
		});
	});
</script>
<?php
include("Template/Footer.php");
?>