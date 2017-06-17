<?php
$PageName="ManageExam";
$FormRequired=1;
$TableRequired=1;
$TooltipRequired=1;
$SearchRequired=1;
include("Include.php");
IsLoggedIn();

$ExamId=$_GET['ExamId'];
$query="select exam.SectionId,ExamName,SectionName,ClassName from exam,section,class where 
	ExamStatus='Active' and 
	exam.Session='$CURRENTSESSION' and 
	ExamId='$ExamId' and
	exam.SectionId=section.SectionId and
	section.ClassId=class.ClassId";
$check=mysqli_query($CONNECTION,$query);
$count=mysqli_num_rows($check);

if($count!=1)
{
	$Message="This is not a valid exam!!";
	$Type=error;
	header("Location:ManageExam");
	exit();
}

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
                <?php $BreadCumb="Exam Setup"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>

				<?php
				$row=mysqli_fetch_array($check);
				$SectionId=$row['SectionId'];
				$ClassName=$row['ClassName'];
				$SectionName=$row['SectionName'];
				$ExamName=$row['ExamName'];

				$Action=$_GET['Action'];
				$UniqueId=$_GET['UniqueId'];
				if($UniqueId!="" && ($Action=="UpdateExamSetup" || $Action=="ExamSetup"))
				{
					$query3="select * from examdetail where ExamDetailId='$UniqueId' and ExamDetailStatus='Active' ";
					$check3=mysqli_query($CONNECTION,$query3);
					$count3=mysqli_num_rows($check3);
					if($count3>0 && $Action=="UpdateExamSetup")
					{
						$row3=mysqli_fetch_array($check3);
						$SelectSubjectId=$row3['SubjectId'];
						$SelectExamActivityType=$row3['ExamActivityType'];
						$SelectExamActivityName=$row3['ExamActivityName'];
						$SelectMaximumMarks=$row3['MaximumMarks'];
						$ExamDetailButtonContent="Update";
						$ExamDetailButtonContentSet=1;
						$ExamDetailAddButton="Update <a href=ExamSetup/$ExamId><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateExamDetailId=$UniqueId;
					}
				}
				if($ExamDetailButtonContentSet!=1)
				{
					$ExamDetailButtonContent="Add";
					$ExamDetailAddButton="Add Exam Setup";
				}
				$query1="select SubjectId,SubjectName,SubjectAbb,Class from subject where Session='$CURRENTSESSION' ";
				$check1=mysqli_query($CONNECTION,$query1);
				while($row1=mysqli_fetch_array($check1))
				{
					$SubjectId=$row1['SubjectId'];
					$SubjectName=$row1['SubjectName'];
					$SubjectAbb=$row1['SubjectAbb'];
					$Class=$row1['Class'];
					$Class=explode(",",$Class);
					$SearchIndex=array_search($SectionId,$Class);
					if($SearchIndex===FALSE){}
					else
					{	
						if($SelectSubjectId==$SubjectId)
						$Selected="selected";
						else
						$Selected="";
						$ListAllSubjectForSelectedClass.="<option value=$SubjectId $Selected>$SubjectName</option>";
					}
				}

					$query2="select Locked,Marks,ExamDetailId,ExamActivityName,MasterEntryValue,ExamActivityType,MaximumMarks,SubjectName,examdetail.SubjectId from examdetail,subject,masterentry where
						examdetail.ExamId='$ExamId' and 
						examdetail.ExamDetailStatus='Active' and
						examdetail.SubjectId=subject.SubjectId and
						examdetail.ExamActivityType=masterentry.MasterEntryId 
						order by SubjectName,ExamActivityName";
					$DATA=array();
					$QA=array();
					$result2=mysqli_query($CONNECTION,$query2);
					$count2=mysqli_num_rows($result2);
					while($row2=mysqli_fetch_array($result2))
					{
						$ListExamActivityName=$row2['ExamActivityName'];	
						$ListExamActivityTypeName=$row2['MasterEntryValue'];	
						$ListExamActivityType=$row2['ExamActivityType'];	
						$ListMaximumMarks=$row2['MaximumMarks'];		
						$ListSubjectName=$row2['SubjectName'];		
						$ListSubjectId=$row2['SubjectId'];		
						$ListExamDetailId=$row2['ExamDetailId'];	
						$ListLock=$row2['Locked'];	
						$ListMarks=$row2['Marks'];	
						if($ListMarks=="")
						$ListMarks="<span class=\"badge badge-important\">Marks Not Saved</span>";
						else
						$ListMarks="<span class=\"badge badge-success\">Marks Saved</span>";
						$ListSubjectName.=" $ListMarks";
						$Edit="<a href=ExamSetup/$ExamId/UpdateExamSetup/$ListExamDetailId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
						if($ListLock==1)
						$Lock="<a href=ActionGet/UnLockExam/$ListExamDetailId $ConfirmProceed><span class=\"icomoon-icon-unlocked tip\" title=\"Unlock\"></span></a>";
						else
						$Lock="<a href=ActionGet/LockExam/$ListExamDetailId $ConfirmProceed><span class=\"icomoon-icon-locked tip\" title=\"Lock\"></span></a>";
						$Del="<a href=DeletePopUp/DeleteExamActivity/$ListExamDetailId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
						$ListSubjectName="$ListSubjectName";
						$QA[]=array($ListSubjectName,$ListExamActivityName,$ListExamActivityTypeName,$ListMaximumMarks,$Edit,$Del,$Lock);
					}
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);
				?>	
				
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo "$ExamDetailAddButton $ClassName $SectionName $ExamName"; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ExamSetup" id="ExamSetup" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SubjectId">Subject</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="SubjectId" id="SubjectId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListAllSubjectForSelectedClass; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ExamActivityType">Activity Type</label>
												<div class="controls sel span8">   
												<?php
												GetCategoryValue('ExamActivityType','ExamActivityType',$SelectExamActivityType,'','','','',2,'');
												?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ExamActivityName">Activity Name</label>
												<input tabindex="3" class="span8" id="ExamActivityName" type="text" name="ExamActivityName" value="<?php echo $SelectExamActivityName; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="MaximumMarks">Maximum Marks</label>
												<input tabindex="4" class="span8" id="MaximumMarks" type="text" name="MaximumMarks" value="<?php echo $SelectMaximumMarks; ?>" />
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ExamSetup" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<input type="hidden" name="ExamId" value="<?php echo $ExamId; ?>" readonly>
										<?php if($count3>0) { ?>
										<input type="hidden" name="ExamDetailId" value="<?php echo $UpdateExamDetailId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ExamDetailButtonContent,5); ?>
								</form>
                            </div>
						</div>
					</div>
					<div class="span8">
                        <div class="box chart gradient">
							<div class="title">
								<h4>
									<span><?php echo "Listing all activities for \"$ClassName $SectionName $ExamName\""; ?></span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="ExamDetailTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Subject</th>
											<th>Activity Name</th>
											<th>Activity Type</th>
											<th>Maximum Marks</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
											<th><span class="icomoon-icon-cancel tip" title="Delete"></span></th>
											<th><span class="icomoon-icon-locked tip" title="Lock"></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
                    </div>						
                </div>
            </div>
        </div>
		
<script type="text/javascript">
	$(document).ready(function() {
	$('#ExamDetailTable').dataTable({
		"sPaginationType": "two_button",
		"bJQueryUI": false,
		"bAutoWidth": false,
		"bLengthChange": false,  
		"bProcessing": true,
		"bDeferRender": true,
		"sAjaxSource": "plugins/Data/data1.txt",
		"fnInitComplete": function(oSettings, json) {
		  $('.dataTables_filter>label>input').attr('id', 'search');
			$('#ExamDetailTable').on('click', 'a[data-toggle=modal]', function(e) {
			lv_target = $(this).attr('data-target');
			lv_url = $(this).attr('href');
			$(lv_target).load(lv_url);
			});	
		}
	});
	$("#SubjectId").select2();
	$('#SubjectId').select2({placeholder: "Select"}); 	
	$("#ExamActivityType").select2();
	$('#ExamActivityType').select2({placeholder: "Select"}); 	
	$("input, textarea, select").not('.nostyle').uniform();
	$("#ExamSetup").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			SubjectId: {
				required: true,
			},
			ExamActivityType: {
				required: true,
			},
			MaximumMarks: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=MaximumMarks"
			},
			ExamActivityName: {
				required: true,
			}
		},
		messages: {
			SubjectId: {
				required: "Please select this!!",
			},
			ExamActivityType: {
				required: "Please select this!!",
			},
			MaximumMarks: {
				required: "Please select this!!",
				remote: jQuery.format("Should be numeric!!")
			},
			ExamActivityName: {
				required: "Please enter this!!",
			}
		}   
	});
	});
</script>
<?php
include("Template/Footer.php");
?>