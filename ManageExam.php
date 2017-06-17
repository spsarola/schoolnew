<?php
$PageName="ManageExam";
$FormRequired=1;
$TableRequired=1;
$TooltipRequired=1;
$SearchRequired=1;
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

<?php
$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
$UniqueId=isset($_GET['UniqueId']) ? $_GET['UniqueId'] : '';
$ExamName=$SectionId=$Weightage=$ExamButtonContent=$ExamButtonContentSet=$ExamAddButton=$count1="";
// For Exam table			
if($UniqueId!="" && ($Action=="UpdateExam" || $Action=="DeleteExam"))
{
	$query1="select * from exam where ExamId='$UniqueId' and Session='$CURRENTSESSION' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0 && $Action=="UpdateExam")
	{
		$row1=mysqli_fetch_array($check1);
		$ExamName=$row1['ExamName'];
		$SectionId=$row1['SectionId'];
		$Weightage=round($row1['Weightage'],2);
		$ExamButtonContent="Update";
		$ExamButtonContentSet=1;
		$ExamAddButton="Update <a href=ManageExam><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateExamId=$UniqueId;
	}
}

if($ExamButtonContentSet!=1)
{
	$ExamButtonContent="Add";
	$ExamAddButton="Add Exam";
}
	$query="select ExamId,ExamName,ClassName,SectionName,Weightage from exam,class,section where 
		exam.Session='$CURRENTSESSION' and 
		ExamStatus='Active' and
		exam.SectionId=section.SectionId and
		class.ClassId=section.ClassId 
		order by ExamName";
	$DATA=array();
	$QA=array();
	$result=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($result);
	$ListClassName="";
	while($row=mysqli_fetch_array($result))
	{
		$ListClassName=$row['ClassName'];	
		$ListSectionName=$row['SectionName'];	
		$ListClassName.=" $ListSectionName";
		$ListExamName=$row['ExamName'];		
		$ListWeightage=round($row['Weightage'],2);		
		$ListExamId=$row['ExamId'];	
		$Edit="<a href=ManageExam/UpdateExam/$ListExamId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		$ListExamName="<a href=ExamSetup/$ListExamId>$ListExamName</a>";
		$QA[]=array($ListClassName,$ListExamName,$ListWeightage,$Edit);
	}
	$DATA['aaData']=$QA;
	$fp = fopen('plugins/Data/data1.txt', 'w');
	fwrite($fp, json_encode($DATA));
	fclose($fp);
	
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
		$SectionIdArray[]="$SelectSectionId";
		$SectionNameArray[]="$SelectClassName $SelectSectionName";
		if($SectionId==$SelectSectionId)
			$Selected="selected";
		else
			$Selected="";
		$ListAllClass.="<option value=\"$SelectSectionId\" $Selected>$SelectClassName $SelectSectionName</option>";
	}	
?>	

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Manage Exams"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $ExamAddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageExam" id="ManageExam" method="Post">
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
												<label class="form-label span4" for="ExamName">Exam Name</label>
												<input tabindex="2" class="span8" id="ExamName" type="text" name="ExamName" value="<?php echo $ExamName; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Weightage">Weightage</label>
												<input tabindex="2" class="span8" id="Weightage" type="text" name="Weightage" value="<?php echo $Weightage; ?>" />
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageExam" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="ExamId" value="<?php echo $UpdateExamId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ExamButtonContent,3); ?>
								</form>
                            </div>
						</div>
					</div>
					<div class="span6">
                        <div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Listing All Exam</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix" style="padding:5px;">
								<table id="ExamTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Class</th>
											<th>Exam Name</th>
											<th>Weightage</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
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

$('#ExamTable').dataTable({
	"sPaginationType": "two_button",
	"bJQueryUI": false,
	"bAutoWidth": false,
	"bLengthChange": false,  
	"bProcessing": true,
	"bDeferRender": true,
	"sAjaxSource": "plugins/Data/data1.txt",
	"fnInitComplete": function(oSettings, json) {
	  $('.dataTables_filter>label>input').attr('id', 'search');
	}
});

	$("#SectionId").select2();
	$("input, textarea, select").not('.nostyle').uniform();
	$('#SectionId').select2({placeholder: "Select"});
	$("#ManageExam").validate({
		rules: {
			SectionId: {
				required: true,
			},
			ExamName: {
				required: true,
			},
			Weightage: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=Weightage"
			}
		},
		messages: {
			SectionId: {
				required: "Please select this!!",
			},
			ExamName: {
				required: "Please enter this!!",
			},
			Weightage: {
				required: "Please enter this!!",
				remote: jQuery.format("Should be numeric!!")
			}
		}   
	});
});
</script>
		
<?php
include("Template/Footer.php");
?>