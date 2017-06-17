<?php
$PageName="Admission";
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
			<?php $BreadCumb="Student Admission"; BreadCumb($BreadCumb); ?>
				
				<?php DisplayNotification(); 
				$Student=isset($_POST['Student']) ? $_POST['Student'] : '';
				$TransportFee=isset($_POST['TransportFee']) ? $_POST['TransportFee'] : '';
				$Distance=isset($_POST['Distance']) ? $_POST['Distance'] : '';
				$TransportFeeChecked=$StudentSelected=$ValidationRules=$ValidationMessages=$i=$STR=$ListAllFee="";
				if($TransportFee=="Yes")
				$TransportFeeChecked="checked=checked";
				$query="Select RegistrationId,StudentName,FatherName,Mobile,ClassName,SectionName,section.SectionId,class.ClassId from registration,class,section where
					registration.Session='$CURRENTSESSION' and
					class.ClassId=section.ClassId and
					registration.SectionId=section.SectionId and
					Status='NotAdmitted'
					order by StudentName";
				$check=mysqli_query($CONNECTION,$query);
				$ListAllStudents="";
				while($row=mysqli_fetch_array($check))
				{
					$ComboStudentName=$row['StudentName'];
					$ComboFatherName=$row['FatherName'];
					$ComboMobile=$row['Mobile'];
					$ComboClassName=$row['ClassName'];
					$ComboSectionName=$row['SectionName'];
					$ComboRegistrationId=$row['RegistrationId'];
					$ComboSectionId=$row['SectionId'];
					$ComboClassId=$row['ClassId'];
					if($ComboRegistrationId==$Student)
					{
						$Selected="selected";
						$StudentSelected=1;
						$SectionIdSelected=$ComboSectionId;
						$ClassIdSelected=$ComboClassId;
					}
					else
					$Selected="";
					$ListAllStudents.="<option value=\"$ComboRegistrationId\" $Selected>$ComboStudentName $ComboFatherName $ComboMobile $ComboClassName $ComboSectionName</option>";
				}
				?>	
					
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Student Admission</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding:5px;">
								<form class="form-horizontal" action="" name="StudentAdmission" id="StudentAdmission" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4 mandatory" for="Student">Select Student</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="Student" id="Student" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListAllStudents; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="TransportFee">Transport</label>
												<input tabindex="2" class="styled" id="TransportFee" type="checkbox" name="TransportFee" value="Yes" <?php echo $TransportFeeChecked; ?> />
												Check only if Transport facility is required
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="FeeType">Distance</label>
												<div class="controls sel span8">   
												<?php
												GetCategoryValue('Distance','Distance',$Distance,'','','','',3,'');
												?>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="Action" value="StudentAdmission" readonly>
									<?php $ButtonContent="Get Fee Structure"; ActionButton($ButtonContent,4); ?>
								</form>
                            </div>
                        </div>
                    </div>	
					<div class="span8">
					<?php
					if($StudentSelected==1)
					{
						$query11="select MasterEntryValue,FeeType,Amount,FeeId,Distance from fee,masterentry where
								fee.FeeType=masterentry.MasterEntryId and SectionId='$SectionIdSelected' and Session='$CURRENTSESSION' and (Distance='' or Distance='$Distance') ";
						$check11=mysqli_query($CONNECTION,$query11);
						$count11=mysqli_num_rows($check11);
						if($count11>0)
						{
							$TabIndex="15";
						while($row11=mysqli_fetch_array($check11))
						{
							$i++;
							$FeeName=$row11['MasterEntryValue'];
							$FeeAmount=$row11['Amount'];
							$FeeId=$row11['FeeId'];
							$Dis=$row11['Distance'];
							if($Dis!="")
							{
								$DistanceName=GetCategoryValueOfId($Dis,'Distance');
								$DistanceName="From <b>$DistanceName</b>";
							}
							else
								$DistanceName="";
							$STR.=$FeeId;
							if($i<$count11)
							$STR.="-";
							$ListAllFee.="<div class=\"form-row row-fluid\">
										<div class=\"span12\">
											<div class=\"row-fluid\">
												<label class=\"form-label span4\" for=\"normal\" readonly>$FeeName $DistanceName
												<span class=\"help-block\">Actual Fee: <b>$FeeAmount $CURRENCY</b></span></label>
												<input tabindex=\"$TabIndex\" class=\"span8\" id=\"$FeeId\" type=\"text\" name=\"$FeeId\" value=\"$FeeAmount\" />
											</div>
										</div>
									</div>";
							$ValidationRules.="$FeeId: {
												required: true,
												remote: \"RemoteValidation?Action=IsAmountWithZero&Id=$FeeId\"
											},";
							$ValidationMessages.="$FeeId: {
											required: \"Please enter fee!!\",
											remote: jQuery.format(\"Amount should be numeric!!\")
											},";
							$TabIndex++;
						}
						?>
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Set Fee Structure</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="AdmissionConfirm" id="AdmissionConfirm" method="Post">
								
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="normal">Date of Admission</label>
											<input tabindex="11" class="span8" id="DOA" type="text" name="DOA" readonly />
										</div>
									</div>
								</div>
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="normal">Admission No</label>
											<input tabindex="12" class="span8" id="AdmissionNo" type="text" name="AdmissionNo" />
										</div>
									</div>
								</div>
								<?php echo $ListAllFee; ?>
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="normal">Remarks</label>
											<textarea tabindex="31" class="span8" id="Remarks" name="Remarks" /></textarea>
										</div>
									</div>
								</div>
									   <?php $ButtonContent="Confirm"; ActionButton($ButtonContent,32); ?>
									   <input type="hidden" name="Action" value="AdmissionConfirm" readonly>
									   <input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									   <input type="hidden" name="RegistrationId" value="<?php echo $Student; ?>" readonly>
									   <input type="hidden" name="SectionId" value="<?php echo $SectionIdSelected; ?>" readonly>
									   <input type="hidden" name="FeeArray" value="<?php echo $STR; ?>" readonly>
									   <input type="hidden" name="Distance" value="<?php echo $Distance; ?>" readonly>
									
								</form>
                            </div>
                        </div>						
						<?php
						}
						else
						echo "<div class=\"alert alert-error\">No fee structure set for this class!!</div>";
					}
					else
					echo "<div class=\"alert alert-info\">Please select one student!!</div>";
					?>
					</div>
                </div>
            </div>
        </div>
		
<script type="text/javascript">
	$(document).ready(function() {
		$("#Student").select2();
		$("#Distance").select2();
		if($('#DOA').length) {
		$('#DOA').datepicker({ yearRange: "-5:+5", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		$("input, textarea, select").not('.nostyle').uniform();
		$('#Student').select2({placeholder: "Select"});
		$('#Distance').select2({placeholder: "Select"});
		$("#StudentAdmission").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				Student: {
					required: true,
				},
				Distance: {
					required: "#TransportFee:checked",
				}
			},
			messages: {
				Student: {
					required: "Please select this!!",
				},
				Distance: {
					required: "Please select this!!",
				}
			}   
		});
		$("#AdmissionConfirm").validate({
			rules: {
				DOA: {
					required: true,
				},
				AdmissionNo: {
					required: true,
					//remote: "RemoteValidation?Action=IsValidAdmissionNo&Id=AdmissionNo",
				},
				<?php echo $ValidationRules; ?>
			},
			messages: {
				DOA: {
					required: "Please select date!!",
				},
				AdmissionNo: {
					required: "Please enter this!!",
					//remote: jQuery.format("Admission No already exists!!"),
				},
				<?php echo $ValidationMessages; ?>
			}   
		});		
	});
</script>
<?php
include("Template/Footer.php");
?>