<?php
$PageName="MarksSetup";
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
                <?php $BreadCumb="Marks Setup"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				

				<?php
				$ExamId=isset($_GET['ExamId']);
				$SubjectId=isset($_GET['SubjectId']);
				$ListAllExam=$SelectedExam=$SelectedSectionId=$ValidExamId=$ListAllSubject="";
				$query01="select exam.SectionId,ExamId,ExamName,ClassName,SectionName from exam,section,class where
					ExamStatus='Active' and
					exam.Session='$CURRENTSESSION' and
					class.ClassId=section.ClassId and
					exam.SectionId=section.SectionId and
					class.ClassStatus='Active' and
					section.SectionStatus='Active' ";
				$check01=mysqli_query($CONNECTION,$query01);
				while($row01=mysqli_fetch_array($check01))
				{
					$ComboExamId=$row01['ExamId'];
					$ComboExamName=$row01['ExamName'];
					$ComboClassName=$row01['ClassName'];
					$ComboSectionName=$row01['SectionName'];
					$ComboSectionId=$row01['SectionId'];
					$ExamNameArray[]=$row01['ExamName'];
					$ExamIdArray[]=$row01['ExamId'];
					
					$OptionValue="$ComboExamId-$ComboSectionId";
					$GetValue="$ExamId-$ComboSectionId";
					if($OptionValue==$GetValue)
					{
						$SelectedExam="selected";
						$SelectedSectionId=$ComboSectionId;
						$ValidExamId=1;
					}
					else
					$SelectedExam="";
					
					$ListAllExam.="<option value=\"$OptionValue\" $SelectedExam>$ComboExamName $ComboClassName $ComboSectionName</option>";
				}

				$query02="select SubjectName,subject.SubjectId,Class from subject,examdetail where
						Session='$CURRENTSESSION' and SubjectStatus='Active' and ExamDetailStatus='Active' and 
						subject.SubjectId=examdetail.SubjectId group by examdetail.SubjectId";
				$check02=mysqli_query($CONNECTION,$query02);
				while($row02=mysqli_fetch_array($check02))
				{
					$ComboSubjectName=$row02['SubjectName'];
					$ComboSubjectId=$row02['SubjectId'];
					$ComboClass=explode(",",$row02['Class']);
					$SearchIndex=array_search($SelectedSectionId,$ComboClass);
					if($SearchIndex===FALSE){}
					else
					{
						if($ComboSubjectId==$SubjectId)
						{
							$SelectedSubject="selected";
							$ValidSubjectId=1;
						}
						else
						$SelectedSubject="";
						$ListAllSubject.="<option value=\"$ComboSubjectId\" $SelectedSubject>$ComboSubjectName</option>";
					}
				}				
				
				$TableSubjectHeading =$PrintSubjectHeading =$TotalMaximumMarks =$PrintData=$TableData=$ValidationRules=$ValidationMessages="";
				if($ExamId!="" && $SubjectId!="")
				{
					$query="select ExamDetailId,ExamActivityName,MaximumMarks,SubjectName,Marks from examdetail,exam,subject where
						examdetail.ExamId=exam.ExamId and
						examdetail.ExamId='$ExamId' and
						examdetail.SubjectId='$SubjectId' and
						examdetail.SubjectId=subject.SubjectId and
						exam.Session='$CURRENTSESSION' and
						ExamDetailStatus='Active'
						order by ExamActivityName";
					$check=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($check);
					while($row=mysqli_fetch_array($check))
					{
						$ExamActivityName=$row['ExamActivityName'];
						$MaximumMarks=$row['MaximumMarks'];
						$SubjectName=$row['SubjectName'];
						$ExamDetailId=$row['ExamDetailId'];
						$Marks[]=$row['Marks'];
						$ExamDetailIdArray[]=$ExamDetailId;
						$ExamActivityNameArray[]=$ExamActivityName;
						$ExamActivityMMArray[]=$MaximumMarks;
						$TableSubjectHeading.="<th>$ExamActivityName<Br>MM: $MaximumMarks</th>";
						$PrintSubjectHeading.="<th>$ExamActivityName<Br>MM: $MaximumMarks</th>";
						$TotalMaximumMarks+=$MaximumMarks;
					}

					$query1="select ClassName,SectionName,ExamName,section.SectionId from exam,class,section where 
						exam.ExamId='$ExamId' and
						exam.SectionId=section.SectionId and
						class.ClassId=section.ClassId and
						exam.Session='$CURRENTSESSION' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$row1=mysqli_fetch_array($check1);
					$ClassName=$row1['ClassName'];
					$SectionName=$row1['SectionName'];
					$ExamName=$row1['ExamName'];
					$SectionId=$row1['SectionId'];
						
					$query2="select registration.RegistrationId,StudentName,Mobile from registration,admission,studentfee where
						registration.RegistrationId=admission.RegistrationId and
						admission.AdmissionId=studentfee.AdmissionId and
						studentfee.Session='$CURRENTSESSION' and
						studentfee.SectionId='$SectionId' order by StudentName";
					$check2=mysqli_query($CONNECTION,$query2);
					$TabIndex=10;
					while($row2=mysqli_fetch_array($check2))
					{
						$TotalObtainedMarks=0;
						$RegistrationId=$row2['RegistrationId'];
						$StudentName=$row2['StudentName'];
						$Mobile=$row2['Mobile'];
						$TableData.="<tr><td>$StudentName <br> $Mobile</td>";
						$PrintData.="<tr><td>$StudentName <br> $Mobile</td>";
						if($ExamDetailIdArray!="")
						{
							foreach($ExamDetailIdArray as $ExamDetailIdArrayValues)
							{
								$ArrayIndex=array_search($ExamDetailIdArrayValues,$ExamDetailIdArray);
								$MM=$ExamActivityMMArray[$ArrayIndex];
								$StudentMarks=$Marks[$ArrayIndex];
								$StudentMarks=explode(",",$StudentMarks);
								foreach($StudentMarks as $StudentMarksValues)
								{
									$StudentMarksValuesArray=explode("-",$StudentMarksValues);
									unset($FinalMarks);
									if($StudentMarksValuesArray[0]==$RegistrationId)
									{
										$FinalMarks=$StudentMarksValuesArray[1];
										break;
									}
								}
								$TotalObtainedMarks+=$FinalMarks;
								$FieldName="Field_".$RegistrationId."_".$ExamDetailIdArrayValues;
								$TableData.="<td><input type=\"number\" name=\"$FieldName\" id=\"$FieldName\" tabindex=\"$TabIndex\" class=\"span10\" value=\"$FinalMarks\"></td>";
								$PrintData.="<td>$FinalMarks</td>";
								$TabIndex++;
								$ValidationRules.="$FieldName:{max:$MM,},";
								$ValidationMessages.="$FieldName:{max:\"Max $MM\",},";
							}
						}
						$Grading=Grade($TotalMaximumMarks,$TotalObtainedMarks);
						$TableData.="<td>$TotalObtainedMarks</td><Td>$Grading</td></tr>";
						$PrintData.="<td>$TotalObtainedMarks</td><Td>$Grading</td></tr>";
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
								<form class="form-horizontal" action="ReportAction" name="MarksSetupReportAction" id="MarksSetupReportAction" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ExamId">Exam</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="ExamId" id="ExamId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListAllExam; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SubjectId">Subject</label>
												<div class="controls sel span8">   
												<select tabindex="2" name="SubjectId" id="SubjectId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php if($ExamId!="" && $SubjectId!="")
												echo $ListAllSubject; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" readonly name="Action" value="MarksSetupReportAction">
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<?php $ButtonContent="Get Student"; ActionButton($ButtonContent,3); ?>
								</form>
							</div>
						</div>
					</div>
                    <div class="span8">
					<?php if($ExamId!="" && $SubjectId!="" && $ValidExamId==1 && $ValidSubjectId==1 && $count>0) { ?>
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo "<span class=\"badge badge-success\">$ClassName $SectionName</span> $ExamName <span class=\"badge badge-important\">$SubjectName</span>"; ?></span>
									<?php if($PrintData!="") { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintIndividualExamAndSubjectMarksList" readonly>
										<input type="hidden" name="HeadingName" value="PrintIndividualExamAndSubjectMarksHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Marks List"></button>
										</form>
									</div>
									<?php } ?>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="/Action" name="MarksSetup" id="MarksSetup" method="Post">
									<table cellpadding="0" cellspacing="0" border="0" class="responsive display table table-bordered" width="100%">
										<thead>
											<tr>
												<th></th>
												<?php echo $TableSubjectHeading; ?>
												<th>MM <?php echo $TotalMaximumMarks; ?></th>
												<th>Grade</th>
											</tr>
										</thead>
										<tbody>
												<?php echo $TableData; ?>
										</tbody>
									</table>
									
									<?php
									$Print="<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive display table table-bordered\" width=\"100%\">
										<thead>
											<tr>
												<th></th>
												$PrintSubjectHeading
												<th>MM $TotalMaximumMarks</th>
												<Th>Grade</th>
											</tr>
										</thead>
										<tbody>
										$PrintData
										</tbody>
									</table>";
									
									$_SESSION['PrintIndividualExamAndSubjectMarksList']=$Print;
									$PrintHeading="Class : $ClassName $SectionName <Br> Exam : $ExamName <Br> Subject : $SubjectName";
									$_SESSION['PrintIndividualExamAndSubjectMarksHeading']=$PrintHeading;
									$_SESSION['PrintCategory']="Marks";
									?>
									
									<input type="hidden" name="Action" value="MarksSetup" readonly>
									<input type="hidden" name="ExamId" value="<?php echo $ExamId; ?>" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="SubjectId" value="<?php echo $SubjectId; ?>" readonly>
									<?php ActionButton('Save',$TabIndex); ?>
								</form>
                            </div>
						</div>
						<?php } elseif($ExamId=="" || $SubjectId=="") { ?>
						<div class="alert alert-info">Please choose exam and its subject!!</div>
						<?php } elseif($count==0) { ?>	
						<div class="alert alert-error">No activity found in selected exam and subject!!</div>
						<?php } else { ?>
						<div class="alert alert-error">Exam Id or Subject is not valid!!</div>
						<?php } ?>
					</div>					
                </div>
            </div>
        </div>
<script type="text/javascript">
	$(document).ready(function() {
	$("#ExamId").select2();
	$('#ExamId').select2({placeholder: "Select"}); 	
	var cSelect; 
	$(document).ready(function() { 
		cSelect = $("#SubjectId").select2(); 
		cSelect.select2({placeholder: "Select"}); 	
		$("#ExamId").change(function() { 
			cSelect.select2("val", ""); 
			$("#SubjectId").load("GetData/GetExamSubject/" + $("#ExamId").val());
		}); 
	});		
	$("input, textarea, select").not('.nostyle').uniform();
	$("#MarksSetup").validate({
		ignore: 'input[type="hidden"]',
		rules: {<?php echo $ValidationRules; ?>},
		messages: {<?php echo $ValidationMessages; ?>}   
	});
	$("#MarksSetupReportAction").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			ExamId: {
				required: true,
			},
			SubjectId: {
				required: true,
			}
		},
		messages: {
			ExamId: {
				required: "Please select this!!",
			},
			SubjectId: {
				required: "Please select this!!",
			}
		}   
	});
	});
</script>	
<?php
include("Template/Footer.php");
?>