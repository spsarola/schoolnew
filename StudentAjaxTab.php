<?php
include("Include.php");
$Action=$_GET['Action'];

if($Action=="StudentProfile")
{
$RegistrationId=$_GET['Id'];
$query100="select * from registration where registration.RegistrationId='$RegistrationId'";
$check100=mysqli_query($CONNECTION,$query100);
$row100=mysqli_fetch_array($check100);
$UpdateStudentName=$row100['StudentName'];
$UpdateFatherName=$row100['FatherName'];
$UpdateMotherName=$row100['MotherName'];
$SSSMID=$row100['SSSMID'];
$Family_SSSMID=$row100['Family_SSSMID'];
$Aadhar_No=$row100['Aadhar_No'];
$Bank_Account_Number=$row100['Bank_Account_Number'];
$IFSC_Code=$row100['IFSC_Code'];

$UpdateSectionId=$row100['SectionId'];
$UpdateDOR=date("d-m-Y H:i",$row100['DOR']);
$UpdateDOB=$row100['DOB'];
if($UpdateDOB!="")
$UpdateDOB=date("d-m-Y",$row100['DOB']);
$UpdateBloodGroup=$row100['BloodGroup'];
$UpdateCaste=$row100['Caste'];
$UpdateCategory=$row100['Category'];
$UpdateGender=$row100['Gender'];

$query2="select ClassName,SectionName,SectionId from class,section where 
	class.ClassId=section.ClassId and class.ClassStatus='Active' and
	section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
$check2=mysqli_query($CONNECTION,$query2);
$ListAllClass="";
while($row2=mysqli_fetch_array($check2))
{
	$SelectClassName=$row2['ClassName'];
	$SelectSectionName=$row2['SectionName'];
	$SelectSectionId=$row2['SectionId'];
	if($SelectSectionId==$UpdateSectionId)
	$Selected="selected";
	else
	$Selected="";
	$ListAllClass.="<option value=\"$SelectSectionId\" $Selected>$SelectClassName $SelectSectionName</option>";
}
					
?>
	<div class="alert alert-info">You cannot change the class once admission is done!!</div>
<form class="form-horizontal" action="Action" name="ManageStudentProfile" id="ManageStudentProfile" method="Post">
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="UpdateStudentName">Name</label>
					<input tabindex="21" class="span8" id="UpdateStudentName" type="text" name="UpdateStudentName" value="<?php echo $UpdateStudentName; ?>"/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
				<label class="form-label span4" for="StudentSectionId">Class</label> 
					<div class="controls sel span8">   
					<select tabindex="22" name="StudentSectionId" id="StudentSectionId" class="nostyle" style="width:100%;" >
					<option></option>
					<?php echo $ListAllClass; ?>
					</select>
					</div>
				</div>
			</div> 
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="UpdateFatherName">Father Name</label>
					<input tabindex="23" class="span8" id="UpdateFatherName" type="text" name="UpdateFatherName" value="<?php echo $UpdateFatherName; ?>" />
				</div>
			</div>
		</div>
            <p><?php echo $UpdateMotherName; ?></p>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="UpdateMotherName">Mother Name</label>
					<input tabindex="24" class="span8" id="UpdateMotherName" type="text" name="UpdateMotherName" value="<?php echo $UpdateMotherName; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="SSSMID">SSSMID</label>
					<input tabindex="25" class="span8" id="SSSMID" type="text" name="SSSMID" value="<?php echo $SSSMID; ?>" />
                                </div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="Family_SSSMID">Family SSSMID</label>
					<input tabindex="26" class="span8" id="Family_SSSMID" type="text" name="Family_SSSMID" value="<?php echo $Family_SSSMID; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="Aadhar_No">Aadhar Number</label>
					<input tabindex="27" class="span8" id="Aadhar_No" type="text" name="Aadhar_No" value="<?php echo $Aadhar_No; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="Bank_Account_Number">Bank A/c No</label>
					<input tabindex="28" class="span8" id="Bank_Account_Number" type="text" name="Bank_Account_Number" value="<?php echo $Bank_Account_Number; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="IFSC_Code">IFSC Code</label>
					<input tabindex="29" class="span8" id="IFSC_Code" type="text" name="IFSC_Code" value="<?php echo $IFSC_Code; ?>" />
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="UpdateDOR">Registration Date</label>
					<input tabindex="30" class="span8" id="UpdateDOR" type="text" name="UpdateDOR" value="<?php echo $UpdateDOR; ?>" readonly/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="UpdateGender">Gender</label>
					<div class="span8 controls sel">  
						<?php GetCategoryValue('Gender','UpdateGender',$UpdateGender,'','','','',26,''); ?>
					</div> 
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="UpdateDOB">Birth Date</label>
					<input tabindex="31" class="span8" id="UpdateDOB" type="text" name="UpdateDOB" value="<?php echo $UpdateDOB; ?>" readonly/>
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
				<label class="form-label span4" for="UpdateCaste">Caste</label> 
					<div class="span8 controls sel">  
						<?php GetCategoryValue('Caste','UpdateCaste',$UpdateCaste,'','','','',28,''); ?>
					</div> 
				</div>
			</div> 
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
				<label class="form-label span4" for="UpdateCategory">Category</label> 
					<div class="span8 controls sel">  
						<?php GetCategoryValue('Category','UpdateCategory',$UpdateCategory,'','','','',29,''); ?>
					</div> 
				</div>
			</div> 
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
				<label class="form-label span4" for="UpdateBloodGroup">Blood Group</label> 
					<div class="span8 controls sel">  
						<?php GetCategoryValue('BloodGroup','UpdateBloodGroup',$UpdateBloodGroup,'','','','',30,''); ?>
					</div> 
				</div>
			</div> 
		</div>
		<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
		<input type="hidden" name="Action" value="ManageStudentProfile" readonly>
		<input type="hidden" name="RegistrationId" value="<?php echo $RegistrationId; ?>" readonly>
		<?php $ButtonContent="Save"; ActionButton($ButtonContent,31); ?>
	</div>
</form>

<script type="text/javascript">
$(document).ready(function() {
if($('#UpdateDOR').length) {
	$("#UpdateDOR").datetimepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
}
if($('#UpdateDOB').length) {
	$("#UpdateDOB").datepicker({ yearRange: "-50:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
}
	$("input, textarea, select").not('.nostyle').uniform();
	$("#StudentSectionId").select2();
	$('#StudentSectionId').select2({placeholder: "Select"});
	$("#UpdateCategory").select2();
	$('#UpdateCategory').select2({placeholder: "Select"});
	$("#UpdateCaste").select2();
	$('#UpdateCaste').select2({placeholder: "Select"});
	$("#UpdateBloodGroup").select2();
	$('#UpdateBloodGroup').select2({placeholder: "Select"});
	$("#UpdateGender").select2();
	$('#UpdateGender').select2({placeholder: "Select"});
	$("#ManageStudentProfile").validate({
		rules: {
			UpdateStudentName: {
				required: true,
			},
			UpdateFatherName: {
				required: true,
			},
			UpdateMotherName: {
				required: true,
			},
			SSSMID: {
				required: true,
			},
			Family_SSSMID: {
				required: true,
			},
			Aadhar_No: {
				required: true,
			},
			Bank_Account_Number: {
				required: true,
			},
			IFSC_Code: {
				required: true,
			}
		},
		messages: {
			UpdateStudentName: {
				required: "Please enter Name!!",
			},
			UpdateFatherName: {
				required: "Please enter Father Name!!",
			},
			UpdateMotherName: {
				required: "Please enter Mother Name!!",
			},
			SSSMID: {
				required: "Please enter SSSMID!!",
			},
			Family_SSSMID: {
				required: "Please enter Family SSSMID!!",
			},
			Aadhar_No: {
				required: "Please enter Aadhar Number!!",
			},
			Bank_Account_Number: {
				required: "Please enter Account Number!!",
			},
			IFSC_Code: {
				required: "Please enter IFSC Code!!",
			}
		}   
	});
});
</script>
<?php
}
elseif($Action=="StudentContact")
{
$RegistrationId=$_GET['Id'];
$query100="select * from registration where registration.RegistrationId='$RegistrationId' and registration.Session='$CURRENTSESSION' ";
$check100=mysqli_query($CONNECTION,$query100);
$row100=mysqli_fetch_array($check100);
$Mobile=$row100['Mobile'];
$Landline=$row100['Landline'];
$AlternateMobile=$row100['AlternateMobile'];
$FatherMobile=$row100['FatherMobile'];
$MotherMobile=$row100['MotherMobile'];
$PresentAddress=br2nl($row100['PresentAddress']);
$PermanentAddress=br2nl($row100['PermanentAddress']);
?>
<form class="form-horizontal" action="Action" name="ManageStudentContact" id="ManageStudentContact" method="Post">
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Mobile</label>
					<input tabindex="111" class="span8" id="Mobile" type="text" name="Mobile" value="<?php echo $Mobile; ?>"/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Landline</label>
					<input tabindex="112" class="span8" id="Landline" type="text" name="Landline" value="<?php echo $Landline; ?>"/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Alternate Mobile</label>
					<input tabindex="113" class="span8" id="AlternateMobile" type="text" name="AlternateMobile" value="<?php echo $AlternateMobile; ?>"/>
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Father Mobile</label>
					<input tabindex="114"class="span8" id="FatherMobile" type="text" name="FatherMobile" value="<?php echo $FatherMobile; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Mother Mobile</label>
					<input tabindex="115" class="span8" id="MotherMobile" type="text" name="MotherMobile" value="<?php echo $MotherMobile; ?>" />
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Present Address</label>
					<div class="controls-textarea span8">
					<textarea tabindex="116" class="span12" name="PresentAddress" id="PresentAddress"><?php echo $PresentAddress; ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Permanent Address</label>
					<div class="controls-textarea span8">
					<textarea tabindex="117" class="span12" name="PermanentAddress" id="PermanentAddress"><?php echo $PermanentAddress; ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
		<input type="hidden" name="Action" value="ManageStudentContact" readonly>
		<input type="hidden" name="RegistrationId" value="<?php echo $RegistrationId; ?>" readonly>
		<?php $ButtonContent="Save"; ActionButton($ButtonContent,118); ?>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {

	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageStudentContact").validate({
		rules: {
			Mobile: {
				required: true,
				remote: "RemoteValidation?Action=MobileValidation&Id=Mobile"
			},
			AlternateMobile: {
				remote: "RemoteValidation?Action=MobileValidation&Id=AlternateMobile"
			},
			Landline: {
				remote: "RemoteValidation?Action=LandlineValidation&Id=Landline"
			},
			FatherMobile: {
				remote: "RemoteValidation?Action=MobileValidation&Id=FatherMobile"
			},
			MotherMobile: {
				remote: "RemoteValidation?Action=MobileValidation&Id=MotherMobile"
			}
		},
		messages: {
			Mobile: {
				required: "Please enter Mobile!!",
				remote: jQuery.format("Mobile should be <?php echo $MOBILENUMBERDIGIT; ?> digit Numeric!!"),
			},
			AlternateMobile: {
				remote: jQuery.format("Mobile should be <?php echo $MOBILENUMBERDIGIT; ?> digit Numeric!!"),
			},
			Landline: {
				remote: jQuery.format("Landline should be <?php echo $LANDLINENUMBERDIGIT; ?> digit Numeric!!"),
			},
			FatherMobile: {
				remote: jQuery.format("Mobile should be <?php echo $MOBILENUMBERDIGIT; ?> digit Numeric!!"),
			},
			MotherMobile: {
				remote: jQuery.format("Mobile should be <?php echo $MOBILENUMBERDIGIT; ?> digit Numeric!!"),
			}
		}   
	});
});
</script>
<?php
}
elseif($Action=="ParentsContact")
{
$RegistrationId=$_GET['Id'];
$query100="select * from registration where registration.RegistrationId='$RegistrationId' and Session='$CURRENTSESSION'";
$check100=mysqli_query($CONNECTION,$query100);
$row100=mysqli_fetch_array($check100);
$FatherDateOfBirth=$row100['FatherDateOfBirth'];
$FatherEmail=$row100['FatherEmail'];
$FatherQualification=$row100['FatherQualification'];
$FatherOccupation=$row100['FatherOccupation'];
$FatherDesignation=$row100['FatherDesignation'];
$FatherOrganization=$row100['FatherOrganization'];
$MotherDateOfBirth=$row100['MotherDateOfBirth'];
$MotherEmail=$row100['MotherEmail'];
$MotherQualification=$row100['MotherQualification'];
$MotherOccupation=$row100['MotherOccupation'];
$MotherDesignation=$row100['MotherDesignation'];
$MotherOrganization=$row100['MotherOrganization'];
?>
<form class="form-horizontal" action="Action" name="ManageParentsContact" id="ManageParentsContact" method="Post">
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Father Date Of Birth</label>
					<input tabindex="131" class="span8" id="FatherDateOfBirth" type="text" name="FatherDateOfBirth" value="<?php echo $FatherDateOfBirth; ?>" readonly />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Father Email</label>
					<input tabindex="132" class="span8" id="FatherEmail" type="email" name="FatherEmail" value="<?php echo $FatherEmail; ?>"/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Father Qualification</label>
					<input tabindex="133" class="span8" id="FatherQualification" type="text" name="FatherQualification" value="<?php echo $FatherQualification; ?>"/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Father Occupation</label>
					<input tabindex="134" class="span8" id="FatherOccupation" type="text" name="FatherOccupation" value="<?php echo $FatherOccupation; ?>"/>
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Mother Date Of Birth</label>
					<input tabindex="135" class="span8" id="MotherDateOfBirth" type="text" name="MotherDateOfBirth" value="<?php echo $MotherDateOfBirth; ?>" readonly />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Mother Email</label>
					<input tabindex="136" class="span8" id="MotherEmail" type="email" name="MotherEmail" value="<?php echo $MotherEmail; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Mother Qualification</label>
					<input tabindex="137" class="span8" id="MotherQualification" type="text" name="MotherQualification" value="<?php echo $MotherQualification; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Mother Occupation</label>
					<input tabindex="138" class="span8" id="MotherOccupation" type="text" name="MotherOccupation" value="<?php echo $MotherOccupation; ?>" />
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Father Designation</label>
					<input tabindex="139" class="span8" id="FatherDesignation" type="text" name="FatherDesignation" value="<?php echo $FatherDesignation; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Father Oragnization</label>
					<input tabindex="140" class="span8" id="FatherOrganization" type="text" name="FatherOrganization" value="<?php echo $FatherOrganization; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Mother Designation</label>
					<input tabindex="141" class="span8" id="MotherDesignation" type="text" name="MotherDesignation" value="<?php echo $MotherDesignation; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Mother Oragnization</label>
					<input tabindex="142" class="span8" id="MotherOrganization" type="text" name="MotherOrganization" value="<?php echo $MotherOrganization; ?>" />
				</div>
			</div>
		</div>
		<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
		<input type="hidden" name="Action" value="ManageParentsContact" readonly>
		<input type="hidden" name="RegistrationId" value="<?php echo $RegistrationId; ?>" readonly>
		<?php $ButtonContent="Save"; ActionButton($ButtonContent,143); ?>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
if($('#FatherDateOfBirth').length) {
	$("#FatherDateOfBirth").datepicker({ yearRange: "-80:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
}
if($('#MotherDateOfBirth').length) {
	$("#MotherDateOfBirth").datepicker({ yearRange: "-80:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
}
$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageParentsContact").validate();
});
</script>
<?php
}
elseif($Action=="Qualification")
{
	$StudentId=$_GET['Id'];
	$query1="Select RegistrationId from registration where RegistrationId='$StudentId' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	
	$check=mysqli_query($CONNECTION,"select * from qualification where UniqueId='$StudentId' and Type='Student' ");
	$count=mysqli_num_rows($check);
	if($count>0 && $count1==1)
	{
		echo "<table class=\"responsive table table-bordered\">
			<thead>
				 <tr>
					<th>Board/University</th>
					<th>Class</th>
					<th>Year</th>
					<th>Marks</th>
					<th>Remarks</th>
					<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>
				</tr>
			</thead>
			<tbody>";
			$ActionConfirmMessage="Are you sure want to delete?";
			$ActionConfirm=ActionConfirm($ActionConfirmMessage);
			while($row=mysqli_fetch_array($check))
			{
				$BoardUniversity=$row['BoardUniversity'];
				$Class=$row['Class'];
				$Year=$row['Year'];
				$Marks=$row['Marks'];
				$Remarks=$row['Remarks'];
				$QualificationId=$row['QualificationId'];
				$Delete="<a href=ActionGet/DeleteStudentQualification/$QualificationId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\" $ActionConfirm></span></a>";
				echo "<tr>
				<Td>$BoardUniversity</td>
				<Td>$Class</td>
				<Td>$Year</td>
				<Td>$Marks</td>
				<td>$Remarks</td>
				<td>$Delete</td>
				</tr>";
			}
			echo "</tbody>
			</table>";
	}
	else
	echo "<div class=\"alert alert-error\">No Qualification added!!</div>";
?>
<form class="form-horizontal" action="Action" name="ManageQualification" id="ManageQualification" method="Post">
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Board/University</label>
					<input tabindex="151" class="span8" id="BoardUniversity" type="text" name="BoardUniversity"/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Class</label>
					<input tabindex="152" class="span8" id="Class" type="text" name="Class"/>
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Year</label>
					<input tabindex="153" class="span8" id="Year" type="text" name="Year" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Marks</label>
					<input tabindex="155" class="span8" id="Marks" type="text" name="Marks"  />
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Remarks</label>
					<div class="controls-textarea span8">
					<textarea tabindex="155" id="Remarks" name="Remarks" class="span12"></textarea>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
		<input type="hidden" name="Action" value="ManageQualification" readonly>
		<input type="hidden" name="UniqueId" value="<?php echo $StudentId; ?>" readonly>
		<input type="hidden" name="QualificationType" value="Student" readonly>
		<?php $ButtonContent="Save"; ActionButton($ButtonContent,156); ?>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageQualification").validate({
		rules: {
			BoardUniversity: {
				required: true,
			},
			Class: {
				required: true,
			},
			Year: {
				required: true,
			},
			Marks: {
				required: true,
			}
		},
		messages: {
			BoardUniversity: {
				required: "Please enter this field!!",
			},
			Class: {
				required: "Please enter this field!!",
			},
			Year: {
				required: "Please enter this field!!",
			},
			Marks: {
				required: "Please enter this field!!",
			}
		}   
	});
});
</script>
<?php
}
elseif($Action=="SiblingInformation")
{
	$StudentId=$_GET['Id'];
	$query1="Select RegistrationId from registration where RegistrationId='$StudentId' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	
	$check=mysqli_query($CONNECTION,"select * from sibling where RegistrationId='$StudentId' ");
	$count=mysqli_num_rows($check);
	if($count>0 && $count1==1)
	{
		echo "<table class=\"responsive table table-bordered\">
			<thead>
				 <tr>
					<th>Name</th>
					<th>DOB</th>
					<th>Class</th>
					<th>School</th>
					<th>Remarks</th>
					<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>
				</tr>
			</thead>
			<tbody>";
			$ActionConfirmMessage="Are you sure want to delete?";
			$ActionConfirm=ActionConfirm($ActionConfirmMessage);
			while($row=mysqli_fetch_array($check))
			{
				$SiblingId=$row['SiblingId'];
				$SName=$row['SName'];
				$SDOB=$row['SDOB'];
				if($SDOB!="")
				$SDOB=date("d M Y",$SDOB);
				$SClass=$row['SClass'];
				$SSchool=$row['SSchool'];
				$SRemarks=$row['SRemarks'];
				$Delete="<a href=ActionGet/DeleteSiblingInformation/$SiblingId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\" $ActionConfirm></span></a>";
				echo "<tr>
				<Td>$SName</td>
				<Td>$SDOB</td>
				<Td>$SClass</td>
				<Td>$SSchool</td>
				<td>$SRemarks</td>
				<td>$Delete</td>
				</tr>";
			}
			echo "</tbody>
			</table>";
	}
	else
	echo "<div class=\"alert alert-error\">No Sibling added!!</div>";
?>
<form class="form-horizontal" action="Action" name="ManageSiblingInformation" id="ManageSiblingInformation" method="Post">
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="SName">Name</label>
					<input tabindex="161" class="span8" id="SName" type="text" name="SName"/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="SDOB">DOB</label>
					<input tabindex="162" class="span8" id="SDOB" type="text" name="SDOB" readonly />
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="SClass">Class</label>
					<input tabindex="163" class="span8" id="SClass" type="text" name="SClass" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="SSchool">School</label>
					<input tabindex="165" class="span8" id="SSchool" type="text" name="SSchool"  />
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="SRemarks">Remarks</label>
					<div class="controls-textarea span8">
					<textarea tabindex="165" id="SRemarks" name="SRemarks" class="span12"></textarea>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
		<input type="hidden" name="Action" value="ManageSiblingInformation" readonly>
		<input type="hidden" name="RegistrationId" value="<?php echo $StudentId; ?>" readonly>
		<?php $ButtonContent="Save"; ActionButton($ButtonContent,166); ?>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
if($('#SDOB').length) {
	$("#SDOB").datepicker({ yearRange: "-20:+0", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
}
	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageSiblingInformation").validate({
		rules: {
			SName: {
				required: true,
			}
		},
		messages: {
			SName: {
				required: "Please enter this field!!",
			}
		}   
	});
});
</script>
<?php
}
elseif($Action=="Photo")
{
	$StudentId=$_GET['Id'];
?>
<form class="form-horizontal" action="Action" name="ManagePhotos" id="ManagePhotos" method="Post" enctype="multipart/form-data">
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="Title">Title</label>
					<input tabindex="161" class="span8" id="Title" type="text" name="Title"/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="Resolution">Document</label>
					<div class="span8 controls sel">  
						<?php GetCategoryValue('StudentsDocuments','Document','','','','','',162,''); ?>
					</div> 
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="Resolution">Resolution</label>
					<div class="span8 controls sel">  
						<?php GetCategoryValue('Resolution','Resolution','','','','','',163,''); ?>
					</div> 
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="file">Select Image</label>
					<div class="span8 controls sel"> 
					<input type="file" name="file" id="file" tabindex="164" />
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
		<input type="hidden" name="Action" value="ManagePhotos" readonly>
		<input type="hidden" name="UniqueId" value="<?php echo $StudentId; ?>" readonly>
		<input type="hidden" name="Detail" value="StudentDocuments" readonly>
		<?php $ButtonContent="Save"; ActionButton($ButtonContent,166); ?>
	</div>
	<div class="span8">
	<?php
	$query="select PhotoId,Path,Title,MasterEntryValue from photos,masterentry where photos.Document=masterentry.MasterEntryId and UniqueId='$StudentId' and Detail='StudentDocuments' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		while($row=mysqli_fetch_array($check))
		{
			$Path=$row['Path'];
			$PhotoId=$row['PhotoId'];
			$Title=$row['Title'];
			$Document=$row['MasterEntryValue'];
			$Path="$PHOTOPATH/thumbnail-$Path";
			if (!file_exists($Path) && $Path!="")
			echo "File Not found";
			else
			echo "<div class=\"span4\"><center><img src=\"$Path\" style=\"border:1px solid #cdcdcd\"> <a href=\"/ActionGet/DeleteDocument/$PhotoId\" $ConfirmProceed><span class=\"icomoon-icon-cancel\"></a></span> <br><b>$Document</b> <br>$Title</center></div>";
		}
	}
	else
	echo "<div class=\"alert alert-error\">No documents uploaded!!</div>";
	?>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
	$("input, textarea, select").not('.nostyle').uniform();
	$("#Resolution").select2();
	$('#Resolution').select2({placeholder: "Select"});
	$("#Document").select2();
	$('#Document').select2({placeholder: "Select"});
	$("#ManagePhotos").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			Title: {
				required: true,
			},
			Resolution: {
				required: true,
			},
			Document: {
				required: true,
			},
			file: {
				required: true,
			}
		},
		messages: {
			Title: {
				required: "Please enter this field!!",
			},
			Resolution: {
				required: "Please select this field!!",
			},
			Document: {
				required: "Please select this field!!",
			},
			file: {
				required: "Please select this field!!",
			}
		}   
	});
});
</script>
<?php
}
elseif($Action=="Termination")
{
$RegistrationId=$_GET['Id'];
$DateOfTermination=$TerminationRemarks=$Status="";
$query="Select Status,DateOfTermination,TerminationRemarks from registration where RegistrationId='$RegistrationId' ";
$check=mysqli_query($CONNECTION,$query);
$row=mysqli_fetch_array($check);
$Status=$row['Status'];
$DateOfTermination=$row['DateOfTermination'];
$TerminationRemarks=$row['TerminationRemarks'];
if($Status=="Studying")
{
	$query1="select StudentFeeId,StudentFeeStatus,studentfee.Session from registration,admission,studentfee where
		registration.RegistrationId=admission.RegistrationId and
		admission.AdmissionId=studentfee.AdmissionId and registration.RegistrationId='$RegistrationId'";
	$check1=mysqli_query($CONNECTION,$query1);
	$ListAllSession="";
	while($row1=mysqli_fetch_array($check1))
	{
		$ListSession=$row1['Session'];
		$ListStudentFeeId=$row1['StudentFeeId'];
		$ListAllSession.="<option value=$ListStudentFeeId>$ListSession</option>";
	}
?>
<form class="form-horizontal" action="Action" name="StudentTermination" id="StudentTermination" method="Post">
	<div class="form-row row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<label class="form-label span4" for="normal">Date of Termination</label>
				<input class="span8" tabindex="1901" id="DateOfTermination" type="text" name="DateOfTermination" value="<?php echo $DateOfTermination; ?>" readonly />
			</div>
		</div>
	</div>
	<div class="form-row row-fluid">
		<div class="span12">
			<div class="row-fluid">
			<label class="form-label span4" for="TerminationReason">Reason</label> 
				<div class="span8 controls sel">  
					<?php GetCategoryValue('TerminationReason','TerminationReason','','','','','',1901,''); ?>
				</div> 
			</div>
		</div> 
	</div>
	<div class="form-row row-fluid">
		<div class="span12">
			<div class="row-fluid">
			<label class="form-label span4" for="normal">Session</label> 
				<div class="span8 controls sel">
					<select tabindex="1902" class="nostyle" name="StudentFeeId" id="StudentFeeId" style="width:100%;">
					<option></option>
					<?php echo $ListAllSession; ?>
					</select>
				</div> 
			</div>
		</div> 
	</div>
	<div class="form-row row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<label class="form-label span4" for="normal">Termination Remarks</label>
				<div class="controls-textarea span8">
				<textarea tabindex="1903" class="span12" name="Remarks" id="Remarks"><?php echo $TerminationRemarks; ?></textarea>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
	<input type="hidden" name="Action" value="StudentTermination" readonly>
	<input type="hidden" name="RegistrationId" value="<?php echo $RegistrationId; ?>" readonly>
	<?php $ButtonContent="Save"; ActionButton($ButtonContent,1904); ?>
</form>
<?php
}
elseif($Status=="Terminated")
{
$DateOfTermination=date("d M Y",$DateOfTermination);
?>
<div class="alert alert-error">This student is already terminated on <?php echo $DateOfTermination; ?> with following remarks. <BR><Br>
<?php echo $TerminationRemarks; ?></div>
<?php
}
elseif($Status="NotAdmit")
{
?>
<div class="alert alert-error">This student cannot be terminated because he/she is not yet taken the admission!!</div>
<?php
}
?>
<script type="text/javascript">
$(document).ready(function() {

if($('#DateOfTermination').length) {
	$("#DateOfTermination").datepicker({ yearRange: "-80:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
}
	$("#StudentFeeId").select2();
	$('#StudentFeeId').select2({placeholder: "Select"});
	$("#TerminationReason").select2();
	$('#TerminationReason').select2({placeholder: "Select"});

	$("input, textarea, select").not('.nostyle').uniform();
	$("#StudentTermination").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			DateOfTermination: {
				required: true,
			},
			StudentFeeId: {
				required: true,
			},
			TerminationReason: {
				required: true,
			}
		}
	});
});
</script>
<?php
}
else
{}
?>