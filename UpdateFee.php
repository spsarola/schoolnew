<?php
$PageName="UpdateFee";
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
			<?php $BreadCumb="Update Fee"; BreadCumb($BreadCumb); ?>
				
				<?php DisplayNotification(); 
				$Student=isset($_GET['Student']) ? $_GET['Student'] : '';
				$CurrentSectionId=isset($_GET['CurrentSectionId']) ? $_GET['CurrentSectionId'] : '';
				$ListAllStudents=$SelectedDistance=$StudentSelected=$Distance=$ValidationRules=$ValidationMessages=$PaidFeeIdArray=$PaidFeeSTR=$i=$STR=$ListAllFee="";
				
				if($Student!="" && $CurrentSectionId!="")
				{
					$query="Select studentfee.AdmissionNo,studentfee.Remarks,Date,studentfee.Distance,FeeStructure,admission.AdmissionId,registration.RegistrationId,StudentName,FatherName,Mobile,ClassName,SectionName,section.SectionId,class.ClassId from registration,class,section,admission,studentfee where
						studentfee.Session='$CURRENTSESSION' and
						class.ClassId=section.ClassId and
						studentfee.SectionId=section.SectionId and
						registration.RegistrationId=admission.RegistrationId and
						admission.AdmissionId=studentfee.AdmissionId and 
						studentfee.SectionId='$CurrentSectionId' and 
						Status='Studying'
						order by StudentName";
					$check=mysqli_query($CONNECTION,$query);
					while($row=mysqli_fetch_array($check))
					{
						$ComboStudentName=$row['StudentName'];
						$ComboAdmissionId=$row['AdmissionId'];
						$ComboFatherName=$row['FatherName'];
						$ComboFeeStructure=$row['FeeStructure'];
						$ComboAdmissionNo=$row['AdmissionNo'];
						$ComboMobile=$row['Mobile'];
						$ComboDistance=$row['Distance'];
						$ComboRemarks=br2nl($row['Remarks']);
						$DOA=date("d-m-Y",$row['Date']);
						if($Student==$ComboAdmissionId)
						{
							$Selected="selected";
							$StudentSelected=1;
							$SelectedFeeStructure=$ComboFeeStructure;
							$SelectedDistance=$ComboDistance;
							$SelectedDOA=$DOA;
							$Remarks=$ComboRemarks;
							$SelectedAdmissionNo=$ComboAdmissionNo;
						}
						else
						{
							$Selected="";
						}
						$ListAllStudents.="<option value=\"$ComboAdmissionId\" $Selected>$ComboStudentName $ComboFatherName $ComboMobile</option>";
					}
					$SelectedFeeStructure=explode(",",$SelectedFeeStructure);
				}

				$query3="select ClassName,SectionName,SectionId from class,section where 
					class.ClassId=section.ClassId and class.ClassStatus='Active' and
					section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
				$check3=mysqli_query($CONNECTION,$query3);
				$ListCurrentClass=$ListAllClass="";
				while($row3=mysqli_fetch_array($check3))
				{
					$ComboCurrentClassName=$row3['ClassName'];
					$ComboCurrentSectionName=$row3['SectionName'];
					$ComboCurrentSectionId=$row3['SectionId'];
					if($CurrentSectionId==$ComboCurrentSectionId)
					{
						$SelectedClass="selected";
						$ValidSection=1;
						$SelectedSectionId=$ComboCurrentSectionId;
					}
					else
					{
						$SelectedClass="";
					}
					$ListCurrentClass.="<option value=\"$ComboCurrentSectionId\" $SelectedClass>$ComboCurrentClassName $ComboCurrentSectionName</option>";
					if($SelectedClass!="selected")
					$ListAllClass.="<option value=\"$ComboCurrentSectionId-$SelectedDistance\">$ComboCurrentClassName $ComboCurrentSectionName</option>";
				}
				
				if($StudentSelected==1)
				{
					foreach($SelectedFeeStructure as $SelectedFeeStructureValue)
					{
						$SelectedFeeStructureValueArray=explode("-",$SelectedFeeStructureValue);
						$StudentFeeIdArray[]=$SelectedFeeStructureValueArray[0];
						$StudentFeeAmountArray[]=$SelectedFeeStructureValueArray[1];
						$FeeNameIndex=array_search($SelectedFeeStructureValueArray[0],$MasterEntryIdArray);
						$StudentFeeNameArray[]=$MasterEntryValueArray[$FeeNameIndex];
					}
					$query5="select SUM(feepayment.Amount) as Paid,MasterEntryValue,fee.FeeId from transaction,feepayment,fee,masterentry where
						TransactionStatus='Active' and FeePaymentStatus='Active' and 
						transaction.Token=feepayment.Token and
						feepayment.FeeType=fee.FeeId and
						fee.FeeType=masterentry.MasterEntryId and
						TransactionHead='Fee' and
						TransactionHeadId='$Student' 
						group by feepayment.FeeType ";
					$check5=mysqli_query($CONNECTION,$query5);
					while($row5=mysqli_fetch_array($check5))
					{
						$PaidFeeArray[]=$row5['Paid'];
						$PaidFeeNameArray[]=$row5['MasterEntryValue'];
						$PaidFeeIdArray[]=$row5['FeeId'];
					}
					
					$query6="select MasterEntryValue,FeeId,Distance from fee,masterentry where
						fee.FeeType=masterentry.MasterEntryId and fee.SectionId='$CurrentSectionId' and fee.Session='$CURRENTSESSION' ";
					$check6=mysqli_query($CONNECTION,$query6);
					while($row6=mysqli_fetch_array($check6))
					{
						$FeeIdArray[]=$row6['FeeId'];
						$DistanceValue=$row6['Distance'];
						if($DistanceValue!="")
						{
							$DistanceSearchIndex=array_search($DistanceValue,$MasterEntryIdArray);
							$DistanceName=$MasterEntryValueArray[$DistanceSearchIndex];
							$FeeNameArray[]=$row6['MasterEntryValue']." from $DistanceName";
						}
						else
						$FeeNameArray[]=$row6['MasterEntryValue'];
					}
										
					$pp=0;
					foreach($StudentFeeIdArray as $StudentFeeIdArrayValue)
					{
						if($PaidFeeIdArray!="")
						$SearchFeeIdIndex=array_search($StudentFeeIdArrayValue,$PaidFeeIdArray);
						else
						$SearchFeeIdIndex=FALSE;
						if($SearchFeeIdIndex===FALSE)
						{
							$FValue=$StudentFeeAmountArray[$pp];
							$PFValue=0;
						}
						else
						{
							$PFValue=$PaidFeeArray[$SearchFeeIdIndex];
							$FValue=$StudentFeeAmountArray[$pp];
						}
						$FeeNameSearchIndex=array_search($StudentFeeIdArrayValue,$FeeIdArray);
						$FName=$FeeNameArray[$FeeNameSearchIndex];
						$BFee=$FValue-$PFValue;			
						$PaidFeeSTR.="<tr><td>$FName</td><td>$FValue $CURRENCY</td><Td>$PFValue $CURRENCY</td><td>$BFee $CURRENCY</td></tr>";		
						$pp++;
					}
				}
				?>	
					
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Update Fee</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding:5px;">
								<form class="form-horizontal" action="ReportAction" name="UpdateFee" id="UpdateFee" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="CurrentSectionId">Class</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="CurrentSectionId" id="CurrentSectionId" class="nostyle" style="width:100%;">
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
												<label class="form-label span4" for="Student">Student</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="Student" id="Student" class="nostyle" style="width:100%;" >
												<option></option>
												<?php if($Student!="" && $CurrentSectionId!="") echo $ListAllStudents; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="UpdateFee" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<?php $ButtonContent="Get Fee Structure"; ActionButton($ButtonContent,6); ?>
								</form>
                            </div>
                        </div>
						
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Transport Fee</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding:5px;">
								<form class="form-horizontal" action="Action" name="UpdateTransportFee" id="UpdateTransportFee" method="Post">
								
								<input type="hidden" name="Action" value="UpdateTransportFee" readonly>
								<input type="hidden" name="AdmissionId" value="<?php echo $Student; ?>" readonly>
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="Password">Password</label>
											<input class="span8" tabindex="2" id="Password" type="password" name="Password" value="" />
										</div>
									</div>
								</div>
								<?php if($SelectedDistance!="") { ?>
								<input type="hidden" name="ActionDetail" value="Remove" readonly>
								<?php $ButtonContent="Remove Transportation Fee"; ActionButton($ButtonContent,101); ?>
								<?php } else { ?>
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
								<input type="hidden" name="ActionDetail" value="Save" readonly>
								<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
								<?php $ButtonContent="Save Transportation Fee"; ActionButton($ButtonContent,101); ?>
								<?php } ?>
								
								</form>
							</div>
						</div>
                    </div>	
					<div class="span8">
					<?php
					
					if($StudentSelected==1 && $ValidSection==1)
					{
						$query11="select MasterEntryValue,FeeType,Amount,FeeId,Distance from fee,masterentry where
								fee.FeeType=masterentry.MasterEntryId and SectionId='$CurrentSectionId' and Session='$CURRENTSESSION' and (Distance='' or Distance='$SelectedDistance') ";
						$check11=mysqli_query($CONNECTION,$query11);
						$count11=mysqli_num_rows($check11);
						while($row11=mysqli_fetch_array($check11))
						{
							$i++;
							$FeeName=$row11['MasterEntryValue'];
							$FeeAmount=$row11['Amount'];
							$FeeId=$row11['FeeId'];
							$StudentFeeSearchIndex=array_search($FeeId,$StudentFeeIdArray);
							$SavedFee=$StudentFeeAmountArray[$StudentFeeSearchIndex];
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
												<input class=\"span8\" id=\"$FeeId\" type=\"text\" name=\"$FeeId\" value=\"$SavedFee\" />
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
						}
						?>
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Update Class</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="UpdateClass" id="UpdateClass" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="NewSectionId">Class</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="NewSectionId" id="NewSectionId" class="nostyle" style="width:100%;"  onchange="showdetail(this.value,'UpdateClassDetail','UpdateClassDetail')">
												<option></option>
												<?php echo $ListAllClass; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div id="UpdateClassDetail"></div>
									<input type="hidden" name="AdmissionId" value="<?php echo $Student; ?>" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="Action" value="UpdateClass" readonly>
								</form>
							</div>
						</div>
						
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Set Fee Structure</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="UpdateFeeConfirm" id="UpdateFeeConfirm" method="Post">
								
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="DOAP">Date of Promotion</label>
											<input class="span8" id="DOAP" type="text" name="DOAP" value="<?php echo $SelectedDOA; ?>" readonly />
										</div>
									</div>
								</div>
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="AdmissionNo">Admission No</label>
											<input class="span8" id="AdmissionNo" type="text" name="AdmissionNo" value="<?php echo $SelectedAdmissionNo; ?>" />
										</div>
									</div>
								</div>
								<?php echo $ListAllFee; ?>
								<div class="form-row row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4" for="Remarks">Remarks</label>
											<textarea class="span8" id="Remarks" name="Remarks" /><?php echo $Remarks; ?></textarea>
										</div>
									</div>
								</div>
									   <?php $ButtonContent="Confirm"; ActionButton($ButtonContent,5); ?>
									   <input type="hidden" name="Action" value="UpdateFeeConfirm" readonly>
									   <input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									   <input type="hidden" name="AdmissionId" value="<?php echo $Student; ?>" readonly>
									   <input type="hidden" name="FeeArray" value="<?php echo $STR; ?>" readonly>
									   <input type="hidden" name="SectionId" value="<?php echo $CurrentSectionId; ?>" readonly>
									
								</form>
                            </div>
                        </div>	
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Fee Payment Detail</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<table class="responsive table table-bordered">
									<thead>
										<tr>
											<th>Fee Name</th>
											<th>Amount</th>
											<th>Paid</th>
											<th>Balance</th>
									</thead>
									<tbody>
										<?php echo $PaidFeeSTR; ?>
									</tbody>
								</table>
							</div>
						</div>
						<?php
					}
					else
					echo "<div class=\"alert alert-info\">Please select one valid student!!</div>";
					?>
					</div>
                </div>
            </div>
        </div>
		
<script type="text/javascript">

	var cSelect; 
	$(document).ready(function() { 
		$("#NewSectionId").select2(); 
		$('#NewSectionId').select2({placeholder: "Select"}); 
		$("#Distance").select2(); 
		$('#Distance').select2({placeholder: "Select"}); 
		$("#CurrentSectionId").select2(); 
		$('#CurrentSectionId').select2({placeholder: "Select"}); 
		cSelect = $("#Student").select2(); 
		$("#CurrentSectionId").change(function() { 
			cSelect.select2("val", ""); 
			$("#Student").load("GetData/GetCurrentClassStudent/" + $("#CurrentSectionId").val());
		}); 

		$('#Student').select2({placeholder: "Select"}); 
		if($('#DOAP').length) {
		$('#DOAP').datepicker({ yearRange: "-5:+5", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		$("input, textarea, select").not('.nostyle').uniform();
		$("#UpdateFee").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				Student: {
					required: true,
				},
				CurrentSectionId: {
					required: true,
				}
			},
			messages: {
				Student: {
					required: "Please select this!!",
				},
				CurrentSectionId: {
					required: "Please select this!!",
				}
			}   
		});	
		$("#UpdateTransportFee").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				Password: {
					required: true,
				},
				Distance: {
					required: true,
				}
			},
			messages: {
				Password: {
					required: "Please enter this!!",
				},
				Distance: {
					required: "Please select this!!",
				}
			}   
		});	
		$("#UpdateFeeConfirm").validate({
			rules: {
				DOAP: {
					required: true,
				},
				<?php echo $ValidationRules; ?>
			},
			messages: {
				DOAP: {
					required: "Please select date!!",
				},
				<?php echo $ValidationMessages; ?>
			}   
		});	
		$("#UpdateClass").validate({
			rules: {
				NewSectionId: {
					required: true,
				}
			}
		});	
	});
</script>
<?php
include("Template/Footer.php");
?>