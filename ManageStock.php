<?php
$PageName="ManageStock";
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
                <?php $BreadCumb="Manage Stock"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$count1=0;
				$StockType=$StockName=$OpeningStock=$Unit=$ButtonContent=$ButtonContentSet=$AddButton=$UpdateStockId=$UpdateStockTypeId="";
				if($Action=="UpdateStock" || $Action=="TransferStock")
				{
					$StockTypeId=isset($_GET['UniqueId']) ? $_GET['UniqueId'] : '';
					$StockId=isset($_GET['SUniqueId']) ? $_GET['SUniqueId'] : '';
					$query1="select * from stock,masterentry where stock.StockType=masterentry.MasterEntryId and StockId='$StockId' and StockType='$StockTypeId' and StockStatus='Active' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="UpdateStock")
					{
						$row1=mysqli_fetch_array($check1);
						$StockType=$row1['StockType'];
						$StockName=$row1['StockName'];
						$OpeningStock=round($row1['OpeningStock'],2);
						$Unit=$row1['Unit'];
						if($Unit==0)
						$OpeningStock="";
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=ManageStock><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateStockId=$StockId;
						$UpdateStockTypeId=$StockTypeId;
					}
					elseif($count1>0 && $Action=="TransferStock")
					{
						$row1=mysqli_fetch_array($check1);
						$TransferStockName=$row1['StockName'];
						$TransferStockTypeName=$row1['MasterEntryValue'];
						$TransferOpeningStock=round($row1['OpeningStock'],2);
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add Stock";
				}

				$query="select MasterEntryValue,COUNT(StockId) as Total,MasterEntryId from masterentry,stock
					where MasterEntryName='StockType' and
					masterentry.MasterEntryId=stock.StockType and
					MasterEntryStatus='Active' 
					group by StockType 
					order by MasterEntryValue";
				$result=mysqli_query($CONNECTION,$query);
				$count=mysqli_num_rows($result);
				$DATA=array();
				$QA=array();
				$StockNameDetail=$ListStockType="";
				while($row=mysqli_fetch_array($result))
				{
					$Total=$row['Total'];	
					$MasterEntryValue=$row['MasterEntryValue'];
					$MasterEntryId=$row['MasterEntryId'];
					$ListStockType.="<tr>
						<td></td>
						<td>$Total</td>
					</tr>";
					$StockNameDetail="<a href=ManageStock/ListStock/$MasterEntryId>$MasterEntryValue</a>";
					$QA[]=array($StockNameDetail,$Total);
				}		
				$DATA['aaData']=$QA;
				$fp = fopen('plugins/Data/data1.txt', 'w');
				fwrite($fp, json_encode($DATA));
				fclose($fp);	

				$query55="select MasterEntryName,MasterEntryId,MasterEntryValue from masterentry where MasterEntryStatus='Active' ";
				$check55=mysqli_query($CONNECTION,$query55);
				while($row55=mysqli_fetch_array($check55))
				{
					$MasterEntryNameArray[]=$row55['MasterEntryName'];
					$MasterEntryIdArray[]=$row55['MasterEntryId'];
					$MasterEntryValueArray[]=$row55['MasterEntryValue'];
				}

				if($Action=="ListStock" || $Action=="UpdateStock" || $Action=="TransferStock")
				{
					$ShowStockType=isset($_GET['UniqueId']) ? $_GET['UniqueId'] : '';
					$query2="select StockName,OpeningStock,CurrentStock,MasterEntryValue as StockTypeName,StockId,Unit from stock,masterentry where
						stock.StockType=masterentry.MasterEntryId and
						stock.StockType='$ShowStockType' and
						stock.StockStatus='Active'
						order by StockName ";
					$check2=mysqli_query($CONNECTION,$query2);
					$count2=mysqli_num_rows($check2);
					$DATA2=array();
					$QA2=array();
					$PrintStockList3="";
					while($row2=mysqli_fetch_array($check2))
					{
						$ListStockStockName=$row2['StockName'];
						$ListStockStockId=$row2['StockId'];
						$ListStockOpeningStock=round($row2['OpeningStock'],2);
						$ListStockCurrentStock=round($row2['CurrentStock'],2);
						$ListTotalStock=$ListStockOpeningStock+$ListStockCurrentStock;
						if($ListTotalStock>0)
						$AssignedUnAssigned="important";
						else
						$AssignedUnAssigned="success";
						$ListStockCurrentStock+=$ListStockOpeningStock;
						$ListStockStockTypeName=$row2['StockTypeName'];
						$ListUnit=$row2['Unit'];
						if($ListUnit!=0)
						{
							$SearchIndex=array_search($ListUnit,$MasterEntryIdArray);
							$ListStockUnit=$MasterEntryNameArray[$SearchIndex];
						}
						else
						$ListStockUnit="";
						$TransferId="$ListStockStockId-NotAssigned";
						$DeleteStock="<a href=ManageStock/DeleteStock/$ListStockStockId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
						$TransferStock="<a href=ManageStock/TransferStock/$ShowStockType/$ListStockStockId><span class=\"iconic-icon-transfer tip\" title=\"Transfer\"></span></a>";
						$UpdateStock="<a href=ManageStock/UpdateStock/$ShowStockType/$ListStockStockId><span class=\"icon-edit tip\" title=\"update\"></span></a>";
						
						$ListStockOpeningStock="$ListStockOpeningStock $ListStockUnit";
						$ListTotalStock="<span class=\"badge badge-$AssignedUnAssigned\">$ListTotalStock $ListStockUnit</span>";
						
						$QA2[]=array($ListStockStockName,$ListStockOpeningStock,$ListTotalStock,$TransferStock,$UpdateStock,$DeleteStock);	
						$PrintStockList3.="<tr>
										<Td>$ListStockStockName</td>
										<td>$ListStockOpeningStock</td>
										<td>$ListTotalStock</td>
										</tr>";
					}		
					$DATA2['aaData']=$QA2;
					$fp = fopen('plugins/Data/data2.txt', 'w');
					fwrite($fp, json_encode($DATA2));
					fclose($fp);	
				}
				
				if($Action=="TransferStock" && $count1>0)
				{
					$query10="select StockName,(OpeningStock+CurrentStock) as Quantity from stock where StockId='$StockId' ";
					$check10=mysqli_query($CONNECTION,$query10);
					$row10=mysqli_fetch_array($check10);
					$TransferQuantity=round($row10['Quantity'],2);
				}
				?>		
				
                <div class="row-fluid">
                    <div class="span4">
						<?php if($Action=="TransferStock" && $count1>0) {?>
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Transfer Stock <?php echo "\"$TransferStockTypeName : $TransferStockName\""; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:5px;">
								<?php if($TransferQuantity>0) { ?>
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
									<input type="hidden" name="Action" value="TransferIndividualStock" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="TransferType" value="StockAssign" readonly>
									<input type="hidden" name="StockId" value="<?php echo $StockId; ?>" readonly>
									<input type="hidden" name="StockTypeId" value="<?php echo $StockTypeId; ?>" readonly>
									<?php $ButtonContent="Transfer"; ActionButton($ButtonContent,14); ?>	
								</form>
								<?php } else { ?>
								<div class="alert alert-error">This stock cannot be transfer because no quantity is available to transfer!!</div>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
						
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Manage Stock</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageStock" id="ManageStock" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="normal">Stock Type</label> 
												<div class="controls sel span8">   
													<?php 
													GetCategoryValue('StockType','StockType',$StockType,'','','','',1,''); 
													?>
												</div> 
											</div>
										</div> 
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Stock Name</label>
												<input tabindex="2" class="span8" id="StockName" type="text" name="StockName" value="<?php echo $StockName; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Opening Stock</label>
												<input tabindex="3" class="span8" id="OpeningStock" type="text" name="OpeningStock" value="<?php echo $OpeningStock; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="normal">Unit</label> 
												<div class="controls sel span8">   
													<?php 
													GetCategoryValue('Unit','Unit',$Unit,'','','','',4,''); 
													?>
												</div> 
											</div>
										</div> 
									</div>
										<input type="hidden" name="Action" value="ManageStock" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="StockId" value="<?php echo $UpdateStockId; ?>" readonly>
										<input type="hidden" name="StockTypeId" value="<?php echo $UpdateStockTypeId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ButtonContent,5); ?>
								</form>
                            </div>
                        </div>
                    </div>	
					<div class="span8">
					
						<?php if($Action=="ListStock" || $Action=="UpdateStock" || $Action=="TransferStock") { ?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>List Stock Item of "<?php echo $ListStockStockTypeName; ?>"</span>
									<?php if($count2>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintStockList" readonly>
										<input type="hidden" name="HeadingName" value="PrintStockHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Stock List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<?php
								$PrintStockList1="<table id=\"StockListTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Stock Name</th>
											<th>Opening Stock</th>
											<th>Un Assigned</th>";
											echo $PrintStockList1;
											echo "<th><span class=\"iconic-icon-transfer\" title=\"Transfer\"></span></th>";
											echo "<th><span class=\"icon-edit tip\" title=\"Update\"></span></th>
											<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>";
										$PrintStockList2="</tr>
									</thead>
									<tbody>";
									echo $PrintStockList2;
									$PrintStockList4="</tbody>
								</table>";
									echo $PrintStockList4;
								$PrintStockList="$PrintStockList1 $PrintStockList2 $PrintStockList3 $PrintStockList4";
								$_SESSION['PrintStockList']=$PrintStockList;
								$PrintStockHeading="Showing List of Stock \"$ListStockStockTypeName\" ";
								$_SESSION['PrintStockHeading']=$PrintStockHeading;
								?>
							</div>
						</div>						
						<?php } ?>
						
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>List Stock Type</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<table id="StockTypeTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Stock Type</th>
											<th>Total Stock Item</th>
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
var cSelect; 
$(document).ready(function() {
	$('#StockTypeTable').dataTable({
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
	$('#StockListTable').dataTable({
		"sPaginationType": "two_button",
		"bJQueryUI": false,
		"bAutoWidth": false,
		"bLengthChange": false,  
		"bProcessing": true,
		"bDeferRender": true,
		"sAjaxSource": "plugins/Data/data2.txt",
		"fnInitComplete": function(oSettings, json) {
		  $('.dataTables_filter>label>input').attr('id', 'search');
		}
	});	
	$("#StockType").select2(); 
	$('#StockType').select2({placeholder: "Select"}); 
	$("#AssignTo").select2(); 
	$('#AssignTo').select2({placeholder: "Select"}); 
	$("#Unit").select2(); 
	$('#Unit').select2({placeholder: "Select"}); 
	
	cSelect = $("#AssignToDetail").select2(); 
	$("#AssignTo").change(function() { 
		cSelect.select2("val", ""); 
		$("#AssignToDetail").load("GetData/GetAssignToDetail/" + $("#AssignTo").val());
	}); 
	$('#AssignToDetail').select2({placeholder: "Select"});	
	
	if($('#DOT').length) {
	$('#DOT').datetimepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}

	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageStock").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			StockType: {
				required: true,
			},
			StockName: {
				required: true,
			},
			OpeninigStock: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithZero&Id=OpeninigStock"
			}
		},
		messages: {
			StockType: {
				required: "Please select this!!",
			},
			StockName: {
				required: "Please enter this!!",
			},
			OpeninigStock: {
				required: "Please select this!!",
				remote: jQuery.format("Numeric allowed!!")
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