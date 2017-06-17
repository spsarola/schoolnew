<?php
$PageName="ManageClass";
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
$ClassButtonContent=$ClassButtonContentSet=$ClassAddButton=$ClassName=$count2=$SectionClassId=$SectionName=$SectionId=$SectionButtonContent=$SectionButtonContentSet=$SectionAddButton=$count1="";
if($UniqueId!="" && ($Action=="UpdateClass" || $Action=="DeleteClass"))
{
	$query1="select * from class where ClassId='$UniqueId' and Session='$CURRENTSESSION' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0 && $Action=="UpdateClass")
	{
		$row1=mysqli_fetch_array($check1);
		$ClassName=$row1['ClassName'];
		$ClassButtonContent="Update";
		$ClassButtonContentSet=1;
		$ClassAddButton="Update <a href=ManageClass><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateClassId=$UniqueId;
	}
}
elseif($UniqueId!="" && ($Action=="UpdateSection" || $Action=="DeleteSection"))
{
	$query2="select section.ClassId,SectionName,SectionId from section,class where class.ClassId=section.ClassId and SectionId='$UniqueId' and class.Session='$CURRENTSESSION'";
	$check2=mysqli_query($CONNECTION,$query2);
	$count2=mysqli_num_rows($check2);
	if($count2>0 && $Action=="UpdateSection")
	{
		$row2=mysqli_fetch_array($check2);
		$SectionClassId=$row2['ClassId'];
		$SectionName=$row2['SectionName'];
		$SectionId=$row2['SectionId'];
		$SectionButtonContent="Update";
		$SectionButtonContentSet=1;
		$SectionAddButton="Update <a href=ManageClass><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateSectionId=$UniqueId;
	}
}

if($ClassButtonContentSet!=1)
{
	$ClassButtonContent="Add";
	$ClassAddButton="Add Class";
}
if($SectionButtonContentSet!=1)
{
	$SectionButtonContent="Add";
	$SectionAddButton="Add Section";
}

	$query="select ClassName,ClassId from class where Session='$CURRENTSESSION' and ClassStatus='Active' order by ClassName";
	$DATA=array();
	$QA=array();
	$result=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($result);
	$ListAllClassId="";
	while($row=mysqli_fetch_array($result))
	{
		$ListClassName=$row['ClassName'];	
		$ListClassId=$row['ClassId'];	
		if($SectionClassId==$ListClassId)
		$Selected="Selected";
		else
		$Selected="";
		$ListAllClassId.="<option value=\"$ListClassId\" $Selected>$ListClassName</option>";
		$Edit="<a href=ManageClass/UpdateClass/$ListClassId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		$QA[]=array($ListClassName,$Edit);
	}
	$DATA['aaData']=$QA;
	$fp = fopen('plugins/Data/data1.txt', 'w');
	fwrite($fp, json_encode($DATA));
	fclose($fp);
	
	$query0="select ClassName,SectionName,SectionId from class,section where class.ClassId=section.ClassId and Session='$CURRENTSESSION' and SectionStatus='Active' order by ClassName";
	$DATA0=array();
	$QA0=array();
	$result0=mysqli_query($CONNECTION,$query0);
	$count0=mysqli_num_rows($result0);
	while($row0=mysqli_fetch_array($result0))
	{
		$ListSectionName=$row0['SectionName'];	
		$ListSectionClassName=$row0['ClassName'];	
		$ListSectionId=$row0['SectionId'];	
		$Edit0="<a href=ManageClass/UpdateSection/$ListSectionId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		$QA0[]=array($ListSectionClassName,$ListSectionName,$Edit0);
	}
	$DATA0['aaData']=$QA0;
	$fp = fopen('plugins/Data/data2.txt', 'w');
	fwrite($fp, json_encode($DATA0));
	fclose($fp);	
?>	

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Manage Class & Section"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $ClassAddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageClass" id="ManageClass" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ClassName">Class Name</label>
												<input tabindex="1" class="span8" id="ClassName" type="text" name="ClassName" value="<?php echo $ClassName; ?>" />
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageClass" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="ClassId" value="<?php echo $UpdateClassId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ClassButtonContent,2); ?>
								</form>
                            </div>
						</div>
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Listing All Class</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="ClassTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Class Name</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
                    </div>		

					<div class="span6">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $SectionAddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageSection" id="ManageSection" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ClassName">Class</label>
												<div class="controls sel span8">   
													<select tabindex="4" name="ClassId" id="ClassId" class="nostyle" style="width:100%">
													<option></option>
													<?php echo $ListAllClassId; ?>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ClassName">Section Name</label>
												<input tabindex="5" class="span8" id="SectionName" type="text" name="SectionName" value="<?php echo $SectionName; ?>" />
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageSection" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count2>0) { ?>
										<input type="hidden" name="SectionId" value="<?php echo $UpdateSectionId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($SectionButtonContent,5); ?>
								</form>
                            </div>
						</div>
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Listing All Section</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="SectionTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Class Name</th>
											<th>Section</th>
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
$("#ClassId").select2();
$('#ClassTable').dataTable({
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

$('#SectionTable').dataTable({
	"sPaginationType": "two_button",
	"bJQueryUI": false,
	"bAutoWidth": false,
	"bLengthChange": false,  
	"bProcessing": true,
	"bDeferRender": true,
	"sAjaxSource": "plugins/Data/data2.txt",
	"fnInitComplete": function(oSettings, json) {
	  $('.dataTables_filter>label>input').attr('id', 'search');
	}
});

	$("input, textarea, select").not('.nostyle').uniform();
	$('#ClassId').select2({placeholder: "Select"});
	$("#ManageClass").validate({
		rules: {
			ClassName: {
				required: true,
			}
		},
		messages: {
			ClassName: {
				required: "Please select Name!!",
			}
		}   
	});
	$("#ManageSection").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			ClassId: {
				required: true,
			},
			SectionName: {
				required: true,
			}
		},
		messages: {
			ClassId: {
				required: "Please select Name!!",
			},
			SectionName: {
				required: "Please enter Value!!",
			}
		}   
	});
});
</script>
		
<?php
include("Template/Footer.php");
?>