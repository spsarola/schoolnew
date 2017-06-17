<?php
$PageName="TransportRoute";
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

        <div id="content" class="clearfix">
            <div class="contentwrapper">
				
<?php
$Action=isset($_GET['Action']) ? $_GET['Action']  : '';
$UniqueId=isset($_GET['UniqueId']) ? $_GET['UniqueId']  : '';
$ViewRouteOption=$StudentList=$VehicleRouteButtonContentSet=$VehicleRouteDetailButtonContentSet=$count6=$UpdateVehicleRouteId =$VehicleRouteVehicleId=$SNo1=$count1=$VehicleRouteName=$SNo2=$SelectedRouteTo =$VehicleRouteRemarks =$Route ="";
$TotalStudentUsingBus=$TotalStudentNotUsingBus=0;

$query3="Select MasterEntryValue,MasterEntryId from masterentry where
	MasterEntryName='RouteStoppage' and MasterEntryStatus='Active' ";
$check3=mysqli_query($CONNECTION,$query3);
while($row3=mysqli_fetch_array($check3))
{
	$RouteStoppageIdArray[]=$row3['MasterEntryId'];
	$RouteStoppageNameArray[]=$row3['MasterEntryValue'];
}
	
if($UniqueId!="" && ($Action=="UpdateRoute" || $Action=="DeleteRoute" || $Action=="ViewRoute"))
{
	$query1="select * from vehicleroute where VehicleRouteId='$UniqueId' and Session='$CURRENTSESSION' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0 && $Action=="UpdateRoute")
	{
		$row1=mysqli_fetch_array($check1);
		$VehicleRouteName=$row1['VehicleRouteName'];
		$VehicleRouteVehicleId=$row1['VehicleId'];
		$SelectedRouteTo=$row1['RouteTo'];
		$VehicleRouteRemarks=$row1['VehicleRouteRemarks'];
		$Route=$row1['Route'];
		$Route=explode(",",$Route);
		$VehicleRouteButtonContent="Update";
		$VehicleRouteButtonContentSet=1;
		$VehicleRouteAddButton="Update <a href=TransportRoute><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateVehicleRouteId=$UniqueId;
	}
	elseif($count1>0 && $Action=="ViewRoute")
	{
		$row1=mysqli_fetch_array($check1);
		$ViewVehicleRouteName=$row1['VehicleRouteName'];
		$SelectedRouteTo=$row1['RouteTo'];
		$ViewRoute=$row1['Route'];
		$ViewRoute=explode(",",$ViewRoute);
	}
}

		
	$query4="select admission.AdmissionId,StudentName,FatherName,ClassName,SectionName,Mobile,Distance from registration,class,section,admission,studentfee where
			registration.RegistrationId=admission.RegistrationId and
			admission.AdmissionId=studentfee.AdmissionId and
			studentfee.Session='$CURRENTSESSION' and
			studentfee.SectionId=section.SectionId and
			class.ClassId=section.ClassId 
			order by StudentName,FatherName";
	$check4=mysqli_query($CONNECTION,$query4);
	while($row4=mysqli_fetch_array($check4))
	{
		$ListAdmissionId=$row4['AdmissionId'];
		$ListStudentName=$row4['StudentName'];
		$ListFatherName=$row4['FatherName'];
		$ListClassName=$row4['ClassName'];
		$ListDistance=$row4['Distance'];
		$ListMobile=$row4['Mobile'];
		if($ListDistance!="")
		{
			$RegistrationIdArray[]=$ListAdmissionId;
			$RegistrationNameArray[]="$ListStudentName $ListFatherName $ListClassName $ListMobile";
		}
		if($ListDistance!="")
		$TotalStudentUsingBus++;
		else
		$TotalStudentNotUsingBus++;
	}
	
	$query40="select Students,VehicleRouteId,RouteStoppageId from vehicleroutedetail where VehicleRouteDetailStatus='Active' ";
	$check40=mysqli_query($CONNECTION,$query40);
	while($row40=mysqli_fetch_array($check40))
	{
		$StudentInRouteArray[]=count(explode(",",$row40['Students']));
		$VehicleRouteIdInRouteArray[]=$row40['VehicleRouteId'];
		$RouteStoppageIdInRouteArray[]=$row40['RouteStoppageId'];
		$BothArray[]=$row40['VehicleRouteId']."-".$row40['RouteStoppageId'];
	}
	

if($VehicleRouteButtonContentSet!=1)
{
	$VehicleRouteButtonContent="Add";
	$VehicleRouteAddButton="Add Route";
}

	$query="select VehicleName,VehicleNumber,VehicleId from vehicle where VehicleStatus='Active' order by VehicleName";
	$result=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($result);
	$ListAllVehicleIdVehicleRoute="";
	while($row=mysqli_fetch_array($result))
	{
		$ListVehicleName=$row['VehicleName'];	
		$ListVehicleNumber=$row['VehicleNumber'];	
		$ListVehicleId=$row['VehicleId'];	
		if($ListVehicleId==$VehicleRouteVehicleId)
		$VehicleRouteSelected="Selected";
		else
		$VehicleRouteSelected="";	
		$ListAllVehicleIdVehicleRoute.="<option value=\"$ListVehicleId\" $VehicleRouteSelected>$ListVehicleName $ListVehicleNumber</option>";
	}
	$query2="select VehicleRouteId,VehicleRouteName,VehicleName,VehicleNumber,Route,MasterEntryValue from vehicle,vehicleroute,masterentry where
		VehicleRouteStatus='Active' and
		vehicleroute.VehicleId=vehicle.VehicleId and
		vehicleroute.RouteTo=masterentry.MasterEntryId and
		vehicleroute.Session='$CURRENTSESSION' 
		order by VehicleRouteName";
	$check2=mysqli_query($CONNECTION,$query2);
	$count2=mysqli_num_rows($check2);
	$DATA1=array();
	$QA1=array();
	$PrintTable1Data="";
	while($row2=mysqli_fetch_array($check2))
	{
		$TotalStudentInOneRoute=0;
		$TableListVehicleRouteName=$row2['VehicleRouteName'];
		$TableListVehicleRouteId=$row2['VehicleRouteId'];
		$TableListVehicleName=$row2['VehicleName'];
		$TableListVehicleNumber=$row2['VehicleNumber'];
		$TableListRouteTo=$row2['MasterEntryValue'];
		$TableListRoute=$row2['Route'];
		$TableListRoute=explode(",",$TableListRoute);
		$RouteStoppageName="";
		$ii=0;
		
		foreach($TableListRoute as $TableListRouteValue)
		{
			$pp=0;
			if($BothArray=="")
			$StudentsInEachStoppage=0;
			else
			foreach($BothArray as $BothArrayValue)
			{
				$BothArrayValue=explode("-",$BothArrayValue);
				$VId=$BothArrayValue[0];
				$RId=$BothArrayValue[1];
				if($VId==$TableListVehicleRouteId && $RId==$TableListRouteValue)
				{
					$StudentsInEachStoppage=$StudentInRouteArray[$pp];
					break;
				}
				else
				$StudentsInEachStoppage=0;
				$pp++;
			}

			$ii++;
			$SearchForRoute=array_search($TableListRouteValue,$RouteStoppageIdArray);
			$RouteStoppageName.="$ii) ". $RouteStoppageNameArray[$SearchForRoute] . " <B>($StudentsInEachStoppage)</b> <br>";
			$TotalStudentInOneRoute+=$StudentsInEachStoppage;
		}
		
		$TableListVehicleName.=" <Br>$TableListVehicleNumber";
		$TableListVehicleName.="<br><B>Total Students $TotalStudentInOneRoute Student(s)</b>";
		$Edit="<a href=TransportRoute/UpdateRoute/$TableListVehicleRouteId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		$SNo1++;
		$PrintTable1Data.="<tr><Td>$SNo1</td><Td>$TableListVehicleRouteName</td><Td>$TableListVehicleName</td><Td>$RouteStoppageName</td></tr>";
		$TableListVehicleRouteName="<a href=TransportRoute/ViewRoute/$TableListVehicleRouteId>$TableListVehicleRouteName</a>";
		$QA1[]=array($SNo1,$TableListRouteTo,$TableListVehicleRouteName,$TableListVehicleName,$RouteStoppageName,$Edit);
	}
	$DATA1['aaData']=$QA1;
	$fp = fopen('plugins/Data/data1.txt', 'w');
	fwrite($fp, json_encode($DATA1));
	fclose($fp);
	
if($count1>0 && $Action=="ViewRoute")
{

	$query101="select Students from vehicleroutedetail,vehicleroute where
		vehicleroutedetail.VehicleRouteId=vehicleroute.VehicleRouteId and
		vehicleroutedetail.VehicleRouteId!='$UniqueId' and
		vehicleroute.Session='$CURRENTSESSION' and
		RouteTo='$SelectedRouteTo' and Students!='' ";
	$check101=mysqli_query($CONNECTION,$query101);
	while($row101=mysqli_fetch_array($check101))
	{
		$OtherStudents=$row101['Students'];
		$OtherStudents=explode(",",$OtherStudents);
		foreach($OtherStudents as $OtherStudentsValue)
		$StudentsInTransportRoute[]=$OtherStudentsValue;
	}
	
	$SAction=isset($_GET['SAction']) ? $_GET['SAction'] : '';
	$SUniqueId=isset($_GET['SUniqueId']) ? $_GET['SUniqueId'] : '';
	if($SUniqueId!="" && $SAction=="UpdateRouteDetail")
	{
		$query6="select VehicleRouteDetailId,VehicleRouteId,RouteStoppageId,Students from vehicleroutedetail where VehicleRouteDetailId='$SUniqueId' and VehicleRouteId='$UniqueId' ";
		
		$check6=mysqli_query($CONNECTION,$query6);
		$count6=mysqli_num_rows($check6);
	}
	
	$query5="Select VehicleRouteDetailId,RouteStoppageId,MasterEntryValue,Students,DOE from vehicleroutedetail,masterentry where
		vehicleroutedetail.RouteStoppageId=masterentry.MasterEntryId and
		VehicleRouteId='$UniqueId' and
		VehicleRouteDetailStatus='Active' ";
	$check5=mysqli_query($CONNECTION,$query5);
	$count5=mysqli_num_rows($check5);
	$DATA2=array();
	$QA2=array();
	$PrintTable2Data="";
	while($row5=mysqli_fetch_array($check5))
	{
		$TableListVehicleRouteDetailId=$row5['VehicleRouteDetailId'];
		$TableListRouteStoppageId=$row5['RouteStoppageId'];
		$SavedRouteStoppageIdArray[]=$TableListRouteStoppageId;
		$TableListStoppageName=$row5['MasterEntryValue'];
		$TableListStudent=$row5['Students'];
		$TableListDOE=date("d M Y, h:ia",$row5['DOE']);
		$TableListStudentArray=explode(",",$TableListStudent);
		$StudentDetail="";
		foreach($TableListStudentArray as $TableListStudent)
		{
			foreach($RegistrationIdArray as $RegistrationIdValues)
			{
				if($RegistrationIdValues==$TableListStudent)
				{
					$StudentsDetailIndex=array_search($RegistrationIdValues,$RegistrationIdArray);
					$StudentDetail.="<div style=\"border-bottom:1px dotted black;\">$RegistrationNameArray[$StudentsDetailIndex]</div>";
					$StudentsInTransportRoute[]=$RegistrationIdValues;
				}
			}
		}
		$EditDetail="<a href=TransportRoute/ViewRoute/$UniqueId/UpdateRouteDetail/$TableListVehicleRouteDetailId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		
		$SNo2++;
		$PrintTable2Data.="<tr><Td>$SNo2</td><Td>$TableListStoppageName</td><Td>$StudentDetail</td></tr>";
		$QA2[]=array($SNo2,$TableListStoppageName,$StudentDetail,$TableListDOE,$EditDetail);
	}
	$DATA2['aaData']=$QA2;
	$fp = fopen('plugins/Data/data2.txt', 'w');
	fwrite($fp, json_encode($DATA2));
	fclose($fp);
	
	$CountStudentsInTransportRoute=count($StudentsInTransportRoute);
	if($CountStudentsInTransportRoute>0)
	$StudentListArray = array_diff($RegistrationIdArray, $StudentsInTransportRoute);
	else
	$StudentListArray=$RegistrationIdArray;
	
	if($SUniqueId=="" || $SAction=="")
	{
		if($RegistrationIdArray!="")
		foreach($RegistrationIdArray as $RegistrationIdList)
		{
			$SearchIndex=array_search($RegistrationIdList,$StudentListArray);
			if($SearchIndex===FALSE)
			{}
			else
			{
				$StudentDetailList=$RegistrationNameArray[$SearchIndex];
				$StudentList.="<option value=\"$RegistrationIdList\">$StudentDetailList</option>";					
			}
		}
		
		foreach($ViewRoute as $ViewRouteArray)
		{
			if($SavedRouteStoppageIdArray!="")
			$SearchForRoute=array_search($ViewRouteArray,$SavedRouteStoppageIdArray);
			else
			$SearchForRoute=FALSE;
			if($SearchForRoute===FALSE)
			{
				foreach($RouteStoppageIdArray as $RouteStoppageId)
				{
					if($RouteStoppageId==$ViewRouteArray)
					{
						$ArrayIndex = array_search($RouteStoppageId, $RouteStoppageIdArray);
						$RouteStoppageName=$RouteStoppageNameArray[$ArrayIndex];
						$ViewRouteOption.="<option value=\"$RouteStoppageId\">$RouteStoppageName</option>";
					}
				}
			}
		}
	}
	elseif($SUniqueId!="" && $SAction=="UpdateRouteDetail" && $count6==1)
	{
		$UpdateStudentsArray=array_diff($RegistrationIdArray,$StudentsInTransportRoute);
		$row6=mysqli_fetch_array($check6);
		$UpdateVehicleRouteDetailId=$row6['VehicleRouteDetailId'];
		$UpdateVehicleRouteId=$row6['VehicleRouteId'];
		$UpdateRouteStoppageId=$row6['RouteStoppageId'];
		
		$UpdateStoppageArray=array_diff($ViewRoute,$SavedRouteStoppageIdArray);
		foreach($UpdateStoppageArray as $UpdateStoppageArrayValues)
		{
			$UpdateSearchIndex=array_search($UpdateStoppageArrayValues, $RouteStoppageIdArray);
			$RouteStoppageName=$RouteStoppageNameArray[$UpdateSearchIndex];
			$ViewRouteOption.="<option value=\"$UpdateStoppageArrayValues\">$RouteStoppageName</option>";
		}
			$UpdateSearchIndex=array_search($UpdateRouteStoppageId, $RouteStoppageIdArray);
			$RouteStoppageName=$RouteStoppageNameArray[$UpdateSearchIndex];
			$ViewRouteOption.="<option value=\"$UpdateRouteStoppageId\" selected>$RouteStoppageName</option>";
		
		$UpdateStudents=$row6['Students'];
		$UpdateStudents=explode(",",$UpdateStudents);
		$UpdateStudentsOther=array_diff($RegistrationIdArray,$StudentsInTransportRoute);
		foreach($UpdateStudentsOther as $UpdateStudentsOtherValues)
		{
			$UpdateStudentOtherSearchIndex=array_search($UpdateStudentsOtherValues, $RegistrationIdArray);
			$UpdateStudentOtherName=$RegistrationNameArray[$UpdateStudentOtherSearchIndex];
			$StudentList.="<option value=\"$UpdateStudentsOtherValues\">$UpdateStudentOtherName</option>";
		}
		foreach($UpdateStudents as $UpdateStudentsValues)
		{
			$UpdateStudentSearchIndex=array_search($UpdateStudentsValues, $RegistrationIdArray);
			$UpdateStudentName=$RegistrationNameArray[$UpdateStudentSearchIndex];
			$StudentList.="<option value=\"$UpdateStudentsValues\" selected>$UpdateStudentName</option>";
		}
		
		$VehicleRouteDetailButtonContent="Update"; 
		$VehicleRouteDetailButtonContentSet=1;
		$VehicleRouteDetailAddButton="Update <a href=/TransportRoute/ViewRoute/$UniqueId><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
	}
	
	if($VehicleRouteDetailButtonContentSet!=1)
	{
		$VehicleRouteDetailButtonContent="Add";
		$VehicleRouteDetailAddButton="Add";
	}	
}
?>	
                <?php $BreadCumb="Transport Route (Total No of Students Using Bus Facility $TotalStudentUsingBus Student(s) | <font color=red>Not Using $TotalStudentNotUsingBus Student(s)</font>"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
                <div class="row-fluid">
                    <div class="span6">
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Listing All Vehicle Route </span>
									<?php if($count2>0) { ?>
									<div class="PrintClass">
										<form method=post action=/Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintVehicleRoute" readonly>
										<input type="hidden" name="HeadingName" value="PrintVehicleRouteHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Vehicle Route List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="VehicleRouteTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>SNo</th>
											<th>To</th>
											<th>Route Name</th>
											<th>Vehicle</th>
											<th>Stoppage</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								<?php
									$PrintTable1="<table class=\"responsive dynamicTable display table table-bordered\"><tr><th>SNo</th><th>Route Name</th><th>Vehicle Name</th><th>Stoppage</th></tr>
											$PrintTable1Data </table>";
									$_SESSION['PrintVehicleRoute']=$PrintTable1;
									$_SESSION['PrintVehicleRouteHeading']="All Route list for Session $CURRENTSESSION";
								?>
							<div class="clearfix"></div>
                            <div class="title">
                                <h4>
                                    <span><?php echo $VehicleRouteAddButton; ?> </span>
                                </h4>
                            </div>
								<form class="form-horizontal" action="Action" name="ManageVehicleRoute" id="ManageVehicleRoute" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="VehicleRouteName">Route Name</label>
												<input tabindex="1" class="span8" id="VehicleRouteName" type="text" name="VehicleRouteName" value="<?php echo $VehicleRouteName; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="RouteTo">To</label>
												<div class="controls sel span8">  
												<?php GetCategoryValue('RouteTo','RouteTo',$SelectedRouteTo,'','','','',2,''); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="VehicleRouteVehicleId">Vehicle</label>
												<div class="controls sel span8">   
													<select tabindex="3" name="VehicleRouteVehicleId" id="VehicleRouteVehicleId" class="nostyle" style="width:100%;" >
													<option></option>
													<?php echo $ListAllVehicleIdVehicleRoute; ?>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Route">Rotue
												<span class="help-block">Order in which vehicle follows the route</span></label>
												<div class="controls sel span8">  
												<?php GetCategoryValue('RouteStoppage','Route',$Route,'','','','',4,1); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Remarks">Remarks</label>
												<div class="span8 controls-textarea">   
												<textarea tabindex="5" class="span12 tip" title="Optional : Remarks" name="Remarks" id="Remarks"><?php echo $VehicleRouteRemarks; ?></textarea>
												</div>
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageVehicleRoute" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="VehicleRouteId" value="<?php echo $UpdateVehicleRouteId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($VehicleRouteButtonContent,6); ?>
								</form>
                            </div>
						</div>
                    </div>	
					
					<div class="span6">
					<?php if($count1>0 && $Action=="ViewRoute") { ?>
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo "$VehicleRouteDetailAddButton Student Detail to $ViewVehicleRouteName"; ?> </span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageVehicleRouteDetail" id="ManageVehicleRouteDetail" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="RouteStoppageId">Stoppage</label>
												<div class="controls sel span8">   
													<select tabindex="10" name="RouteStoppageId" id="RouteStoppageId" class="nostyle" style="width:100%;" >
													<option></option>
													<?php echo $ViewRouteOption; ?>
													</select>
												</div>
											</div>
										</div>
									</div>	
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="AdmissionId">Students</label>
												<div class="controls sel span8">   
													<select tabindex="10" name="AdmissionId[]" id="AdmissionId" class="nostyle" style="width:100%;" multiple="multiple" >
													<option></option>
													<?php echo $StudentList; ?>
													</select>
												</div>
											</div>
										</div>
									</div>	
									<input type="hidden" name="Action" value="ManageVehicleRouteDetail" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="VehicleRouteId" value="<?php echo $UniqueId; ?>" readonly>
									<?php if($count6==1) { ?>
									<input type="hidden" name="VehicleRouteDetailId" value="<?php echo $UpdateVehicleRouteDetailId; ?>" readonly>
									<?php } ?>
									<?php ActionButton($VehicleRouteDetailButtonContent,4); ?>
								</form>
							</div>
						</div>	
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Listing <?php echo $ViewVehicleRouteName; ?> Students</span>
									<?php if($count5>0) { ?>
									<div class="PrintClass">
										<form method=post action=/Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintVehicleRouteDetail" readonly>
										<input type="hidden" name="HeadingName" value="PrintVehicleRouteDetailHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Vehicle Route Detail List"></button>
										</form>
									</div>
									<?php } ?>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content clearfix noPad">
								<table id="VehicleRouteDetailTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>SNo</th>
											<th>Stoppage Name</th>
											<th>Student</th>
											<th>Last Updated</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								<?php
									$PrintTable2="<table class=\"responsive dynamicTable display table table-bordered\"><tr><th>SNo</th><th>Stoppage Name</th><th>Student Name</th></tr>
											$PrintTable2Data </table>";
									$_SESSION['PrintVehicleRouteDetail']=$PrintTable2;
									$_SESSION['PrintVehicleRouteDetailHeading']="\"$ViewVehicleRouteName\" Route Detail list for Session $CURRENTSESSION";
								?>
							</div>
						</div>
					<?php } ?>
					</div>					
                </div>
            </div>
        </div>

<script type="text/javascript">
$(document).ready(function() {

$("#VehicleRouteVehicleId").select2();
$('#VehicleRouteVehicleId').select2({placeholder: "Select"});
$("#Route").select2();
$("#RouteStoppageId").select2();
$('#RouteStoppageId').select2({placeholder: "Select"});
$("#RouteTo").select2();
$('#RouteTo').select2({placeholder: "Select"});
$("#AdmissionId").select2();
$('#AdmissionId').select2({placeholder: "Select"});
$("#Route").select2();
$('#VehicleRouteTable').dataTable({
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

$('#VehicleRouteDetailTable').dataTable({
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
	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageVehicleRoute").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			VehicleRouteName: {
				required: true,
			},
			VehicleRouteVehicleId: {
				required: true,
			},
			RouteTo: {
				required: true,
			}
		},
		messages: {
			VehicleRouteName: {
				required: "Please enter this!!",
			},
			VehicleRouteVehicleId: {
				required: "Please select this!!",
			},
			RouteTo: {
				required: "Please select this!!",
			}
		}   
	});
	$("#ManageVehicleRouteDetail").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			RouteStoppageId: {
				required: true,
			}
		},
		messages: {
			RouteStoppageId: {
				required: "Please select this!!",
			}
		}   
	});

});
</script>
	
<?php
include("Template/Footer.php");
?>