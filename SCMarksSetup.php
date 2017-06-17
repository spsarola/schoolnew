<?php
$PageName="SCMarksSetup";
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
                <?php $BreadCumb="Co Scholastic Marks Setup"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				<?php
					$GETExamId=isset($_GET['ExamId']);
					$GETSCAreaId=isset($_GET['SCAreaId']);
					
					$query="select exam.SectionId,ExamId,ExamName,ClassName,SectionName from exam,section,class where
						ExamStatus='Active' and
						exam.Session='$CURRENTSESSION' and
						class.ClassId=section.ClassId and
						exam.SectionId=section.SectionId and
						class.ClassStatus='Active' and
						section.SectionStatus='Active' ";
					$OptionValue=$GetValue=$SelectedExam=$ListAllExam=$ListAllSCArea=$SelectedSectionId=$ValidExamId=$count3="";
					$SelectedIndicatorsName=$ListAllIndicatorsOrg=$ListAllIndicators=$StudentField=$Select2Plugin=$PrintData="";
					$check=mysqli_query($CONNECTION,$query);
					while($row=mysqli_fetch_array($check))
					{
						$ComboExamId=$row['ExamId'];
						$ComboExamName=$row['ExamName'];
						$ComboClassName=$row['ClassName'];
						$ComboSectionName=$row['SectionName'];
						$ComboSectionId=$row['SectionId'];
						$ExamNameArray[]=$row['ExamName'];
						$ExamIdArray[]=$row['ExamId'];
						
						$OptionValue="$ComboExamId-$ComboSectionId";
						$GetValue="$GETExamId-$ComboSectionId";
						if($OptionValue==$GetValue)
						{
							$SelectedExam="selected";
							$SelectedClassName=$ComboClassName;
							$SelectedSectionName=$ComboSectionName;
							$SelectedExamName=$ComboExamName;
							$SelectedSectionId=$ComboSectionId;
							$ValidExamId=1;
						}
						else
						$SelectedExam="";
						
						$ListAllExam.="<option value=\"$OptionValue\" $SelectedExam>$ComboExamName $ComboClassName $ComboSectionName</option>";
					}

					$query5="select MasterEntryValue,MasterEntryId from masterentry where MasterEntryName='GradingPoint' and MasterEntryStatus='Active' ";
					$check5=mysqli_query($CONNECTION,$query5);
					while($row5=mysqli_fetch_array($check5))
					{
						$GradingPointIdArray[]=$row5['MasterEntryId'];
						$GradingPointNameArray[]=$row5['MasterEntryValue'];
					}					
	
					$query1="select SCAreaId,SCAreaName,MasterEntryValue,SCAreaClass,GradingPoint from masterentry,scarea where
							Session='$CURRENTSESSION' and scarea.SCPartId=masterentry.MasterEntryId ";
					$check1=mysqli_query($CONNECTION,$query1);
					while($row1=mysqli_fetch_array($check1))
					{
						$ComboSCAreaId=$row1['SCAreaId'];
						$ComboSCAreaName=$row1['SCAreaName'];
						$ComboGradingPoint=$row1['GradingPoint'];
						$ComboMasterEntryValue=$row1['MasterEntryValue'];
						$ComboSCAreaClass=explode(",",$row1['SCAreaClass']);
						$SearchIndex=array_search($SelectedSectionId,$ComboSCAreaClass);
						if($SearchIndex===FALSE){}
						else
						{
							if($ComboSCAreaId==$GETSCAreaId)
							{
								$SelectedAreaName=$ComboSCAreaName;
								$SelectedSCArea="selected";
								$ValidSCAreaId=1;
								$GradingSearchIndex=array_search($ComboGradingPoint,$GradingPointIdArray);
								$GradingPointName=$GradingPointNameArray[$GradingSearchIndex];
							}
							else
							$SelectedSCArea="";
							$ListAllSCArea.="<option value=\"$ComboSCAreaId\" $SelectedSCArea>$ComboSCAreaName ($ComboMasterEntryValue)</option>";
						}
					}

				if($ValidExamId==1 && $ValidSCAreaId==1)
				{
					
					$query2="select SCIndicatorName,SCIndicatorId from scindicator where SCAreaId='$GETSCAreaId' ";
					$check2=mysqli_query($CONNECTION,$query2);
					$TotalIndicator=0;
					while($row2=mysqli_fetch_array($check2))
					{
						$SCIndicatorNameArray[]=$row2['SCIndicatorName'];
						$SCIndicatorIdArray[]=$row2['SCIndicatorId'];
						$SCIndicatorName=$row2['SCIndicatorName'];
						$SCIndicatorId=$row2['SCIndicatorId'];		
						$TotalIndicator++;
						$ListAllIndicators.="<option value=\"$SCIndicatorId\">$SCIndicatorName</option>";
						$ListAllIndicatorsOrg.="<option value=\"$SCIndicatorId\">$SCIndicatorName</option>";
					}
					
					$query4="Select Marks from scexamdetail where ExamId='$GETExamId' and SCAreaId='$GETSCAreaId' ";
					$check4=mysqli_query($CONNECTION,$query4);
					$row4=mysqli_fetch_array($check4);
					$Marks=$row4['Marks'];
					$AdmissionIdWithIndicators=explode(",",$Marks);
					foreach($AdmissionIdWithIndicators as $AdmissionIdWithIndicatorsValue)
					{
						$OnlyAdmissionIdAndIndicators=explode("-",$AdmissionIdWithIndicatorsValue);
						$MarksAdmissionIdArray[]=$OnlyAdmissionIdAndIndicators[0];
						$MarksMarksArray[]=isset($OnlyAdmissionIdAndIndicators[1]) ? $OnlyAdmissionIdAndIndicators[1] : '';
					}
					
					$query3="select admission.AdmissionId,StudentName,Mobile from studentfee,admission,registration where
						registration.RegistrationId=admission.RegistrationId and
						admission.AdmissionId=studentfee.AdmissionId and
						studentfee.SectionId='$SelectedSectionId' and
						studentfee.Session='$CURRENTSESSION' ";
					$check3=mysqli_query($CONNECTION,$query3);
					$count3=mysqli_num_rows($check3);
					$TabIndex=11;
					while($row3=mysqli_fetch_array($check3))
					{
						$SelectedIndicatorsName=array();
						$AdmissionIdArray[]=$row3['AdmissionId'];
						$StudentNameArray[]=$row3['StudentName'];
						$MobileArray[]=$row3['Mobile'];
						$ListAllIndicators=$ListAllIndicatorsOrg;
						$AdmissionId=$row3['AdmissionId'];
						$CountIndicators=0;
						if($MarksAdmissionIdArray!="")
						{
							$SearchForAdmissionId=array_search($AdmissionId,$MarksAdmissionIdArray);
							if($SearchForAdmissionId===FALSE)
							{}
							else
							{
								$AdmissionIdMarks=explode(":",$MarksMarksArray[$SearchForAdmissionId]);
								$CountIndicators=count($AdmissionIdMarks);
								$ListAllIndicators="";
								$i=0;
								foreach($SCIndicatorIdArray as $SCIndicatorIdArrayValue)
								{
									$SearchForMarksIndex=array_search($SCIndicatorIdArrayValue,$AdmissionIdMarks);
									if($SearchForMarksIndex===FALSE)
									{$SelectMarks="";}
									else
									{
										$SelectMarks="Selected";
										$IndicatorName=$SCIndicatorNameArray[$i];
										$SelectedIndicatorsName[]=$IndicatorName;
									}
									$IndicatorName=$SCIndicatorNameArray[$i];
									$i++;
									$ListAllIndicators.="<option value=\"$SCIndicatorIdArrayValue\" $SelectMarks>$IndicatorName</option>";
								}	
							}
						}
						
						$StudentName=$row3['StudentName'];
						$Mobile=$row3['Mobile'];	
						$FieldName="Admission-$AdmissionId";
						$FieldName1="Admission-$AdmissionId"."[]";
						$FieldNameArray[]=$FieldName;
						$Grade=GradeIndicator($GradingPointName,$TotalIndicator,$CountIndicators);
						$StudentField.="<div class=\"form-row row-fluid\">
							<div class=\"span12\">
								<div class=\"row-fluid\">
									<label class=\"form-label span4\" for=\"$FieldName\">$StudentName $Mobile <span class=\"help-block\"><b>$Grade</b></span></label>
									<div class=\"controls sel span8\">   
									<select tabindex=\"$TabIndex\" name=\"$FieldName1\" id=\"$FieldName\" class=\"nostyle\" style=\"width:100%;\" multiple=\"multiple\">
									$ListAllIndicators
									</select>
									</div>
								</div>
							</div>
						</div>";	
						if(isset($SelectedIndicatorsName))
						$SelectedIndicatorsName=implode(",",$SelectedIndicatorsName);
						$PrintData.="<tr><td>$StudentName ($Mobile)</td><Td>$Grade</td><td>$SelectedIndicatorsName</td></tr>";
						$TabIndex++;
						$Select2Plugin.="$(\"#$FieldName\").select2();";
					}
					$FieldNameArrayImplode=implode(",",$FieldNameArray);
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
								<form class="form-horizontal" action="ReportAction" name="SCMarksSetup" id="SCMarksSetup" method="Post">
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
												<label class="form-label span4" for="SCAreaId">Co-Scholastic Area</label>
												<div class="controls sel span8">   
												<select tabindex="2" name="SCAreaId" id="SCAreaId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php if($GETExamId!="" && $GETSCAreaId!="")
												echo $ListAllSCArea; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" readonly name="Action" value="SCMarksSetup">
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<?php $ButtonContent="Get Student"; ActionButton($ButtonContent,3); ?>
								</form>
                            </div>
						</div>
					</div>
                    <div class="span8">
						<?php if($GETExamId!="" && $GETSCAreaId!="" && $ValidExamId==1 && $ValidSCAreaId==1 && $count3>0) { ?>
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Marks Entry</span>
									<?php if($PrintData!="") { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintIndividualExamAndAreaGradeList" readonly>
										<input type="hidden" name="HeadingName" value="PrintIndividualExamAndAreaGradeHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Co-scholastic grade List"></button>
										</form>
									</div>
									<?php } ?>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="/Action" name="SCMarksSave" id="SCMarksSave" method="Post">
									<?php echo $StudentField; ?>
									<input type="hidden" readonly name="Action" value="SCMarksSave">
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="FieldNameArray" value="<?php echo $FieldNameArrayImplode; ?>" readonly>
									<input type="hidden" name="ExamId" value="<?php echo $GETExamId; ?>" readonly>
									<input type="hidden" name="SCAreaId" value="<?php echo $GETSCAreaId; ?>" readonly>
									<?php $ButtonContent="Save"; ActionButton($ButtonContent,$TabIndex); ?>
									<?php
									$Print="<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive display table table-bordered\" width=\"100%\">
										<thead>
											<tr>
												<th>Student Name</th>
												<th>Grade</th>
												<th>Indicators</th>
											</tr>
										</thead>
										<tbody>
										$PrintData
										</tbody>
									</table>";
									$_SESSION['PrintIndividualExamAndAreaGradeList']=$Print;
									$PrintHeading="Class : $SelectedClassName $SelectedSectionName <Br> Exam : $SelectedExamName <Br> Area : $SelectedAreaName";
									$_SESSION['PrintIndividualExamAndAreaGradeHeading']=$PrintHeading;
									$_SESSION['PrintCategory']="Marks";
									?>
								</form>
                            </div>
						</div>
						<?php } elseif($count3==0) { ?>
						<div class="alert alert-error">No student found in selected class!!</div>
						<?php } elseif($GETExamId=="" || $GETSCAreaId=="") { ?>
						<div class="alert alert-info">Please choose exam and its co-scolastic area!!</div>
						<?php } else { ?>
						<div class="alert alert-error">Exam Id or Co-Scolastic Area is not valid!!</div>
						<?php } ?>
					</div>	
                </div>
            </div>
        </div>
<script type="text/javascript">
	$(document).ready(function() {
	$("#ExamId").select2();
	$('#ExamId').select2({placeholder: "Select"}); 	
	<?php echo $Select2Plugin; ?>
	var cSelect; 
	$(document).ready(function() { 
		cSelect = $("#SCAreaId").select2(); 
		cSelect.select2({placeholder: "Select"}); 	
		$("#ExamId").change(function() { 
			cSelect.select2("val", ""); 
			$("#SCAreaId").load("/GetData/GetSCArea/" + $("#ExamId").val());
		}); 
	});		
	
	$("input, textarea, select").not('.nostyle').uniform();
	$("#SCMarksSetup").validate({
		ignore: null,
		ignore: 'input[type="hidden"]',
		rules: {
			ExamId: {
				required: true,
			},
			SCAreaId: {
				required: true,
			}
		},
		messages: {
			ExamId: {
				required: "Please select this!!",
			},
			SCAreaId: {
				required: "Please select this!!",
			}
		}   
	});
	});
</script>	
<?php
include("Template/Footer.php");
?>