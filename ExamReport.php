<?php
$PageName="ExamReport";
$FormRequired=1;
$TableRequired=1;
$TooltipRequired=1;
$SearchRequired=1;
include("Include.php");
include("Grading.php");
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
                <?php $BreadCumb="Exam Report"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>

				<?php
				$SectionId=$_POST['SectionId'];
				$query3="select ClassName,SectionName,SectionId from class,section where 
					class.ClassId=section.ClassId and class.ClassStatus='Active' and
					section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
				$check3=mysqli_query($CONNECTION,$query3);
				while($row3=mysqli_fetch_array($check3))
				{
					$ComboCurrentClassName=$row3['ClassName'];
					$ComboCurrentSectionName=$row3['SectionName'];
					$ComboCurrentSectionId=$row3['SectionId'];
					if($SectionId==$ComboCurrentSectionId)
					{
						$SelectedClass="selected";
						$ValidSectionId=1;
					}
					else
					$SelectedClass="";
					$ListAllClass.="<option value=\"$ComboCurrentSectionId\" $SelectedClass>$ComboCurrentClassName $ComboCurrentSectionName</option>";
				}
				
				if($ValidSectionId==1)
				{
					$query="select StudentName,FatherName,Mobile,admission.AdmissionId from admission,studentfee,registration where
						registration.RegistrationId=admission.RegistrationId and
						admission.AdmissionId=studentfee.AdmissionId and
						studentfee.SectionId='$SectionId' and
						studentfee.Session='$CURRENTSESSION' and
						registration.Status='Studying' ";
					$check=mysqli_query($CONNECTION,$query);
					while($row=mysqli_fetch_array($check))
					{
						$ComboStudentName=$row['StudentName'];
						$ComboFatherName=$row['FatherName'];
						$ComboMobile=$row['Mobile'];
						$ComboAdmissionId=$row['AdmissionId'];
						if($ComboAdmissionId==$_POST['StudentId'])
						{
							$SelectedStudentName=$row['StudentName'];
							$SelectedFatherName=$row['FatherName'];
							$SelectedMobile=$row['Mobile'];
							$SelectedAdmissionId=$row['AdmissionId'];
							$ValidAdmissionId=1;
							$SelectedStudent="Selected";
						}
						else
						$SelectedStudent="";
						$ListAllStudent.="<option value=\"$ComboAdmissionId\" $SelectedStudent>$ComboStudentName $ComboFatherName $ComboMobile</option>";
					}
				}
				?>				
				
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Select Option</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="ReportAction" name="ExamReport" id="ExamReport" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SectionId">Class</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="SectionId" id="SectionId" class="nostyle" style="width:100%;" >
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
												<label class="form-label span4" for="StudentId">Student</label>
												<div class="controls sel span8">   
												<select tabindex="2" name="StudentId" id="StudentId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php if($SectionId!="")
												echo $ListAllStudent; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" readonly name="Action" value="ExamReport">
									<?php $ButtonContent="Get Report"; ActionButton($ButtonContent,3); ?>
								</form>
							</div>
						</div>
					</div>			
                </div>
            </div>
        </div>
<script type="text/javascript">
	var cSelect2; 
	var cSelect; 
	$(document).ready(function() {
	$("#SectionId").select2();
	$('#SectionId').select2({placeholder: "Select"}); 	
	
	cSelect = $("#StudentId").select2(); 
	cSelect.select2({placeholder: "Select"}); 	
	cSelect2 = $("#ExamId").select2(); 
	cSelect2.select2({placeholder: "Select"}); 	
	$("#SectionId").change(function() { 
		cSelect2.select2("val", ""); 
		$("#ExamId").load("GetData/GetClassExam/" + $("#SectionId").val());
		cSelect.select2("val", ""); 
		$("#StudentId").load("GetData/GetCurrentClassStudent/" + $("#SectionId").val());
	}); 
	
	$("input, textarea, select").not('.nostyle').uniform();
	$("#ExamReport").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			StudentId: {
				required: true,
			},
			SectionId: {
				required: true,
			}
		},
		messages: {
			StudentId: {
				required: "Please select this!!",
			},
			SectionId: {
				required: "Please select this!!",
			}
		}   
	});
	});
</script>	
<?php
include("Template/Footer.php");
?>