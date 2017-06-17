<?php
$PageName="StockTransfer";
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
                <?php $BreadCumb="Stock Transfer"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				<?php
					$GETStockType=isset($_GET['StockType']) ? $_GET['StockType'] : '';
					$GETStockId=isset($_GET['StockId']) ? $_GET['StockId'] : '';		
					$GETStockAssignId=isset($_GET['StockAssignId']) ? $_GET['StockAssignId'] : '';						
					$count=0;	
					$ListStockOption="";					
					$StockSelected=$ValidStock=$TransferedStockName="";
					$query="select StockId,StockName from stock where StockType='$GETStockType' order by StockName ";
					$check=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($check);
					if($count>0)
					{
						while($row=mysqli_fetch_array($check))
						{
							$StockName=$row['StockName'];
							$StockId=$row['StockId'];
							if($GETStockId==$StockId)
							{
								$StockSelected="selected";
								$ValidStock=1;
								$TransferedStockName=$StockName;
							}
							else
							$StockSelected="";
							$ListStockOption.="<option value=$StockId $StockSelected>$StockName</option>";
						}
						
						if($GETStockAssignId!="")
						{
							$query3="select StockName,(Quantity-Returning) as Quantity,MasterEntryValue from stockassign,stock,masterentry where 
								stock.StockId=stockassign.StockId and 
								StockAssignId='$GETStockAssignId' and
								stockassign.StockId='$GETStockId' and
								stockassign.AssignTo=masterentry.MasterEntryId and 
								StockAssignStatus='Active' and
								StockStatus='Active' ";
							$check3=mysqli_query($CONNECTION,$query3);
							$count3=mysqli_num_rows($check3);
							if($count3>0)
							{
								$row3=mysqli_fetch_array($check3);
								$TransferQuantity=round($row3['Quantity'],2);
								$TransferStockName=$row3['StockName'];
								$TransferFrom=$row3['MasterEntryValue'];
							}
						}

						$query1="select StockAssignId,StockName,MasterEntryValue,Quantity,(Quantity-Returning) as TotalStock,AssignToDetail,DOT from stockassign,masterentry,stock 
								where
								stock.StockId=stockassign.StockId and
								stockassign.AssignTo=masterentry.MasterEntryId and
								stockassign.StockId='$GETStockId' 
								order by DOT desc ";
						$check1=mysqli_query($CONNECTION,$query1);
						$count1=mysqli_num_rows($check1);
						$DATA=array();
						$QA=array();
						if($count1>0)
						{
							while($row1=mysqli_fetch_array($check1))
							{
								$ListStockName=$row1['StockName'];
								$ListStockAssignId=$row1['StockAssignId'];
								$ListAssignTo=$row1['MasterEntryValue'];
								$ListQuantity=round($row1['Quantity'],2);
								$ListTotalStock=round($row1['TotalStock'],2);
								$ListAssignToDetail=$row1['AssignToDetail'];
								$ListDOT=date("d M Y",$row1['DOT']);
								
								if($ListAssignTo=="Staff")
								{
									$check2=mysqli_query($CONNECTION,"select StaffName,MasterEntryValue from staff,masterentry where StaffId='$ListAssignToDetail' and staff.StaffPosition=masterentry.MasterEntryId ");
									$row2=mysqli_fetch_array($check2);
									$StaffName=$row2['StaffName'];
									$StaffPosition=$row2['MasterEntryValue'];
									$Detail="$StaffName ($StaffPosition)";
								}
								elseif($ListAssignTo=="Location")
								{
									$check2=mysqli_query($CONNECTION,"select LocationName,CalledAs from location where LocationId='$ListAssignToDetail' ");
									$row2=mysqli_fetch_array($check2);
									$LocationName=$row2['LocationName'];
									$CalledAs=$row2['CalledAs'];		
									$Detail="$LocationName ($CalledAs)";
								}
								elseif($ListAssignTo=="Student")
								{
									$query2="select StudentName,FatherName from registration,admission where 
									admission.AdmissionId='$ListAssignToDetail' and
									registration.RegistrationId=admission.RegistrationId ";
									$check2=mysqli_query($CONNECTION,$query2);
									$row2=mysqli_fetch_array($check2);
									$StudentName=$row2['StudentName'];
									$Detail="$StudentName";
								}
								elseif($ListAssignTo=="Other")
								{
									$query2="select MasterEntryValue from masterentry where MasterEntryId='$ListAssignToDetail' ";
									$check2=mysqli_query($CONNECTION,$query2);
									$row2=mysqli_fetch_array($check2);
									$OtherDetail=$row2['MasterEntryValue'];
									$Detail="$OtherDetail";
								}
								$TransferId="$ListStockAssignId";
								$Transfer="<a href=StockTransfer/$GETStockType/$GETStockId/$TransferId><span class=\"iconic-icon-transfer tip\" title=\"Transfer\"></span></a>";
								$QA[]=array($ListStockAssignId,$ListAssignTo,$Detail,$ListQuantity,$ListTotalStock,$ListDOT,$Transfer);								
							}	
						}
							$DATA['aaData']=$QA;
							$fp = fopen('plugins/Data/data1.txt', 'w');
							fwrite($fp, json_encode($DATA));
							fclose($fp);
					}
				?>
				
				<div class="row-fluid">
					<div class="span4">
						<?php if($GETStockAssignId!="" && $count3==1 && $TransferQuantity>0) { ?>
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span><?php echo "Transfer Stock \"$TransferStockName\" from \"$TransferFrom\" "; ?></span>
								</h4>
								<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="TransferStock" id="TransferStock" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Transfer To</label>
												<div class="span8 controls sel">
													<?php GetCategoryValue('AssignTo','AssignTo','','','','','',10,''); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Detail</label>
												<div class="span8 controls sel">
												<select tabindex="11" class="nostyle" name="AssignToDetail" id="AssignToDetail" style="width:100%;">
												<option></option>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Quantity</label>
												<input tabindex="12" class="span8" id="TransferQuantity" type="number" name="TransferQuantity" placeholder="Less than <?php echo $TransferQuantity; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Transfer Date</label>
												<input tabindex="13" class="span8" id="DOT" type="text" name="DOT" readonly />
											</div>
										</div>
									</div>	
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="Action" value="TransferIndividualStock" readonly>
									<input type="hidden" name="TransferType" value="StockTransfer" readonly>
									<input type="hidden" name="StockId" value="<?php echo $GETStockId; ?>" readonly>
									<input type="hidden" name="StockTypeId" value="<?php echo $GETStockType; ?>" readonly>
									<input type="hidden" name="StockAssignId" value="<?php echo $GETStockAssignId; ?>" readonly>
									<?php $ButtonContent="Transfer"; ActionButton($ButtonContent,14); ?>	
								</form>							
							</div>
						</div>
						<?php } elseif($GETStockAssignId!="" && $count3==1 && $TransferQuantity==0) { ?>
						<div class="alert alert-error">This stock is not available to transfer!!</div>
						<?php } ?>
						
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Select</span>
								</h4>
								<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="ReportAction" name="StockTransferForm" id="StockTransferForm" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="normal">Stock Type</label> 
												<div class="controls sel span8">   
													<?php 
													GetCategoryValue('StockType','StockType',$GETStockType,'','','','',1,''); 
													?>
												</div> 
											</div>
										</div> 
									</div>								
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="normal">Stock Name</label> 
												<div class="controls sel span8">    
												<select tabindex="2" name="StockId" id="StockId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php if($ValidStock==1)
												echo $ListStockOption;
												?>
												</select>
												</div> 
											</div>
										</div> 
									</div>	
									<input type="hidden" name="Action" value="StockTransfer" readonly>
									<?php $ButtonContent="Proceed"; ActionButton($ButtonContent,3); ?>									
								</form>
							</div>
						</div>
					</div>
					<div class="span8">
						<?php if($ValidStock==1) { ?>
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Transfer of Stock "<?php echo $TransferedStockName ?>" Detail</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad clearfix">
								<table id="StockTransferTable" class="responsive table table-bordered dynamicTable display" cellpadding="0" cellspacing="0" border="0" width="100%">
									<thead>
									  <tr>
										<th>Transfer Id</th>
										<th>To</th>
										<th>Detail</th>
										<th>Quantity</th>
										<th>Available</th>
										<th>Date</th>
										<th>Transfer</th>
									  </tr>
									</thead>
									<tbody>
									</tbody>
								</table>							
							</div>
						</div>
						<?php } elseif($GETStockId!="" && $ValidStock!=1) { ?>
						<div class="alert alert-error">This is not a valid Stock!!</div>
						<?php } else { ?>
						<div class="alert alert-info">Please select Stock type & Stock name!!</div>
						<?php } ?>
					</div>
				</div>
            </div>
        </div>
	
<script type="text/javascript">
var cSelect;
var cSelect2;
	$(document).ready(function() {
		$('#StockTransferTable').dataTable({
			"sPaginationType": "two_button",
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,  
			"bProcessing": true,
			"bDeferRender": true,
			"sAjaxSource": "plugins/Data/data1.txt",
			"fnInitComplete": function(oSettings, json) {
			  $('.dataTables_filter>label>input').attr('id', 'search');
			}
		});	
		$("#StockType").select2(); 
		$('#StockType').select2({placeholder: "Select"}); 	
			
		$(document).ready(function() { 
			cSelect = $("#StockId").select2(); 
			$("#StockType").change(function() { 
				cSelect.select2("val", ""); 
				$("#StockId").load("GetData/GetStockId/" + $("#StockType").val());
			}); 
		});	
		$('#StockId').select2({placeholder: "Select"}); 
		if($('#DOT').length) {
		$('#DOT').datetimepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		
		$("#AssignTo").select2(); 
		$('#AssignTo').select2({placeholder: "Select"}); 
		cSelect2 = $("#AssignToDetail").select2(); 
		$("#AssignTo").change(function() { 
			cSelect2.select2("val", ""); 
			$("#AssignToDetail").load("GetData/GetAssignToDetail/" + $("#AssignTo").val());
		}); 
		$('#AssignToDetail').select2({placeholder: "Select"});	
		
		$("#StockTransferForm").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				StockType: {
					required: true,
				},
				StockId: {
					required: true,
				}
			},
			messages: {
				StockType: {
					required: "Please select this!!",
				},
				StockId: {
					required: "Please select this!!",
				}
			}   
		});
	$("#TransferStock").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			AssignTo: {
				required: true,
			},
			AssignToDetail: {
				required: true,
			},
			TransferQuantity: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=TransferQuantity"
			},
			DOT: {
				required: true,
			}
		},
		messages: {
			AssignTo: {
				required: "Please select this!!",
			},
			AssignToDetail: {
				required: "Please select this!!",
			},
			TransferQuantity: {
				required: "Please enter this!!",
				remote: jQuery.format("Numeric allowed with greater than zero!!")
			},
			DOT: {
				required: "Please select this!!",
			}
		}   
	});
	});
</script>
<?php
include("Template/Footer.php");
?>