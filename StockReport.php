<?php
$PageName="StockReport";
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
                <?php $BreadCumb="Stock Report"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); 
				
				$GETAssignTo=isset($_GET['AssignTo']) ? $_GET['AssignTo'] : '';
				$StockDate=isset($_GET['StockDate']) ? $_GET['StockDate'] : '';
				if($StockDate=="")
				$StockDate=$DDMMYYYY;
				$StockDate.=" 23:59";
				$StockDate=strtotime($StockDate);
				if($StockDate=="")
				$StockDateName=date("d-m-Y",$Date);
				else
				$StockDateName=date("d-m-Y",$StockDate);
				$GETAssignToDetail=isset($_GET['AssignToDetail']) ? $_GET['AssignToDetail'] : '';
				$ListOption=$Selected=$AssignToName=$SelectedName=$count1=$PrintData="";
				if($GETAssignTo!="")
				{	
					$AssignToName=GetCategoryValueOfId($GETAssignTo,'AssignTo');
					if($AssignToName=="Room")
					{
						$query="select LocationName,CalledAs,LocationId from location where LocationStatus='Active' order by LocationName ";
						$check=mysqli_query($CONNECTION,$query);
						while($row=mysqli_fetch_array($check))
						{
							$LocationName=$row['LocationName'];
							$CalledAs=$row['CalledAs'];
							if($CalledAs==$LocationName)
							$CalledAsName="";
							else
							$CalledAsName="($CalledAs)";
							$LocationId=$row['LocationId'];
							if($GETAssignToDetail==$LocationId)
							{
								$Selected="Selected";
								$SelectedName="$LocationName $CalledAsName";
								$Valid=1;
							}
							else
							$Selected="";
							$ListOption.="<option value=\"$LocationId\" $Selected>$LocationName ($CalledAs)</option>";
						}
					}
					elseif($AssignToName=="Staff")
					{
						$query="select StaffName,MasterEntryValue,StaffId from staff,masterentry where
							staff.StaffPosition=masterentry.MasterEntryId and
							StaffStatus='Active' order by StaffPosition,StaffName ";
						$check=mysqli_query($CONNECTION,$query);
						while($row=mysqli_fetch_array($check))
						{
							$StaffName=$row['StaffName'];
							$StaffPosition=$row['MasterEntryValue'];
							$StaffId=$row['StaffId'];
							if($GETAssignToDetail==$StaffId)
							{
								$Selected="Selected";
								$SelectedName="$StaffName";
								$Valid=1;
							}
							else
							$Selected="";
							$ListOption.="<option value=\"$StaffId\" $Selected>$StaffName ($StaffPosition)</option>";
						}
					}
					elseif($AssignToName=="Student")
					{
						$query="select ClassName,SectionName,StudentName,FatherName,Mobile,admission.AdmissionId from admission,registration,class,section,studentfee where 
								studentfee.AdmissionId=admission.AdmissionId and
								studentfee.Session='$CURRENTSESSION'
								and registration.RegistrationId=admission.RegistrationId
								and class.ClassId=section.ClassId
								and registration.Status='Studying' group by studentfee.AdmissionId
								order by StudentName,registration.SectionId";
						$check=mysqli_query($CONNECTION,$query);
						while($row=mysqli_fetch_array($check))
						{
							$StudentName=$row['StudentName'];
							$FatherName=$row['FatherName'];
							$ClassName=$row['ClassName'];
							$SectionName=$row['SectionName'];
							$Mobile=$row['Mobile'];
							$AdmissionId=$row['AdmissionId'];
							if($GETAssignToDetail==$AdmissionId)
							{
								$Selected="Selected";
								$SelectedName="$StudentName $FatherName";
								$Valid=1;
							}
							else
							$Selected="";
							$ListOption.="<option value=\"$AdmissionId\" $Selected>$StudentName - $FatherName ($ClassName $SectionName)</option>";		
						}
					}
					elseif($AssignToName=="Other")
					{
						$query="select MasterEntryId,MasterEntryValue from masterentry where
							MasterEntryName='OtherAssignTo' and MasterEntryStatus='Active' ";
						$check=mysqli_query($CONNECTION,$query);
						while($row=mysqli_fetch_array($check))
						{
							$MasterEntryId=$row['MasterEntryId'];
							$MasterEntryValue=$row['MasterEntryValue'];
							if($GETAssignToDetail==$MasterEntryId)
							{
								$Selected="Selected";
								$SelectedName="$MasterEntryValue";
								$Valid=1;
							}
							else
							$Selected="";
							$ListOption.="<option value=\"$MasterEntryId\" $Selected>$MasterEntryValue</option>";		
						}										
					}

					$query111="select MasterEntryValue,MasterEntryId from masterentry where MasterEntryName='Unit' ";
					$check111=mysqli_query($CONNECTION,$query111);
					while($row111=mysqli_fetch_array($check111))
					{
						$UnitNameArray[]=$row111['MasterEntryValue'];
						$UnitIdArray[]=$row111['MasterEntryId'];
					}
					
					if($GETAssignToDetail=="")
					{
					$query1="select StockName,SUM(Quantity-Returning) as TotalStock,Unit,stock.StockId,MasterEntryValue from stockassign,stock,masterentry 
							where
							stock.StockId=stockassign.StockId and
							stock.StockType=masterentry.MasterEntryId and 
							stockassign.AssignTo='$GETAssignTo' and
							DOT<='$StockDate'
							group by stockassign.StockId order by StockName ";
					}
					else
					{
					$query1="select StockName,SUM(Quantity-Returning) as TotalStock,Unit,stock.StockId,MasterEntryValue from stockassign,stock,masterentry 
							where
							stock.StockId=stockassign.StockId and
							stock.StockType=masterentry.MasterEntryId and 
							stockassign.AssignTo='$GETAssignTo' and
							stockassign.AssignToDetail='$GETAssignToDetail' and
							DOT<='$StockDate'
							group by stockassign.StockId order by StockName ";						
					}
					
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					$DATA=array();
					$QA=array();
					$PrintData="";
					while($row1=mysqli_fetch_array($check1))
					{
						$ListStockName=$row1['StockName'];
						$ListStockType=$row1['MasterEntryValue'];
						$ListStockId=$row1['StockId'];
						$ListUnit=$row1['Unit'];
						if($ListUnit!="")
						{
							$ListUnitSearchIndex=array_search($ListUnit,$UnitIdArray);
							$ListUnitName=$UnitNameArray[$ListUnitSearchIndex];
						}
						else
						$ListUnitName="";
						$ListTotalStock=round($row1['TotalStock'],2);
						if($ListTotalStock>0)
						{
							$PrintData.="<tr>
									<td>$ListStockName</td>
									<td>$ListStockType</td>
									<td>$ListTotalStock $ListUnitName</td>
								</tr>";
							$ListTotalStock="$ListTotalStock $ListUnitName";
							$QA[]=array($ListStockName,$ListStockType,$ListTotalStock,$ListTotalStock);	
						}
					}	
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);	
					
				}
				?>
                <div class="row-fluid">
                    <div class="span3">				
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Select</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="ReportAction" name="StockReportForm" id="StockReportForm" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="normal">Select One</label> 
												<div class="controls sel span8">   
													<?php 
													GetCategoryValue('AssignTo','AssignTo',$GETAssignTo,'','','','',1,''); 
													?>
												</div> 
											</div>
										</div> 
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Detail</label>
												<div class="span8 controls sel">
												<select tabindex="2" class="nostyle" name="AssignToDetail" id="AssignToDetail" style="width:100%;">
												<option></option>
												<?php if($GETAssignTo!="") 
												echo $ListOption;
												?>
												</select>
												</div>
											</div>
										</div>
									</div>	
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Stock Date</label>
												<input tabindex="3" class="span8" id="StockDate" type="text" name="StockDate" value="<?php echo $StockDateName; ?>" readonly />
											</div>
										</div>
									</div>
									<?php
									$ButtonContent="Get Detail"; ActionButton($ButtonContent,4); ?>
									<input type="hidden" name="Action" value="StockReport" readonly>	
								</form>
                            </div>
                        </div>
                    </div>
					<div class="span9">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Stock List "<?php echo "$AssignToName - $SelectedName on $StockDateName"; ?>"</span>
									<?php if($count1>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintStockReportList" readonly>
										<input type="hidden" name="HeadingName" value="PrintStockReportHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Stock Report List"></button>
										</form>
									</div>
									<?php } ?>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad clearfix">
							<?php
							$Print1="<table id=\"StockReportTable\" class=\"responsive dynamicTable display table table-bordered\">
								<thead>
								  <tr>
									<th>Stock Name</th>
									<th>Stock Type</th>
									<th>Available</th>";
									echo $Print1;
								  $Print3="</tr>
								</thead>
								<tbody>";
								echo $Print3;
								$Print4="</tbody>
								</table>";
								echo $Print4;
								$Print="$Print1 $PrintData $Print4";
								$_SESSION['PrintStockReportList']=$Print;
								$PrintStockReportHeading="Stock List \"$AssignToName - $SelectedName\" on $StockDateName";
								$_SESSION['PrintStockReportHeading']=$PrintStockReportHeading;
							?>
							</div>
						</div>
					</div>
					
				</div>
            </div>
        </div>

<script type="text/javascript">
	var cSelect; 
	$(document).ready(function() {
		$('#StockReportTable').dataTable({
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
	
		$("#AssignTo").select2(); 
		$('#AssignTo').select2({placeholder: "Select"}); 	
		
		cSelect = $("#AssignToDetail").select2(); 
		$("#AssignTo").change(function() { 
			cSelect.select2("val", ""); 
			$("#AssignToDetail").load("GetData/GetAssignToDetail/" + $("#AssignTo").val());
		}); 
		$('#AssignToDetail').select2({placeholder: "Select"});	
		
		$("input, textarea, select").not('.nostyle').uniform();
		if($('#StockDate').length) {
		$("#StockDate").datepicker({ yearRange: "-5:+0", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		$("#StockReportForm").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				AssignTo: {
					required: true,
				},
				StockDate: {
					required: true,
				}
			},
			messages: {
				AssignTo: {
					required: "Please select this!!",
				},
				StockDate: {
					required: "Please select this!!",
				}
			}   
		});
	});
</script>
<?php
include("Template/Footer.php");
?>