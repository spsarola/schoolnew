<?php
$PageName="ManageHouse";
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

<?php
$Action=$_GET['Action'];
$UniqueId=$_GET['UniqueId'];
if($UniqueId!="" && ($Action=="UpdateHouse" || $Action=="DeleteHouse" || $Action=="ViewHouse"))
{
	$query1="select * from house where HouseId='$UniqueId' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0 && $Action=="UpdateHouse")
	{
		$row1=mysqli_fetch_array($check1);
		$HouseName=$row1['HouseName'];
		$HouseButtonContent="Update";
		$HouseButtonContentSet=1;
		$HouseAddButton="Update <a href=ManageHouse><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateHouseId=$UniqueId;
	}
	elseif($count1>0 && $Action=="ViewHouse")
	{
		$row1=mysqli_fetch_array($check1);
		$ViewHouseName=$row1['HouseName'];
	}
}
if($HouseButtonContentSet!=1)
{
	$HouseButtonContent="Add";
	$HouseAddButton="Add House";
}

$query2="select HouseName,HouseId from house where HouseStatus='Active' and Session='$CURRENTSESSION' ";
$check2=mysqli_query($CONNECTION,$query2);
$DATA=array();
$QA=array();
while($row2=mysqli_fetch_array($check2))
{
	$HouseNameComboBox=$row2['HouseName'];
	$HouseIdComboxBox=$row2['HouseId'];
	$HouseNameArray[]=$row2['HouseName'];
	$HouseIdArray[]=$row2['HouseId'];
	$HouseComboBox.="<option value=\"$HouseIdComboxBox\">$HouseNameComboBox</option>";
	$Edit="<a href=ManageHouse/UpdateHouse/$HouseIdComboxBox><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
	$View="<a href=ManageHouse/ViewHouse/$HouseIdComboxBox><span class=\"icon-check tip\" title=\"Update\"></span></a>";
	$QA[]=array($HouseNameComboBox,$View,$Edit);	
}
$DATA['aaData']=$QA;
$fp = fopen('plugins/Data/data1.txt', 'w');
fwrite($fp, json_encode($DATA));
fclose($fp);

$query3="select ClassName,SectionName,SectionId from class,section where 
	class.ClassId=section.ClassId and class.ClassStatus='Active' and
	section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
$check3=mysqli_query($CONNECTION,$query3);
while($row3=mysqli_fetch_array($check3))
{
	$ComboClassName=$row3['ClassName'];
	$ComboSectionName=$row3['SectionName'];
	$ComboSectionId=$row3['SectionId'];
	$ListSection.="<option value=\"$ComboSectionId\">$ComboClassName $ComboSectionName</option>";
}
?>

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Manage House"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>

				<div class="row-fluid">
                    <div class="span5">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $HouseAddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="HouseAction" name="ManageHouse" id="ManageHouse" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="HouseName">House Name</label>
												<input tabindex="1" class="span8" id="HouseName" type="text" name="HouseName" value="<?php echo $HouseName; ?>" />
											</div>
										</div>
									</div>	
									<input type="hidden" name="Action" value="ManageHouse" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<?php if($count1>0) { ?>
									<input type="hidden" name="HouseId" value="<?php echo $UpdateHouseId; ?>" readonly>
									<?php } ?>
									<?php ActionButton($HouseButtonContent,3); ?>
								</form>
							</div>
						</div>
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Listing All House</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="HouseTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>House Name</th>
											<th><span class="icon-check tip" title="View House"></span></th>
											<th><span class="icon-edit tip" title="Update"></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					<?php if($count1>0 && $Action=="ViewHouse") { ?>
                    <div class="span7">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>House "<?php echo $ViewHouseName; ?>" Add/Update Student(s) </span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="HouseAction" name="HouseStudentAssign" id="HouseStudentAssign" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SectionId">Class</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="SectionId" id="SectionId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListSection; ?>
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
												<?php echo $ListAllStudents; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="HouseStudentAssign" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<?php if($count1>0) { ?>
									<input type="hidden" name="HouseId" value="<?php echo $UniqueId; ?>" readonly>
									<?php } ?>
									<?php $ButtonContent="Assign"; ActionButton($ButtonContent,3); ?>
								</form>
							</div>
						</div>
					</div>
					<?php } ?>
					
				</div>
				
            </div>
        </div>

<script type="text/javascript">
$(document).ready(function() {
$('#HouseTable').dataTable({
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
	$('#SectionId').select2({placeholder: "Select"}); 
	var cSelect; 
	cSelect = $("#Student").select2(); 
	$("#SectionId").change(function() { 
		cSelect.select2("val", ""); 
		$("#Student").load("GetData/GetCurrentClassStudent/" + $("#SectionId").val());
	}); 
	
	$("#ManageHouse").validate({
		rules: {
			HouseName: {
				required: true,
			}
		},
		messages: {
			HouseName: {
				required: "Please enter this!!",
			}
		}   
	});	
	$("#HouseStudentAssign").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			SectionId: {
				required: true,
			}
		},
		messages: {
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