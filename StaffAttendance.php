<?php
$PageName="StaffAttendance";
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
                <?php $BreadCumb="Staff Attendance"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
                <div class="row-fluid">
				        <div class="span7">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Staff Attendance</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
                                    <form class="form-horizontal" action="Action" method="post" id="StaffAttendance" name="StaffAttendance">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="normal">Date</label>
													<input class="span8" id="Date" type="text" name="Date" readonly  onchange="showdetail(this.value,'GetAttendanceReportForADay','GetAttendanceReportForADay')"/>
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="InTime">In Time</label>
													<input class="span8" id="InTime" type="text" name="InTime" readonly />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="OutTime">Out Time</label>
													<input class="span8" id="OutTime" type="text" name="OutTime" readonly />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="leftBox">
													<div class="searchBox"><input type="text" id="box1Filter" class="searchField" placeholder="Search"/><button id="box1Clear" type="button" class="btn"><span class="icon12  icomoon-icon-cancel-3"></span></button></div>
													<select id="box1View" multiple="multiple" class="multiple nostyle" style="height:200px;">
													<?php
														$query1="select StaffName,StaffId,MasterEntryValue from staff,masterentry where 
														staff.StaffPosition=masterentry.MasterEntryId and staff.StaffStatus='Active' order by StaffName ";
														$check1=mysqli_query($CONNECTION,$query1);
														while($row1=mysqli_fetch_array($check1))
														{
															$StaffName=$row1['StaffName'];
															$StaffId=$row1['StaffId'];
															$StaffPosition=$row1['MasterEntryValue'];
															echo "<option value=\"$StaffId\">$StaffName ($StaffPosition)</option>";
														}
													?>
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
												<input type="hidden" name="Action" value="StaffAttendance" readonly>
												<button type="submit" class="btn btn-info" name="Present" value="Present">Present</button>
												<button type="submit" class="btn btn-info" name="Absent" value="Absent">Absent</button>
												<button type="submit" class="btn btn-info" name="HalfDay" value="HalfDay">Half Day</button>
												<button type="submit" class="btn btn-info" name="PaidLeave" value="PaidLeave">Paid Leave</button>
												<button type="submit" class="btn btn-info" name="OnDuty" value="OnDuty">On Duty</button>
												<button type="submit" class="btn btn-info" name="Blank" value="Blank">Blank</button>
												<button type="submit" class="btn btn-info" name="Holiday" value="Holiday">Holiday</button>
										</div>
                                    </form>
                                </div>
                            </div>
                        </div>
						<div class="span5">
							<div id="GetAttendanceReportForADay"></div>
						</div>
                </div>
            </div>
        </div>
<script type="text/javascript">
	$(document).ready(function() {
	$.configureBoxes();
	if($('#Date').length) {
		$("#Date").datepicker({ yearRange: "-10:+0", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
	if($('#InTime').length) {
		$("#InTime").timepicker({ yearRange: "-10:+0", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
	if($('#OutTime').length) {
		$("#OutTime").timepicker({ yearRange: "-10:+0", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
		$("input, textarea, select").not('.nostyle').uniform();
		$("#StaffAttendance").validate({
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