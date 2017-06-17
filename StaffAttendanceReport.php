<?php
$PageName="StaffAttendanceReport";
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
                <?php $BreadCumb="Staff Attendance Report"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				<?php
				$smallsize="style=\"font-size:10px;\"";
				$Sessions=explode("-",$CURRENTSESSION);
				$StartingYear=$Sessions[0];
				$EndingYear=$Sessions[1];
				$MonthYear=$count1=$ShowMonth=$Valid=$u=$STR=$STRHeading="";
				$DateArray=array();
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
				
				if($Valid==1)
				{
					$MonthYearArray=explode("-",$POSTMonthYear);
					$SelectedMonth=$MonthYearArray[0];
					$SelectedYear=$MonthYearArray[1];
					
					$DaysInMonth=cal_days_in_month(CAL_GREGORIAN,$SelectedMonth,$SelectedYear);
					$date1="$SelectedYear-$SelectedMonth-01";
					$date2="$SelectedYear-$SelectedMonth-$DaysInMonth";
					$date1timestamp=strtotime($date1);
					$date2timestamp=strtotime($date2);
					
					$query="select Attendance,Date from staffattendance where Date>='$date1timestamp' and Date<='$date2timestamp' ";
					$check=mysqli_query($CONNECTION,$query);
					while($row=mysqli_fetch_array($check))
					{
						$AttendanceArray[]=$row['Attendance'];
						$DateArray[]=date("d-m-Y",$row['Date']);
					}
					
					$query1="select StaffName,StaffMobile,StaffId,MasterEntryValue from staff,masterentry where
						staff.StaffPosition=masterentry.MasterEntryId and StaffStatus='Active'
						order by StaffName";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					while($row1=mysqli_fetch_array($check1))
					{
						$u++;
						$StaffName=$row1['StaffName'];
						$Mobile=$row1['StaffMobile'];
						$StaffPosition=$row1['MasterEntryValue'];
						$StaffId=$row1['StaffId'];
						if($u==1)
						$STRHeading.="<th>Name</th>";
						
						$STR.="<tr><th>$StaffName <br>$Mobile ($StaffPosition)</th>";
						
						$A=$P=$H=$HD=$OD=$PL=0;
						for($l=1;$l<=$DaysInMonth;$l++)
						{
							$Found=0;
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
									if($AllAttendanceOfDayValue[0]==$StaffId)
									{
										$InTime=date("h:ia",$AllAttendanceOfDayValue[3]);
										$OutTime=date("h:ia",$AllAttendanceOfDayValue[4]);
										if($AllAttendanceOfDayValue[1]=="P")
										{
											$STR.="<td style=\"background-color:green;\" $smallsize class=\"tip\" title=\"In Time: $InTime Out Time: $OutTime\">P</td>";
											$P++;
										}
										elseif($AllAttendanceOfDayValue[1]=="A")
										{
											$STR.="<td style=\"background-color:red;\" $smallsize class=\"tip\" title=\"In Time: $InTime Out Time: $OutTime\">A</td>";
											$A++;
										}
										elseif($AllAttendanceOfDayValue[1]=="HD")
										{
											$STR.="<td style=\"background-color:orange;\" $smallsize class=\"tip\" title=\"In Time: $InTime Out Time: $OutTime\">HD</td>";
											$HD++;
										}
										elseif($AllAttendanceOfDayValue[1]=="H")
										{
											$STR.="<td style=\"background-color:yellow;\" $smallsize class=\"tip\" title=\"In Time: $InTime Out Time: $OutTime\">H</td>";
											$H++;
										}
										elseif($AllAttendanceOfDayValue[1]=="OD")
										{
											$STR.="<td style=\"background-color:pink;\" $smallsize class=\"tip\" title=\"In Time: $InTime Out Time: $OutTime\">OD</td>";
											$OD++;
										}
										elseif($AllAttendanceOfDayValue[1]=="PL")
										{
											$STR.="<td style=\"background-color:blue;\" $smallsize class=\"tip\" title=\"In Time: $InTime Out Time: $OutTime\">PL</td>";
											$PL++;
										}
										$Found=1;
									}
								}
								
								if($Found!=1)
								$STR.="<Td>-</td>";
							}
						}
						if($u==1)
						$STRHeading.="<th $smallsize>P</th><th $smallsize>A</th><th $smallsize>HD</th><th $smallsize>H</th><th $smallsize>OD</th><th $smallsize>PL</th></tr>";
						$STR.="<td $smallsize>$P</td><Td $smallsize>$A</td><Td $smallsize>$HD</td><Td $smallsize>$H</td><Td $smallsize>$OD</td><Td $smallsize>$PL</td></tr>";
					}
				}
				
				
				?>				
				
                <div class="row-fluid">
					<div class="span12">
                        <div class="box calendar gradient">
                            <div class="title">
                                <h4>
                                    <span>Select Month</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="width:98%; margin-bottom:10px; float:left; clear:both; "> 
								<form class="form-horizontal" action="" method="post" id="StaffAttendanceReport" name="StaffAttendanceReport">
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
										<input type="hidden" name="Action" value="StaffAttendance" readonly>
										<?php $ButtonContent="Get"; ActionButton($ButtonContent,7); ?>
									</div>
								</form>
                            </div>
                        </div>
					</div>
                </div>
				
				<?php if($count1==0) { ?>
				<div class="alert alert-error">No staff found!!</div>
				<?php } elseif($Valid==1) { ?>
                <div class="row-fluid">
					<div class="span12">
                        <div class="box calendar gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo "Staff Attendance Report of $POSTMonthYear"; ?></span>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintStaffAttendanceList" readonly>
										<input type="hidden" name="HeadingName" value="PrintStaffAttendanceHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Staff Attendance List"></button>
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
								</thead>
								<Tbody>
									$STR
								</tbody>
							</table>";
							echo $FinalAttendance;
								$PrintStaffAttendanceList=$FinalAttendance;
								$_SESSION['PrintStaffAttendanceList']=$PrintStaffAttendanceList;
								$PrintStaffAttendanceHeading="Staff Attendance Report of $POSTMonthYear";
								$_SESSION['PrintStaffAttendanceHeading']=$PrintStaffAttendanceHeading; 
								$_SESSION['PrintCategory']="Attendance";
							?>
                            </div>
                        </div>
					</div>
                </div>
				<?php } elseif($POSTMonthYear=="") { ?>
				<div class="alert alert-info">Please select month year!!</div>
				<?php } else { ?>
				<div class="alert alert-error">Please select valid month year!!</div>
				<?php } ?>
            </div>
        </div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#MonthYear").select2();
		$('#MonthYear').select2({placeholder: "Select"});
		$("input, textarea, select").not('.nostyle').uniform();
		$("#StaffAttendanceReport").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				MonthYear: {
					required: true,
				}
			},
			messages: {
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