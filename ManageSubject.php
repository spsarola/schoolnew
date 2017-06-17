<?php
$PageName="ManageSubject";
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
$SubjectName=$SubjectAbb=$Class=$SubjectButtonContent=$SubjectButtonContentSet=$SubjectAddButton=$count1="";
if($UniqueId!="" && ($Action=="UpdateSubject" || $Action=="DeleteSubject"))
{
	$query1="select * from subject where SubjectId='$UniqueId' and Session='$CURRENTSESSION' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0 && $Action=="UpdateSubject")
	{
		$row1=mysqli_fetch_array($check1);
		$SubjectName=$row1['SubjectName'];
		$SubjectAbb=$row1['SubjectAbb'];
		$Class=$row1['Class'];
		if($Class!="")
		$Class=explode(",",$Class);
		$SubjectButtonContent="Update";
		$SubjectButtonContentSet=1;
		$SubjectAddButton="Update <a href=ManageSubject><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateSubjectId=$UniqueId;
	}
}

if($SubjectButtonContentSet!=1)
{
	$SubjectButtonContent="Add";
	$SubjectAddButton="Add Subject";
}

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
		$Selected="";
		if($Class!="")
		{
			foreach($Class as $kk)
			{
				if($kk==$SelectSectionId)
				{
					$Selected="selected";
					break;
				}
			}
		}
		$ListAllClass.="<option value=\"$SelectSectionId\" $Selected>$SelectClassName $SelectSectionName</option>";
	}	

	
	$query="select SubjectName,SubjectAbb,SubjectId,Class from subject where Session='$CURRENTSESSION' and SubjectStatus='Active' order by SubjectName";
	$DATA=array();
	$QA=array();
	$result=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($result);
	while($row=mysqli_fetch_array($result))
	{
		$ListSubjectName=$row['SubjectName'];	
		$ListSubjectAbb=$row['SubjectAbb'];	
		$ListSubjectId=$row['SubjectId'];	
		$ListClass=$row['Class'];
		$ListClass=explode(",",$ListClass);
		$AllClassName="";
		foreach($ListClass as $kkk)
		{
			$pp=0;
			foreach($SectionIdArray as $kSection)
			{
				if($kSection==$kkk)
				$AllClassName.="$SectionNameArray[$pp] <br>";
				$pp++;
			}
		}
		$Edit="<a href=ManageSubject/UpdateSubject/$ListSubjectId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		$QA[]=array($ListSubjectName,$ListSubjectAbb,$AllClassName,$Edit);
	}
	$DATA['aaData']=$QA;
	$fp = fopen('plugins/Data/data1.txt', 'w');
	fwrite($fp, json_encode($DATA));
	fclose($fp);
?>

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Manage Subjects"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification();?>
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $SubjectAddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageSubject" id="ManageSubject" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SubjectName">Subject Name</label>
												<input tabindex="1" class="span8" id="SubjectName" type="text" name="SubjectName" value="<?php echo $SubjectName; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SubjectAbb">Abbreviation</label>
												<input tabindex="2" class="span8" id="SubjectAbb" type="text" name="SubjectAbb" value="<?php echo $SubjectAbb; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Class">Class</label>
												<div class="controls sel span8">   
												<select tabindex="3" name="Class[]" id="Class" class="nostyle" style="width:100%;" multiple="multiple">
												<?php echo $ListAllClass; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageSubject" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="SubjectId" value="<?php echo $UpdateSubjectId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($SubjectButtonContent,4); ?>
								</form>
                            </div>
						</div>
					</div>
					<div class="span8">
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Listing All Subjects</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="SubjectTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Subject Name</th>
											<th>Abbreviation</th>
											<th>Class</th>
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
$("#Class").select2();
$('#SubjectTable').dataTable({
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

	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageSubject").validate({
		ignore: null,
		ignore: 'input[type="hidden"]',
		rules: {
			SubjectName: {
				required: true,
			},
			SubjectAbb: {
				required: true,
			},
			Class: {
				required: true,
			}
		},
		messages: {
			SubjectName: {
				required: "Please enter Name!!",
			},
			SubjectAbb: {
				required: "Please enter Abbreviation!!",
			},
			Class: {
				required: "Please select atleast one!!",
			}
		}   
	});
});
</script>
		
<?php
include("Template/Footer.php");
?>