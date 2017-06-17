<?php
$PageName="Promotion";
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
			<?php $BreadCumb="Student Promotion"; BreadCumb($BreadCumb); ?>
				
				<?php DisplayNotification(); 
				$Student=isset($_POST['Student']) ? $_POST['Student'] : '';
				$TransportFee=isset($_POST['TransportFee']) ? $_POST['TransportFee'] : '';
				$NextSession=isset($_POST['NextSession']) ? $_POST['NextSession'] : '';
				$NextSectionId=isset($_POST['NextSectionId']) ? $_POST['NextSectionId'] : '';
				$CurrentSectionId=isset($_POST['CurrentSectionId']) ? $_POST['CurrentSectionId'] : '';
				$Distance=isset($_POST['Distance']) ? $_POST['Distance'] : '';
				$AdmissionIdSelected=$TransportFeeChecked=$ListAllClass=$ValidationRules=$ValidationMessages=$ListAllStudents=$ListCurrentClass=$count22=$StudentSelected=$AlreadyAdmittedMessage="";
				if($TransportFee=="Yes")
				$TransportFeeChecked="checked=checked";
				
				if($NextSession!="")
				{
					$query2="select ClassName,SectionName,SectionId from class,section where 
						class.ClassId=section.ClassId and class.ClassStatus='Active' and
						section.SectionStatus='Active' and class.Session='$NextSession' order by ClassName";
					$check2=mysqli_query($CONNECTION,$query2);
					while($row2=mysqli_fetch_array($check2))
					{
						$SelectClassName=$row2['ClassName'];
						$SelectSectionName=$row2['SectionName'];
						$SelectSectionId=$row2['SectionId'];
						$SectionIdArray[]="$SelectSectionId";
						$SectionNameArray[]="$SelectClassName $SelectSectionName";
						$Selected="";
						if($NextSectionId==$SelectSectionId)
						{
							$Selected="selected";
							$ValidSection=1;
						}
						else
						$Selected="";
						$ListAllClass.="<option value=\"$SelectSectionId\" $Selected>$SelectClassName $SelectSectionName</option>";
					}

					$query4="select AdmissionId from studentfee where Session='$NextSession' ";
					$check4=mysqli_query($CONNECTION,$query4);
					while($row4=mysqli_fetch_array($check4))
					$AdmittedStudent[]=$row4['AdmissionId'];
				}
				
				if($Student!="" && $CurrentSectionId!="")
				{
					$query="Select admission.AdmissionId,registration.RegistrationId,StudentName,FatherName,Mobile,ClassName,SectionName,section.SectionId,class.ClassId from registration,class,section,admission,studentfee where
						studentfee.Session='$CURRENTSESSION' and
						class.ClassId=section.ClassId and
						studentfee.SectionId=section.SectionId and
						registration.RegistrationId=admission.RegistrationId and
						admission.AdmissionId=studentfee.AdmissionId and 
						studentfee.SectionId='$CurrentSectionId' and 
						Status='Studying'
						order by StudentName";
					$check=mysqli_query($CONNECTION,$query);
					$SearchAdmittedStudent=$ComboAdmissionId=$AlreadyAdmitted=$AlreadyAdmittedMessage=$AdmittedStudent="";
					while($row=mysqli_fetch_array($check))
					{
						$ComboStudentName=$row['StudentName'];
						$ComboAdmissionId=$row['AdmissionId'];
						$ComboFatherName=$row['FatherName'];
						$ComboMobile=$row['Mobile'];
						foreach($Student as $StudentValue)
						{
							if($AdmittedStudent!="")
							$SearchAdmittedStudent=array_search($StudentValue,$AdmittedStudent);
							else
							$SearchAdmittedStudent=FALSE;
							if($ComboAdmissionId==$StudentValue && $SearchAdmittedStudent===FALSE)
							{
								$Selected="selected";
								$StudentSelected=1;
								$SectionIdSelected=isset($ComboSectionId) ? $ComboSectionId : '';
								$ClassIdSelected=isset($ComboClassId) ? $ComboClassId : '';
								$FinalStudent[]=$StudentValue;
								break;
							}
							elseif($ComboAdmissionId==$StudentValue)
							$AlreadyAdmitted++;
							else
							$Selected="";
						}
						$ListAllStudents.="<option value=\"$ComboAdmissionId\" $Selected>$ComboStudentName $ComboFatherName $ComboMobile</option>";
					}	
					if($AlreadyAdmitted>0)
					$AlreadyAdmittedMessage="$AlreadyAdmitted students already promoted for this session!!";
				}

				$query3="select ClassName,SectionName,SectionId from class,section where 
					class.ClassId=section.ClassId and class.ClassStatus='Active' and
					section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
				$check3=mysqli_query($CONNECTION,$query3);
				$ListCurrentClass=$SelectedClass="";
				while($row3=mysqli_fetch_array($check3))
				{
					$ComboCurrentClassName=$row3['ClassName'];
					$ComboCurrentSectionName=$row3['SectionName'];
					$ComboCurrentSectionId=$row3['SectionId'];
					if($CurrentSectionId==$ComboCurrentSectionId)
					$SelectedClass="selected";
					else
					$SelectedClass="";
					$ListCurrentClass.="<option value=\"$ComboCurrentSectionId\" $SelectedClass>$ComboCurrentClassName $ComboCurrentSectionName</option>";
				}

				if($Student!="" && $NextSession!="" && $NextSectionId!="")
				{
					$query22="select StudentFeeId from studentfee where AdmissionId='$AdmissionIdSelected' and SectionId='$NextSectionId' and Session='$NextSession' ";
					$check22=mysqli_query($CONNECTION,$query22);
					$count22=mysqli_num_rows($check22);
				}
				?>	
					
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Student Promotion</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding:5px;">
								<form class="form-horizontal" action="" name="StudentPromotion" id="StudentPromotion" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="CurrentSectionId">Current Class</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="CurrentSectionId" id="CurrentSectionId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListCurrentClass; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Student">Select Student</label>
												<div class="controls sel span8">   
												<select tabindex="2" name="Student[]" id="Student" class="nostyle" style="width:100%;" multiple="multiple" >
												<option></option>
												<?php if($Student!="" && $CurrentSectionId!="") echo $ListAllStudents; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="NextSession">Next Session</label>
												<input class="span8" tabindex="3" id="NextSession" type="text" name="NextSession" value="<?php echo $NextSession; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="NextSectionId">Next Class</label>
												<div class="controls sel span8">   
												<select tabindex="4" name="NextSectionId" id="NextSectionId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php if($NextSectionId!="") echo $ListAllClass; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="TransportFee">Transport</label>
												<input tabindex="5" class="styled" id="TransportFee" type="checkbox" name="TransportFee" value="Yes" <?php echo $TransportFeeChecked; ?> />
												Check only if fee is Transport Fee
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="FeeType">Distance</label>
												<div class="controls sel span8">   
												<?php
												GetCategoryValue('Distance','Distance',$Distance,'','','','',6,'');
												?>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="Action" value="StudentPromotion" readonly>
									<?php $ButtonContent="Get Fee Structure"; ActionButton($ButtonContent,7); ?>
								</form>
                            </div>
                        </div>
                    </div>	
					<div class="span8">
					<?php
					
					if($count22>0)
					echo "<div class=\"alert alert-error\">Selected student is already promoted to selected class!! $AlreadyAdmittedMessage</div>";
					elseif($StudentSelected==1 && $ValidSection==1)
					{
						$query11="select MasterEntryValue,FeeType,Amount,FeeId,Distance from fee,masterentry where
								fee.FeeType=masterentry.MasterEntryId and SectionId='$NextSectionId' and Session='$NextSession' and (Distance='' or Distance='$Distance') ";
						$check11=mysqli_query($CONNECTION,$query11);
						$count11=mysqli_num_rows($check11);
						if($count11>0)
						{
						$AdmissionIdValues=implode(",",$FinalStudent);
						$TabIndex="15";
						$i=0;
						$STR=$ListAllFee=$DistanceName="";
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
												<input tabindex=$TabIndex class=\"span8\" id=\"$FeeId\" type=\"text\" name=\"$FeeId\" value=\"$FeeAmount\" />
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
								<form class="form-horizontal" action="Action" name="PromotionConfirm" id="PromotionConfirm" method="Post">
								
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="DOP">Date of Promotion</label>
											<input tabindex="11" class="span8" id="DOP" type="text" name="DOP" readonly />
										</div>
									</div>
								</div>
								<?php echo $ListAllFee; ?>
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="Remarks">Remarks</label>
											<textarea tabindex="<?php echo $TabIndex; ?>" class="span8" id="Remarks" name="Remarks" /></textarea>
										</div>
									</div>
								</div>
									   <?php $ButtonContent="Confirm"; ActionButton($ButtonContent,$TabIndex); ?>
									   <input type="hidden" name="Action" value="PromotionConfirm" readonly>
									   <input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									   <input type="hidden" name="AdmissionId" value="<?php echo $AdmissionIdValues; ?>" readonly>
									   <input type="hidden" name="NextSectionId" value="<?php echo $NextSectionId; ?>" readonly>
									   <input type="hidden" name="FeeArray" value="<?php echo $STR; ?>" readonly>
									   <input type="hidden" name="Distance" value="<?php echo $Distance; ?>" readonly>
									   <input type="hidden" name="SectionId" value="<?php echo $CurrentSectionId; ?>" readonly>
									   <input type="hidden" name="NextSession" value="<?php echo $NextSession; ?>" readonly>
									
								</form>
                            </div>
                        </div>						
						<?php
						}
						else
						echo "<div class=\"alert alert-error\">No fee structure set for this class!!</div>";
					}
					else
					echo "<div class=\"alert alert-error\">Please select one student & next class!! $AlreadyAdmittedMessage</div>";
					?>
					</div>
                </div>
            </div>
        </div>
		
<script type="text/javascript">
	$(document).ready(function() {
	
		$("#NextSession").blur(function() {
		$("#NextSectionId").load("GetData/GetNextSessionSection/" + $("#NextSession").val());
		});
		var cSelect; 
		$(document).ready(function() { 
			cSelect = $("#Student").select2(); 
			$("#CurrentSectionId").change(function() { 
				cSelect.select2("val", ""); 
				$("#Student").load("GetData/GetCurrentClassStudent/" + $("#CurrentSectionId").val());
			}); 
		});		

		if($('#DOP').length) {
		$('#DOP').datepicker({ yearRange: "-5:+5", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		$("input, textarea, select").not('.nostyle').uniform();
		$("#NextSession").mask("9999-9999");
		$("#CurrentSectionId").select2(); 
		$('#CurrentSectionId').select2({placeholder: "Select"}); 
		$("#Student").select2();
		$('#Student').select2({placeholder: "Select"});
		$("#Distance").select2();
		$('#Distance').select2({placeholder: "Select"});
		$("#NextSectionId").select2();
		$('#NextSectionId').select2({placeholder: "Select"});
		$("#StudentPromotion").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				CurrentSectionId: {
					required: true,
				},
				NextSectionId: {
					required: true,
				},
				Student: {
					required: true,
				},
				Distance: {
					required: "#TransportFee:checked",
				}
			},
			messages: {
				CurrentSectionId: {
					required: "Please select this!!",
				},
				NextSectionId: {
					required: "Please select this!!",
				},
				Student: {
					required: "Please select this!!",
				},
				Distance: {
					required: "Please select this!!",
				}
			}   
		});
		$("#PromotionConfirm").validate({
			rules: {
				DOP: {
					required: true,
				},
				<?php echo $ValidationRules; ?>
			},
			messages: {
				DOP: {
					required: "Please select date!!",
				},
				<?php echo $ValidationMessages; ?>
			}   
		});		
	});
</script>
<?php
include("Template/Footer.php");
?>