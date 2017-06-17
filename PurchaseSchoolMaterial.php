<?php
$PageName="PurchaseSchoolMaterial";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
include("Include.php");
IsLoggedIn();
$MaterialType=isset($_GET['MaterialType']) ? $_GET['MaterialType'] : '';
if($MaterialType=="" || ($MaterialType!="Books" && $MaterialType!="Uniform" && $MaterialType!="Other") )
$MaterialType="Books";

$Token=isset($_GET['Token']) ? $_GET['Token'] : '';
if($Token=="")
$Token=PasswordGenerator(30);
$query199="select Token from purchaselist where Token='$Token' and MaterialType!='$MaterialType' ";
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
			PurchaseListId='$UniqueId' and
			MaterialType='$MaterialType' ";
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
		$Type="alert-success";
	}
	else
	{
		$Message="Wrong URL!!";
		$Type="alert-success";	
	}
	SetNotification($Message,$Type);
	header("Location:PurchaseSchoolMaterial/$MaterialType/$Token");	
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
                <?php 
				$SchoolMaterialCategory="<a href=PurchaseSchoolMaterial/Books><div class=\"badge badge-important\">Books</div></a>
										<a href=PurchaseSchoolMaterial/Uniform><div class=\"badge badge-info\">Uniform</div></a>
										<a href=PurchaseSchoolMaterial/Other><div class=\"badge badge-success\">Other</div></a>";
				$BreadCumb="Purchase School Material $SchoolMaterialCategory"; BreadCumb($BreadCumb);  ?>
				<?php DisplayNotification(); ?>
				
				<?php
				$query31="select ClassName,ClassId from class where 
					class.ClassStatus='Active' and
					class.Session='$CURRENTSESSION' order by ClassName";
				$ListAllClass="";
				$check31=mysqli_query($CONNECTION,$query31);
				while($row31=mysqli_fetch_array($check31))
				{
					$ComboCurrentClassName=$row31['ClassName'];
					$ComboCurrentClassId=$row31['ClassId'];
					$ClassIdArray[]=$ComboCurrentClassId;
					$ClassNameArray[]=$ComboCurrentClassName;
					$ListAllClass.="<option value=\"$ComboCurrentClassId\">$ComboCurrentClassName</option>";
				}		
				?>
				
				<?php
				function Purchase($Token,$MaterialType,$ListAllClass,$TOKEN)
				{
				?>
					<div class="box chart gradient">
						<div class="title">
							<h4>
								<span>Select <?php echo $MaterialType; ?> Item</span>
							</h4>
							<a href="#" class="minimize">Minimize</a>
						</div>
						<div class="content" style="padding-bottom:0;">
							<form class="form-horizontal" action="Action" name="PurchaseSchoolMaterial" id="PurchaseSchoolMaterial" method="Post">
							<input type="hidden" name="Action" value="Purchase" readonly>
							<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
							<input type="hidden" name="PurchaseType" value="SchoolMaterial" readonly>
							<input type="hidden" name="Token" value="<?php echo $Token; ?>" readonly>
							<input type="hidden" name="MaterialType" value="<?php echo $MaterialType; ?>" readonly>
							<?php 
							if($MaterialType=="Books")
							{
							?>
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="normal">Class</label>
											<div class="span8 controls sel">
												<select tabindex="1" name="ClassId" id="ClassId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListAllClass; ?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="normal">Select One</label>
											<div class="span8 controls sel">
											<select tabindex="1" class="nostyle" name="SchoolMaterialId" id="SchoolMaterialId" style="width:100%;">
											<option></option>
											</select>
											</div>
										</div>
									</div>
								</div>
							<?php
							}
							else
							{
							$query="select SchoolMaterialId,Name from schoolmaterial where 
									SchoolMaterialStatus='Active' and
									SchoolMaterialType='$MaterialType' ";
							$check=mysqli_query($CONNECTION,$query);
							$ListMaterial="";
							while($row=mysqli_fetch_array($check))
							{
								$SchoolMaterialId=$row['SchoolMaterialId'];
								$Name=$row['Name'];
								$ListMaterial.="<option value=\"$SchoolMaterialId\">$Name</option>";
							}
							?>
							<div class="form-row row-fluid">
								<div class="span12">
									<div class="row-fluid">
										<label class="form-label span4" for="normal">Select One</label>
										<div class="span8 controls sel">
										<select tabindex="1" class="nostyle" name="SchoolMaterialId" id="SchoolMaterialId" style="width:100%;">
										<option></option>
										<?php echo $ListMaterial; ?>
										</select>
										</div>
									</div>
								</div>
							</div>
							<?php
							}
							?>
							<div class="form-row row-fluid">
								<div class="span12">
									<div class="row-fluid">
										<label class="form-label span4" for="normal">Quantity</label>
										<input tabindex="3" type="text" class="span8" name="Quantity" id="Quantity">
									</div>
								</div>
							</div>
							<?php $ButtonContent="Add in Cart"; ActionButton($ButtonContent,4); ?>
							</form>
						</div>
					</div>
				<?php
				}
				
				function CancelPurchase($Token,$MaterialType)
				{
				?>
					<div class="box chart gradient">
						<div class="title">
							<h4>
								<span>Cancel Purchase</span>
							</h4>
							<a href="#" class="minimize">Minimize</a>
						</div>
						<div class="content" style="padding-bottom:0;">
							<form class="form-horizontal" action="ActionDelete" name="CancelPurchaseSchoolMaterial" id="CancelPurchaseSchoolMaterial" method="Post">
							<input type="hidden" name="Action" value="CancelPurchaseSchoolMaterial" readonly>
							<input type="hidden" name="Token" value="<?php echo $Token; ?>" readonly>
							<input type="hidden" name="MaterialType" value="<?php echo $MaterialType; ?>" readonly>
							<div class="form-row row-fluid">
								<div class="span12">
									<div class="row-fluid">
										<label class="form-label span4" for="normal">Password</label>
										<input tabindex="5" type="password" class="span8" name="Password" id="Password">
									</div>
								</div>
							</div>
							<?php SetDeleteButton(6); ?>
							</form>
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
					$query3="select SupplierName,DOP,PurchaseId from purchase,supplier where
						purchase.SupplierId=supplier.SupplierId and
						Token='$Token' ";
					$check3=mysqli_query($CONNECTION,$query3);
					$row3=mysqli_fetch_array($check3);
					$SupplierName=$row3['SupplierName'];
					$DOP=date("d M Y,h:ia",$row3['DOP']);
					$PurchaseId=$row3['PurchaseId'];
				}				
				?>                
				<div class="row-fluid">
                    <div class="span4">
					<?php
					if($PurchaseStatus=="" || $PurchaseStatus=="Started")
					Purchase($Token,$MaterialType,$ListAllClass,$TOKEN);
					elseif($PurchaseStatus=="Active")
					CancelPurchase($Token,$MaterialType);
					?>
                    </div>
					<div class="span8">
					<?php		
						$query1="select Name,purchaselist.Quantity,ClassId,Session,PurchaseListId from purchaselist,schoolmaterial where
								purchaselist.UniqueId=schoolmaterial.SchoolMaterialId and
								Token='$Token' ";
						$check1=mysqli_query($CONNECTION,$query1);
						$count1=mysqli_num_rows($check1);
						$ListItems="";
						if($count1>0)
						{
							while($row1=mysqli_fetch_array($check1))
							{
								$ListPurchaseListId=$row1['PurchaseListId'];
								$ListName=$row1['Name'];
								$ListSession=$row1['Session'];
								$ListClassId=$row1['ClassId'];
								if($ClassIdArray!="")
								$ClassSearchIndex=array_search($ListClassId,$ClassIdArray);
								$ListClassName=$ClassNameArray[$ClassSearchIndex];
								$ListQuantity=round($row1['Quantity'],2);
								$ListItems.="<tr>";
								if($MaterialType=="Books")
								$ListItems.="<td>$ListClassName</td><td>$ListSession</td>";
								$ListItems.="<td>$ListName</td>
										<td>$ListQuantity</td>";
								if($PurchaseStatus=="" || $PurchaseStatus=="Started")
								$ListItems.="<td><a href=PurchaseSchoolMaterial/$MaterialType/$Token/Delete/$ListPurchaseListId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a></td>";
								$ListItems.="</tr>";
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
								<?php if($PurchaseStatus=='Cancelled') { ?>
								<Center><button type="submit" class="btn btn-danger" disabled="disabled">Cancelled</button></center><br>
								<?php } ?>
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
										<?php if($MaterialType=="Books") { ?>
											<th>Class</th><th>Session</th>
										<?php } ?>
											<th>Item Name</th>
											<th>Quantity</th>
										<?php if($PurchaseStatus=="" || $PurchaseStatus=="Started") { ?>
											<th><span class="icomoon-icon-cancel tip" title="Delete"></span></th>
										<?php } ?>
										</tr>
									</thead>
									<tbody>
									<?php
										echo $ListItems; 
									?>
									</tbody>
								</table>
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
										<input type="hidden" name="PurchaseType" value="SchoolMaterial" readonly>
										<input type="hidden" name="MaterialType" value="<?php echo $MaterialType; ?>" readonly>
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
$(document).ready(function() {
	$("#ClassId").select2(); 
	$('#ClassId').select2({placeholder: "Select"}); 
	$("#SupplierId").select2(); 
	$('#SupplierId').select2({placeholder: "Select"}); 
	
	<?php if($MaterialType!="Books") { ?>
	$("#SchoolMaterialId").select2();
	$('#SchoolMaterialId').select2({placeholder: "Select"});
	<?php } else {?>
		var cSelect; 
		cSelect = $("#SchoolMaterialId").select2(); 
		$("#ClassId").change(function() { 
			cSelect.select2("val", ""); 
			$("#SchoolMaterialId").load("GetData/GetClassSchoolMaterial/" + $("#ClassId").val());
		}); 
		$('#SchoolMaterialId').select2({placeholder: "Select"});
	<?php } ?>
	
	if($('#PurchaseDate').length) {
	$('#PurchaseDate').datetimepicker({ dateFormat: 'dd-mm-yy' });
	}
	$("input, textarea, select").not('.nostyle').uniform();
	$("#PurchaseSchoolMaterial").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			ClassId: {
				required: true,
			},
			SchoolMaterialId: {
				required: true,
			},
			Session: {
				required: true,
			},
			Quantity: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=Quantity"
			}
		},
		messages: {
			ClassId: {
				required: "Please select this!!",
			},
			Session: {
				required: "Please enter this!!",
			},
			SchoolMaterialId: {
				required: "Please select this!!",
			},
			Quantity: {
				required: "Please enter this!!",
				remote: jQuery.format("Numeric & greater than zero!!")
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
	$("#CancelPurchaseSchoolMaterial").validate({
		rules: {
			Password: {
				required: true,
			}
		},
		messages: {
			Password: {
				required: "Please enter this!!",
			}
		}   
	});
});
</script>		
<?php
include("Template/Footer.php");
?>