<?php
$PageName="Transport";
$FormRequired=1;
$TableRequired=1;
$SearchRequired=1;
$TooltipRequired=1;
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

<?php
$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
$UniqueId=isset($_GET['UniqueId']) ? $_GET['UniqueId'] : '';
$count1=$count2=$count3=0;
$VehicleButtonContent=$VehicleButtonContentSet=$VehicleAddButton=$UpdateVehicleId="";
$FuelButtonContent=$FuelButtonContentSet=$FuelAddButton=$UpdateFuelId="";
$ReadingButtonContent=$ReadingButtonContentSet=$ReadingAddButton=$UpdateVehicleReadingId="";
$VehicleName=$VehicleNumber=$FuelVehicleId=$ReceiptNo=$Quantity=$Rate=$DOF=$FuelRemarks=$ReadingVehicleId=$DOR=$Reading=$ReadingRemarks="";
if($UniqueId!="" && ($Action=="UpdateVehicle" || $Action=="DeleteVehicle"))
{
	$query1="select * from vehicle where VehicleId='$UniqueId' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0 && $Action=="UpdateVehicle")
	{
		$row1=mysqli_fetch_array($check1);
		$VehicleName=$row1['VehicleName'];
		$VehicleNumber=$row1['VehicleNumber'];
		$VehicleButtonContent="Update";
		$VehicleButtonContentSet=1;
		$VehicleAddButton="Update <a href=Transport><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateVehicleId=$UniqueId;
	}
}
elseif($UniqueId!="" && ($Action=="UpdateFuel" || $Action=="DeleteFuel"))
{
	$query2="select VehicleId,ReceiptNo,Quantity,Rate,DOF,Remarks from vehiclefuel where FuelId='$UniqueId' and FuelStatus='Active'";
	$check2=mysqli_query($CONNECTION,$query2);
	$count2=mysqli_num_rows($check2);
	if($count2>0 && $Action=="UpdateFuel")
	{
		$row2=mysqli_fetch_array($check2);
		$FuelVehicleId=$row2['VehicleId'];
		$ReceiptNo=$row2['ReceiptNo'];
		$Quantity=round($row2['Quantity'],2);
		$Rate=round($row2['Rate'],2);
		$DOF=date("d-m-Y h:ia",$row2['DOF']);
		$FuelRemarks=br2nl($row2['Remarks']);
		$FuelButtonContent="Update";
		$FuelButtonContentSet=1;
		$FuelAddButton="Update <a href=Transport><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateFuelId=$UniqueId;
	}
}
elseif($UniqueId!="" && ($Action=="UpdateReading" || $Action=="DeleteReading"))
{
	$query3="select VehicleId,Reading,DOR,Remarks from vehiclereading where VehicleReadingId='$UniqueId' and VehicleReadingStatus='Active'";
	$check3=mysqli_query($CONNECTION,$query3);
	$count3=mysqli_num_rows($check3);
	if($count3>0 && $Action=="UpdateReading")
	{
		$row3=mysqli_fetch_array($check3);
		$ReadingVehicleId=$row3['VehicleId'];
		$Reading=round($row3['Reading'],2);
		$DOR=date("d-m-Y h:ia",$row3['DOR']);
		$ReadingRemarks=br2nl($row3['Remarks']);
		$ReadingButtonContent="Update";
		$ReadingButtonContentSet=1;
		$ReadingAddButton="Update <a href=Transport><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateVehicleReadingId=$UniqueId;
	}
}

if($VehicleButtonContentSet!=1)
{
	$VehicleButtonContent="Add";
	$VehicleAddButton="Add Vehicle";
}
if($FuelButtonContentSet!=1)
{
	$FuelButtonContent="Add";
	$FuelAddButton="Add Fuel";
}
if($ReadingButtonContentSet!=1)
{
	$ReadingButtonContent="Add";
	$ReadingAddButton="Add Reading";
}

	$FuelReportVehicleId=isset($_POST['FuelReportVehicleId']) ? $_POST['FuelReportVehicleId'] : '';
	$ReadingReportVehicleId=isset($_POST['ReadingReportVehicleId']) ? $_POST['ReadingReportVehicleId'] : '';
	$query="select VehicleName,VehicleNumber,VehicleId from vehicle where VehicleStatus='Active' order by VehicleName";
	$DATA=array();
	$QA=array();
	$result=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($result);
	$FuelSelected=$FuelReportSelected=$ReadingReportSelected="";
	$ListAllVehicleIdFuel=$ListAllVehicleIdReading=$ListAllVehicleIdReadingReport=$ListAllVehicleIdFuelReport="";
	while($row=mysqli_fetch_array($result))
	{
		$ListVehicleName=$row['VehicleName'];	
		$ListVehicleNumber=$row['VehicleNumber'];	
		$ListVehicleId=$row['VehicleId'];	
		if($ListVehicleId==$FuelVehicleId)
		$FuelSelected="Selected";
		else
		$FuelSelected="";	
		if($ListVehicleId==$FuelReportVehicleId)
		$FuelReportSelected="Selected";
		else
		$FuelReportSelected="";	
		if($ListVehicleId==$ReadingReportVehicleId)
		$ReadingReportSelected="Selected";
		else
		$ReadingReportSelected="";	
		if($ListVehicleId==$ReadingVehicleId)
		$ReadingSelected="Selected";
		else
		$ReadingSelected="";
		$ListAllVehicleIdFuel.="<option value=\"$ListVehicleId\" $FuelSelected>$ListVehicleName $ListVehicleNumber</option>";
		$ListAllVehicleIdReading.="<option value=\"$ListVehicleId\" $ReadingSelected>$ListVehicleName $ListVehicleNumber</option>";
		$ListAllVehicleIdReadingReport.="<option value=\"$ListVehicleId\" $ReadingReportSelected>$ListVehicleName $ListVehicleNumber</option>";
		$ListAllVehicleIdFuelReport.="<option value=\"$ListVehicleId\" $FuelReportSelected>$ListVehicleName $ListVehicleNumber</option>";
		$Edit="<a href=Transport/UpdateVehicle/$ListVehicleId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		$QA[]=array($ListVehicleName,$ListVehicleNumber,$Edit);
	}
	$DATA['aaData']=$QA;
	$fp = fopen('plugins/Data/data1.txt', 'w');
	fwrite($fp, json_encode($DATA));
	fclose($fp);	
?>	

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Transport Reading & Fuel"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $VehicleAddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageVehicle" id="ManageVehicle" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="VehicleName">Vehicle Name</label>
												<input tabindex="1" class="span8" id="VehicleName" type="text" name="VehicleName" value="<?php echo $VehicleName; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="VehicleNumber">Vehicle Number</label>
												<input tabindex="2" class="span8" id="VehicleNumber" type="text" name="VehicleNumber" value="<?php echo $VehicleNumber; ?>" />
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageVehicle" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="VehicleId" value="<?php echo $UpdateVehicleId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($VehicleButtonContent,3); ?>
								</form>
                            </div>
						</div>
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Listing All Vehicle</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="VehicleTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Vehicle Name</th>
											<th>Vehicle Number</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
                    </div>		

					<div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $FuelAddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageFuel" id="ManageFuel" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="FuelVehicleId">Vehicle</label>
												<div class="controls sel span8">   
													<select tabindex="10" name="FuelVehicleId" id="FuelVehicleId" class="nostyle" style="width:100%;" >
													<option></option>
													<?php echo $ListAllVehicleIdFuel; ?>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Quantity">Quantity</label>
												<input tabindex="11" class="span8" id="Quantity" type="text" name="Quantity" value="<?php echo $Quantity; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Rate">Rate Per Liter</label>
												<input tabindex="12" class="span8" id="Rate" type="text" name="Rate" value="<?php echo $Rate; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="DOF">Date</label>
												<input readonly tabindex="13" class="span8" id="DOF" type="text" name="DOF" value="<?php echo $DOF; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ReceiptNo">Receipt No</label>
												<input tabindex="14" class="span8" id="ReceiptNo" type="text" name="ReceiptNo" value="<?php echo $ReceiptNo; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Remarks">Remarks</label>
												<div class="span8 controls-textarea">   
												<textarea tabindex="15" class="span12 tip" title="Optional : Remarks" name="Remarks" id="Remarks"><?php echo $FuelRemarks; ?></textarea>
												</div>
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageFuel" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count2>0) { ?>
										<input type="hidden" name="FuelId" value="<?php echo $UpdateFuelId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($FuelButtonContent,16); ?>
								</form>
                            </div>
						</div>
					</div>			

					<div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $ReadingAddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageVehicleReading" id="ManageVehicleReading" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ReadingVehicleId">Vehicle</label>
												<div class="controls sel span8">   
													<select tabindex="20" name="ReadingVehicleId" id="ReadingVehicleId" class="nostyle" style="width:100%;" >
													<option></option>
													<?php echo $ListAllVehicleIdReading; ?>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Reading">Reading</label>
												<input tabindex="21" class="span8" id="Reading" type="text" name="Reading" value="<?php echo $Reading; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="DOR">Date</label>
												<input readonly tabindex="22" class="span8" id="DOR" type="text" name="DOR" value="<?php echo $DOR; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ReadingRemarks">Remarks</label>
												<div class="span8 controls-textarea">   
												<textarea tabindex="23" class="span12 tip" title="Optional : Remarks" name="ReadingRemarks" id="ReadingRemarks"><?php echo $ReadingRemarks; ?></textarea>
												</div>
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageVehicleReading" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count3>0) { ?>
										<input type="hidden" name="VehicleReadingId" value="<?php echo $UpdateVehicleReadingId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ReadingButtonContent,24); ?>
								</form>
                            </div>
						</div>
					</div>				
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Fuel Report</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad clearfix">
								<?php
									$FromDateFuel=isset($_POST['FromDateFuel']) ? $_POST['FromDateFuel'] : '';
									$ToDateFuel=isset($_POST['ToDateFuel']) ? $_POST['ToDateFuel'] : '';
									$FuelVehicleQuery="";
									$DateddMMyyyy=date("d-m-Y",strtotime($Date));
									if($FromDateFuel=="")
									$FromDateFuel=$DateddMMyyyy;
									if($ToDateFuel=="")
									$ToDateFuel=$DateddMMyyyy;
									$FromDateFuelStart="$FromDateFuel 00:00";
									$ToDateFuelEnd="$ToDateFuel 23:59";
									$FDTSFuel=strtotime($FromDateFuelStart);
									$TDTSFuel=strtotime($ToDateFuelEnd);
									if($FuelReportVehicleId!="")
									$FuelVehicleQuery=" and vehiclefuel.VehicleId='$FuelReportVehicleId' ";
									$query11="select FuelId,ReceiptNo,Quantity,Rate,DOF,VehicleName,VehicleNumber from vehiclefuel,vehicle where
										vehiclefuel.VehicleId=vehicle.VehicleId and FuelStatus='Active' and 
										DOF>='$FDTSFuel' and DOF<='$TDTSFuel' $FuelVehicleQuery ";
									$check11=mysqli_query($CONNECTION,$query11);
									$DATA11=array();
									$QA11=array();
									while($row11=mysqli_fetch_array($check11))
									{
										$ListFuelReportVehicleFuelId=$row11['FuelId'];
										$ListFuelReportReceiptNo=$row11['ReceiptNo'];
										$ListFuelReportQuantity=$row11['Quantity'];
										$ListFuelReportRate=$row11['Rate'];
										$ListFuelReportDOF=date("d M Y",$row11['DOF']);
										$ListFuelReportVehicleName=$row11['VehicleName'];
										$ListFuelReportVehicleNumber=$row11['VehicleNumber'];
										$FuelReportEdit="<a href=Transport/UpdateFuel/$ListFuelReportVehicleFuelId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
										$FuelReportDelete="<a href=DeletePopUp/DeleteFuel/$ListFuelReportVehicleFuelId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
										$QA11[]=array($ListFuelReportVehicleName,$ListFuelReportVehicleNumber,$ListFuelReportReceiptNo,$ListFuelReportQuantity,$ListFuelReportRate,$ListFuelReportDOF,$FuelReportEdit,$FuelReportDelete);
									}
									$DATA11['aaData']=$QA11;
									$fp = fopen('plugins/Data/data3.txt', 'w');
									fwrite($fp, json_encode($DATA11));
									fclose($fp);	
								?>
								<form class="form-horizontal" action="" name="FuelReport" id="FuelReport" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="FromDateFuel">From Date</label>
												<input tabindex="201" class="span8 tip" title="Mandatory : (dd-mm-yyyy)" id="FromDateFuel" type="text" name="FromDateFuel" value="<?php echo $FromDateFuel; ?>" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ToDateFuel">To Date</label>
												<input tabindex="202" class="span8 tip" title="Mandatory : (dd-mm-yyyy)" id="ToDateFuel" type="text" name="ToDateFuel" value="<?php echo $ToDateFuel; ?>" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="FuelReportVehicleId">Vehicle</label>
												<div class="span8 controls sel">
												<select tabindex="203" class="nostyle" name="FuelReportVehicleId" id="FuelReportVehicleId" style="width:100%;">
												<option></option>
												<?php echo $ListAllVehicleIdFuelReport; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
								   <?php $ButtonContent="Get Report"; ActionButton($ButtonContent,4); ?>
								</form>
								<table id="FuelReportTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Name</th>
											<th>Number</th>
											<th>Receipt No</th>
											<th>Quantity</th>
											<th>Rate</th>
											<th>Date</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
											<th><span class="icomoon-icon-cancel tip" title="Delete"></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
                    <div class="span6">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Reading Report Report</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad clearfix">
								<?php
									$FromDateReading=isset($_POST['FromDateReading']) ? $_POST['FromDateReading'] : '';
									$ToDateReading=isset($_POST['ToDateReading'])  ? $_POST['ToDateReading'] : '';
									$ReadingVehicleQuery="";
									$DateddMMyyyy=date("d-m-Y",strtotime($Date));
									if($FromDateReading=="")
									$FromDateReading=$DateddMMyyyy;
									if($ToDateReading=="")
									$ToDateReading=$DateddMMyyyy;
									$FromDateReadingStart="$FromDateReading 00:00";
									$ToDateReadingEnd="$ToDateReading 23:59";
									$FDTSReading=strtotime($FromDateReadingStart);
									$TDTSReading=strtotime($ToDateReadingEnd);
									if($ReadingReportVehicleId!="")
									$ReadingVehicleQuery=" and vehiclereading.VehicleId='$ReadingReportVehicleId' ";
									$query11="select VehicleReadingId,Reading,DOR,VehicleName,VehicleNumber from vehiclereading,vehicle where
										vehiclereading.VehicleId=vehicle.VehicleId and VehicleReadingStatus='Active' and 
										DOR>='$FDTSReading' and DOR<='$TDTSReading' $ReadingVehicleQuery ";
									$check11=mysqli_query($CONNECTION,$query11);
									$DATA12=array();
									$QA12=array();
									while($row11=mysqli_fetch_array($check11))
									{
										$ListReadingReportVehicleReadingId=$row11['VehicleReadingId'];
										$ListReadingReportReading=$row11['Reading'];
										$ListReadingReportDOR=date("d M Y",$row11['DOR']);
										$ListReadingReportVehicleName=$row11['VehicleName'];
										$ListReadingReportVehicleNumber=$row11['VehicleNumber'];
										$ReadingReportEdit="<a href=Transport/UpdateReading/$ListReadingReportVehicleReadingId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
										$ReadingReportDelete="<a href=DeletePopUp/DeleteReading/$ListReadingReportVehicleReadingId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
										$QA12[]=array($ListReadingReportVehicleName,$ListReadingReportVehicleNumber,$ListReadingReportReading,$ListReadingReportDOR,$ReadingReportEdit,$ReadingReportDelete);
									}
									$DATA12['aaData']=$QA12;
									$fp = fopen('plugins/Data/data4.txt', 'w');
									fwrite($fp, json_encode($DATA12));
									fclose($fp);	
								?>
								<form class="form-horizontal" action="" name="ReadingReport" id="ReadingReport" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="FromDateReading">From Date</label>
												<input tabindex="301" class="span8 tip" title="Mandatory : (dd-mm-yyyy)" id="FromDateReading" type="text" name="FromDateReading" value="<?php echo $FromDateReading; ?>" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ToDateReading">To Date</label>
												<input tabindex="302" class="span8 tip" title="Mandatory : (dd-mm-yyyy)" id="ToDateReading" type="text" name="ToDateReading" value="<?php echo $ToDateReading; ?>" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ReadingReportVehicleId">Vehicle</label>
												<div class="span8 controls sel">
												<select tabindex="303" class="nostyle" name="ReadingReportVehicleId" id="ReadingReportVehicleId" style="width:100%;">
												<option></option>
												<?php echo $ListAllVehicleIdReadingReport; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
								   <?php $ButtonContent="Get Report"; ActionButton($ButtonContent,4); ?>
								</form>
								<table id="ReadingReportTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Name</th>
											<th>Number</th>
											<th>Reading</th>
											<th>Date</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
											<th><span class="icomoon-icon-cancel tip" title="Delete"></span></th>
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

$("#FuelVehicleId").select2();
$('#FuelVehicleId').select2({placeholder: "Select"});
$("#ReadingVehicleId").select2();
$('#ReadingVehicleId').select2({placeholder: "Select"});
$('#VehicleTable').dataTable({
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
$('#FuelReportTable').dataTable({
	"sPaginationType": "two_button",
	"bJQueryUI": false,
	"bAutoWidth": false,
	"bLengthChange": false,  
	"bProcessing": true,
	"bDeferRender": true,
	"sAjaxSource": "plugins/Data/data3.txt",
	"fnInitComplete": function(oSettings, json) {
	  $('.dataTables_filter>label>input').attr('id', 'search');
		$('#FuelReportTable').on('click', 'a[data-toggle=modal]', function(e) {
		lv_target = $(this).attr('data-target');
		lv_url = $(this).attr('href');
		$(lv_target).load(lv_url);
		});	
	}
});
$('#ReadingReportTable').dataTable({
	"sPaginationType": "two_button",
	"bJQueryUI": false,
	"bAutoWidth": false,
	"bLengthChange": false,  
	"bProcessing": true,
	"bDeferRender": true,
	"sAjaxSource": "plugins/Data/data4.txt",
	"fnInitComplete": function(oSettings, json) {
	  $('.dataTables_filter>label>input').attr('id', 'search');
		$('#ReadingReportTable').on('click', 'a[data-toggle=modal]', function(e) {
		lv_target = $(this).attr('data-target');
		lv_url = $(this).attr('href');
		$(lv_target).load(lv_url);
		});	
	}
});
	if($('#DOF').length) {
	$('#DOF').datetimepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
	if($('#DOR').length) {
	$('#DOR').datetimepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageVehicle").validate({
		rules: {
			VehicleName: {
				required: true,
			},
			VehicleNumber: {
				required: true,
			},
		},
		messages: {
			VehicleName: {
				required: "Please enter this!!",
			},
			VehicleNumber: {
				required: "Please enter this!!",
			},
		}   
	});
	$("#ManageFuel").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			FuelVehicleId: {
				required: true,
			},
			Quantity: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=Quantity"
			},
			Rate: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=Rate"
			},
			//ReceiptNo: {
			//	required: true,
			//},
			DOF: {
				required: true,
			}
		},
		messages: {
			FuelVehicleId: {
				required: "Please select this!!",
			},
			Quantity: {
				required: "Please enter this!!",
				remote: jQuery.format("Only numeric allowed!!")
			},
			Rate: {
				required: "Please enter this!!",
				remote: jQuery.format("Only numeric allowed!!")
			},
			//ReceiptNo: {
			//	required: "Please enter this!!",
			//},
			DOF: {
				required: "Please enter this!!",
			}
		}   
	});
	$("#ManageVehicleReading").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			ReadingVehicleId: {
				required: true,
			},
			Reading: {
				required: true,
				remote: "RemoteValidation?Action=IsNumeric&Id=Reading"
			},
			DOR: {
				required: true,
			}
		},
		messages: {
			ReadingVehicleId: {
				required: "Please select this!!",
			},
			Reading: {
				required: "Please enter this!!",
				remote: jQuery.format("Only numeric allowed!!")
			},
			DOR: {
				required: "Please enter this!!",
			}
		}   
	});
		$("#FuelReportVehicleId").select2();
		$('#FuelReportVehicleId').select2({placeholder: "Select"});
		if($('#FromDateFuel').length) {
		$('#FromDateFuel').datepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}	
		if($('#ToDateFuel').length) {
		$('#ToDateFuel').datepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}	
		$("input, textarea, select").not('.nostyle').uniform();
		$("#FuelReport").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				FromDateFuel: {
					required: true,
				},
				ToDateFuel: {
					required: true,
				}
			} 
		});
		$("#ReadingReportVehicleId").select2();
		$('#ReadingReportVehicleId').select2({placeholder: "Select"});
		if($('#FromDateReading').length) {
		$('#FromDateReading').datepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}	
		if($('#ToDateReading').length) {
		$('#ToDateReading').datepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}	
		$("input, textarea, select").not('.nostyle').uniform();
		$("#ReadingReport").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				FromDateReading: {
					required: true,
				},
				ToDateReading: {
					required: true,
				}
			} 
		});
});
</script>
		
<?php
include("Template/Footer.php");
?>