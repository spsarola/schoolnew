<?php
$PageName="StudentAttendanceReport";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
include("Include.php");
if(isset($ErrorMessage))
{
	$Message=$ErrorMessage;
	$Type=error;
	SetNotification($Message,$Type);
	header("Location:ErrorPage");
	exit();
}
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
                <?php $BreadCumb="Student Attendance Report"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				<?php
				$smallsize="style=\"font-size:10px;\"";
				$POSTSectionId=isset($_POST['SectionId']) ? $_POST['SectionId'] : '';
				$query3="select ClassName,SectionName,SectionId from class,section where 
					class.ClassId=section.ClassId and class.ClassStatus='Active' and
					section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
				$check3=mysqli_query($CONNECTION,$query3);
				$SelectedClass=$ValidSectionId=$ListCurrentClass=$ShowMonth=$Valid=$count1=$u=$STR=$STRHeading="";
				$DateArray=array();
				while($row3=mysqli_fetch_array($check3))
				{
					$ComboCurrentClassName=$row3['ClassName'];
					$ComboCurrentSectionName=$row3['SectionName'];
					$ComboCurrentSectionId=$row3['SectionId'];
					if($POSTSectionId==$ComboCurrentSectionId)
					{
						$SelectedClass="selected";
						$ValidSectionId=1;
					}
					else
					$SelectedClass="";
					$ListCurrentClass.="<option value=\"$ComboCurrentSectionId\" $SelectedClass>$ComboCurrentClassName $ComboCurrentSectionName</option>";
				}
				
				$Sessions=explode("-",$CURRENTSESSION);
				$StartingYear=$Sessions[0];
				$EndingYear=$Sessions[1];
				
				$POSTMonthYear=isset($_POST['MonthYear']) ? $_POST['MonthYear'] : '';
				for($i=$StartingYear;$i<$EndingYear;$i++)
				{
					$k=4;
					$i1=$StartingYear;
					for($j=1;$j<=12;$j++)
					{
						if($k>=13)
						{
						$k=1;
						$i1=$EndingYear;
						}
						$k1=str_pad($k,2,"0",STR_PAD_LEFT);
						$MonthYearComb="$k1-$i1";
						if($MonthYearComb==$POSTMonthYear)
						{
						$Selected="selected";
						$Valid=1;
						}
						else
						$Selected="";
						$ShowMonth.="<option value=\"$MonthYearComb\" $Selected>$MonthYearComb</option>";
						$k++;
					}
				}
				
				if($Valid==1 && $ValidSectionId==1)
				{
					$MonthYearArray=explode("-",$POSTMonthYear);
					$SelectedMonth=$MonthYearArray[0];
					$SelectedYear=$MonthYearArray[1];
					
					$DaysInMonth=cal_days_in_month(CAL_GREGORIAN,$SelectedMonth,$SelectedYear);
					$date1="$SelectedYear-$SelectedMonth-01";
					$date2="$SelectedYear-$SelectedMonth-$DaysInMonth";
					$date1timestamp=strtotime($date1);
					$date2timestamp=strtotime($date2);
					
					$query="select Attendance,Date from studentattendance where Date>='$date1timestamp' and Date<='$date2timestamp' ";
					$check=mysqli_query($CONNECTION,$query);
					while($row=mysqli_fetch_array($check))
					{
						$AttendanceArray[]=$row['Attendance'];
						$DateArray[]=date("d-m-Y",$row['Date']);
					}
					
					$query1="select StudentName,FatherName,Mobile,admission.AdmissionId from registration,admission,studentfee where
						registration.RegistrationId=admission.RegistrationId and
						admission.AdmissionId=studentfee.AdmissionId and
						studentfee.SectionId='$POSTSectionId' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					while($row1=mysqli_fetch_array($check1))
					{
						$u++;
						$StudentName=$row1['StudentName'];
						$FatherName=$row1['FatherName'];
						$Mobile=$row1['Mobile'];
						$AdmissionId=$row1['AdmissionId'];
						if($u==1)
						$STRHeading.="<th>Name</th>";
						
						$STR.="<tr><th>$StudentName <br>F/n $FatherName</th>";
						
						$A=$P=$H=$HL=0;
						for($l=1;$l<=$DaysInMonth;$l++)
						{
							if($u==1)
							$STRHeading.="<td $smallsize>$l</td>";
							
							$l=str_pad($l,2,"0",STR_PAD_LEFT);
							$DateForSearch="$l-$SelectedMonth-$SelectedYear";
							if($DateArray!="")
							$SearchIndex=array_search($DateForSearch,$DateArray);
							else
							$SearchIndex=FALSE;
							if($SearchIndex===FALSE){ $STR.="<td>-</td>"; }
							else
							{
								$AllAttendanceOfDay=$AttendanceArray[$SearchIndex];
								$AllAttendanceOfDay=explode(",",$AllAttendanceOfDay);
								foreach($AllAttendanceOfDay as $AllAttendanceOfDayValue)
								{
									$AllAttendanceOfDayValue=explode("-",$AllAttendanceOfDayValue);
									if($AllAttendanceOfDayValue[0]==$AdmissionId)
									{
										if($AllAttendanceOfDayValue[1]=="P")
										{
											$STR.="<td style=\"background-color:green;\" $smallsize>P</td>";
											$P++;
										}
										elseif($AllAttendanceOfDayValue[1]=="A")
										{
											$STR.="<td style=\"background-color:red;\" $smallsize>A</td>";
											$A++;
										}
										elseif($AllAttendanceOfDayValue[1]=="HL")
										{
											$STR.="<td style=\"background-color:orange;\" $smallsize>HL</td>";
											$HL++;
										}
										elseif($AllAttendanceOfDayValue[1]=="H")
										{
											$STR.="<td style=\"background-color:yellow;\" $smallsize>H</td>";
											$H++;
										}
									}
								}
							}
						}
						if($u==1)
						$STRHeading.="<th $smallsize>P</th><th $smallsize>A</th><th $smallsize>HL</th><th $smallsize>H</th></tr>";
						$STR.="<td $smallsize>$P</td><Td $smallsize>$A</td><Td $smallsize>$HL</td><Td $smallsize>$H</td></tr>";
					}
				}
				
				
				?>				
				
                <div class="row-fluid">
					<div class="span12">
                        <div class="box calendar gradient">
                            <div class="title">
                                <h4>
                                    <span>Select Class</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="width:98%; margin-bottom:10px; float:left; clear:both; "> 
								<form class="form-horizontal" action="" method="post" id="StudentAttendanceReport" name="StudentAttendanceReport">
									<div class="span4">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="SectionId" readonly>Class</label>
													<div class="controls sel span8">   
													<select tabindex="1" name="SectionId" id="SectionId" class="nostyle" style="width:100%;" >
													<option></option>
													<?php echo $ListCurrentClass; ?>
													</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="MonthYear" readonly>Month Year</label>
													<div class="controls sel span8">   
													<select tabindex="1" name="MonthYear" id="MonthYear" class="nostyle" style="width:100%;" >
													<option></option>
													<?php echo $ShowMonth; ?>
													</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<input type="hidden" name="Action" value="StudentAttendance" readonly>
										<?php $ButtonContent="Get Student"; ActionButton($ButtonContent,7); ?>
									</div>
								</form>
                            </div>
                        </div>
					</div>
                </div>
				
				<?php if($count1==0) { ?>
				<div class="alert alert-error">No student found in selected class!!</div>
				<?php } elseif($Valid==1 && $ValidSectionId==1) { ?>
                <div class="row-fluid">
					<div class="span12">
                        <div class="box calendar gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo "Student Attendance Report of $POSTMonthYear"; ?></span>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintStudentAttendanceList" readonly>
										<input type="hidden" name="HeadingName" value="PrintStudentAttendanceHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Student Attendance List"></button>
										</form>
									</div>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad clearfix"> 
							<?php
							$FinalAttendance="<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive display table table-bordered\" width=\"100%\">
								<thead>
									<tr>
										$STRHeading
									</tr>
								</thead>
								<Tbody>
									$STR
								</tbody>
							</table>";
							echo $FinalAttendance;
								$PrintStudentAttendanceList=$FinalAttendance;
								$_SESSION['PrintStudentAttendanceList']=$PrintStudentAttendanceList;
								$PrintStudentAttendanceHeading="Student Attendance Report of $POSTMonthYear";
								$_SESSION['PrintStudentAttendanceHeading']=$PrintStudentAttendanceHeading; 
								$_SESSION['PrintCategory']="Attendance";
							?>
                            </div>
                        </div>
					</div>
                </div>
				<?php } elseif($POSTSectionId=="" && $POSTMonthYear=="") { ?>
				<div class="alert alert-info">Please select class & month year!!</div>
				<?php } else { ?>
				<div class="alert alert-error">Please select valid class & month year!!</div>
				<?php } ?>
            </div>
        </div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#SectionId").select2();
		$('#SectionId').select2({placeholder: "Select"});
		$("#MonthYear").select2();
		$('#MonthYear').select2({placeholder: "Select"});
		$("input, textarea, select").not('.nostyle').uniform();
		$("#StudentAttendanceReport").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				SectionId: {
					required: true,
				},
				MonthYear: {
					required: true,
				}
			},
			messages: {
				SectionId: {
					required: "Please select this!!",
				},
				MonthYear: {
					required: "Please select this!!",
				}
			}   
		});
	});
</script>   
<?php
include("Template/Footer.php");
?>