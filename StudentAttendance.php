<?php
$PageName="StudentAttendance";
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
                <?php $BreadCumb="Student Attendance"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>

				<?php
				$GETSectionId=isset($_GET['SectionId']) ? $_GET['SectionId'] : '';
				$query3="select ClassName,SectionName,SectionId from class,section where 
					class.ClassId=section.ClassId and class.ClassStatus='Active' and
					section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
				$check3=mysqli_query($CONNECTION,$query3);
				$ValidSectionId=$SelectedClass=$ListCurrentClass=$ListAllStudent="";
				while($row3=mysqli_fetch_array($check3))
				{
					$ComboCurrentClassName=$row3['ClassName'];
					$ComboCurrentSectionName=$row3['SectionName'];
					$ComboCurrentSectionId=$row3['SectionId'];
					if($GETSectionId==$ComboCurrentSectionId)
					{
						$SelectedClass="selected";
						$ValidSectionId=1;
					}
					else
					$SelectedClass="";
					$ListCurrentClass.="<option value=\"$ComboCurrentSectionId\" $SelectedClass>$ComboCurrentClassName $ComboCurrentSectionName</option>";
				}
				$query1="select admission.AdmissionId,StudentName,FatherName from studentfee,registration,admission where 
				studentfee.AdmissionId=admission.AdmissionId and Status='Studying' and
				registration.RegistrationId=admission.RegistrationId and
				studentfee.Session='$CURRENTSESSION' and
				studentfee.Sectionid='$GETSectionId' 
				order by StudentName ";
				$check1=mysqli_query($CONNECTION,$query1);
				while($row1=mysqli_fetch_array($check1))
				{
					$AdmissionId=$row1['AdmissionId'];
					$StudentName=$row1['StudentName'];
					$FatherName=$row1['FatherName'];
					$ListAllStudent.="<option value=\"$AdmissionId\">$StudentName ($FatherName)</option>";
				}
				
				?>

				
                <div class="row-fluid">
					<div class="span12">
					<div class="box chart gradient">
						<div class="title">
							<h4>
								<span>Select Class</span>
							</h4>
							<a href="#" class="minimize">Minimize</a>
						</div>
						<div class="content" style="padding-bottom:0;">
							<form class="form-horizontal" action="ReportAction" method="post" id="StudentAttendance1" name="StudentAttendance1">
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
								<input type="hidden" name="Action" value="StudentAttendance" readonly>
								<?php $ButtonContent="Get Student"; ActionButton($ButtonContent,7); ?>
							</form>
						</div>
					</div>
				</div>
									
				<?php if($ValidSectionId==1) { ?>
                <div class="row-fluid">
					<div class="span6">
					<div class="box chart gradient">
						<div class="title">
							<h4>
								<span>Student Attendance</span>
							</h4>
							<a href="#" class="minimize">Minimize</a>
						</div>
						<div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" method="post" id="StudentAttendance" name="StudentAttendance">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal" readonly>Date</label>
												<input class="span8" id="Date" type="text" name="Date" readonly  onchange="showdetail(this.value,'GetAttendanceReportForADayStudent','GetAttendanceReportForADayStudent')"/>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="leftBox">
												<div class="searchBox"><input type="text" id="box1Filter" class="searchField" placeholder="Search"/><button id="box1Clear" type="button" class="btn"><span class="icon12  icomoon-icon-cancel-3"></span></button></div>
												<select id="box1View" multiple="multiple" class="multiple nostyle" style="height:200px;">
												<?php echo $ListAllStudent; ?>
												</select>
												<br/>
												<span id="box1Counter" class="count"></span>
												<div class="dn"><select id="box1Storage" name="box1Storage" class="nostyle"></select></div>
											</div>
											<div class="dualBtn">
												<button id="to2" type="button" class="btn" ><span class="icon12 minia-icon-arrow-right-3"></span></button>
												<button id="allTo2" type="button" class="btn" ><span class="icon12 iconic-icon-last"></span></button>
												<button id="to1" type="button" class="btn marginT5"><span class="icon12 minia-icon-arrow-left-3"></span></button>
												<button id="allTo1" type="button"class="btn marginT5" ><span class="icon12 iconic-icon-first"></span></button>
											</div>
											<div class="rightBox">
												<div class="searchBox"><input type="text" id="box2Filter" class="searchField" placeholder="Search" /><button id="box2Clear"  type="button" class="btn" ><span class="icon12  icomoon-icon-cancel-3"></span></button></div>
												<select id="box2View" name="box2View[]" multiple="multiple" class="multiple nostyle" style="height:200px;"></select><br/>
												<span id="box2Counter" class="count"></span>
												<div class="dn"><select id="box2Storage" class="nostyle"></select></div>
											</div>
										</div> 
									</div>
									<div class="form-row row-fluid">
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<input type="hidden" name="Action" value="StudentAttendance" readonly>
										<input type="hidden" name="SectionId" value="<?php echo $GETSectionId; ?>" readonly>
										<button type="submit" class="btn btn-info" name="Present" value="Present">Present</button>
										<button type="submit" class="btn btn-info" name="Absent" value="Absent">Absent</button>
										<button type="submit" class="btn btn-info" name="HalfDay" value="HalfDay">Half Day</button>
										<button type="submit" class="btn btn-info" name="Blank" value="Blank">Blank</button>
										<button type="submit" class="btn btn-info" name="Holiday" value="Holiday">Holiday</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="span6">
						<div id="GetAttendanceReportForADayStudent"></div>
					</div>
                </div>
				<?php } ?>
            </div>
        </div>
<script type="text/javascript">
	$(document).ready(function() {
	$.configureBoxes();
	if($('#Date').length) {
		$("#Date").datepicker({ yearRange: "-10:+0", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
		$("#SectionId").select2();
		$('#SectionId').select2({placeholder: "Select"});
		$("input, textarea, select").not('.nostyle').uniform();
		$("#StudentAttendance1").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				SectionId: {
					required: true,
				}
			},
			messages: {
				SectionId: {
					required: "Please select this!!",
				}
			}   
		});
		$("#StudentAttendance").validate({
			rules: {
				Date: {
					required: true,
				}
			},
			messages: {
				Date: {
					required: "Please select date!!",
				}
			}   
		});
	});
</script>    
<?php
include("Template/Footer.php");
?>