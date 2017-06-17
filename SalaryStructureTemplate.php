<?php
$PageName="SalaryStructureTemplate";
$FormRequired=1;
$TableRequired=1;
$TooltipRequired=1;
$SearchRequired=1;
$TextBoxAutoComplete=1;
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
$SalaryStructureId=isset($_GET['SalaryStructureId']) ? $_GET['SalaryStructureId'] : '';
$ButtonContentSet=$ButtonContent=$AddButton=$SalaryStructureStatus=$SalaryStructureName=$UpdateFixedSalaryHead=$count1="";
if($SalaryStructureId!="")
{
	$query1="select * from salarystructure where SalaryStructureId='$SalaryStructureId' ";
	
	$query101="select StaffSalaryId from staffsalary where SalaryStructureId='$SalaryStructureId' ";
	$check101=mysqli_query($CONNECTION,$query101);
	$count101=mysqli_num_rows($check101);
	
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0 && $Action=="Update")
	{
		$row1=mysqli_fetch_array($check1);
		$SalaryStructureName=$row1['SalaryStructureName'];
		$UpdateFixedSalaryHead=$row1['FixedSalaryHead'];
		$UpdateFixedSalaryHead=explode(",",$UpdateFixedSalaryHead);
		$SalaryStructureStatus=$row1['SalaryStructureStatus'];
		if($SalaryStructureStatus=="Active")
		$SalaryStructureStatusChecked="Checked=checked";
		else
		$SalaryStructureStatusChecked="";
		$ButtonContent="Update";
		$ButtonContentSet=1;
		$AddButton="Update <a href=SalaryStructureTemplate><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateSalaryStructureId=$SalaryStructureId;
	}
	elseif($count1>0 && $Action=="View")
	{
		$row1=mysqli_fetch_array($check1);
		$ViewSalaryStructureName=$row1['SalaryStructureName'];	
		$ViewFixedSalaryHead=$row1['FixedSalaryHead'];	
		$UpdateFixedSalaryHead=explode(",",$ViewFixedSalaryHead);
	}
}
if($ButtonContentSet!=1)
{
	$ButtonContent="Add";
	$AddButton="Add New Template";
}


$SalaryHead=isset($_SESSION['SalaryHead']) ? $_SESSION['SalaryHead'] : '';
$Expression=isset($_SESSION['Expression']) ? $_SESSION['Expression'] : '';
unset($_SESSION['SalaryHead']);
unset($_SESSION['Expression']);

$query2="select SalaryHeadId,SalaryHead,Code,MasterEntryValue from salaryhead,masterentry where salaryhead.SalaryHeadType=masterentry.MasterEntryId order by SalaryHead ";
$check2=mysqli_query($CONNECTION,$query2);
$ComboSalaryHeadSelected=$ListAllSalaryHead=$ListAllSalaryHeadForTemplate=$count101="";
while($row2=mysqli_fetch_array($check2))
{
	$SelectSalaryHead=$row2['SalaryHead'];
	$SelectCode=$row2['Code'];
	$SelectSalaryHeadType=$row2['MasterEntryValue'];
	$SelectSalaryHeadId=$row2['SalaryHeadId'];
	$SalaryHeadIdArray[]=$SelectSalaryHeadId;
	$SalaryHeadArray[]=$SelectSalaryHead;
	$SalaryHeadCodeArray[]=$SelectCode;
	$SalaryHeadTypeArray[]=$SelectSalaryHeadType;
	$ListAllSalaryHead.="<option value=\"$SelectSalaryHeadId\">$SelectSalaryHead ($SelectCode)</option>";
	
	if($UpdateFixedSalaryHead!="")
	{
		foreach($UpdateFixedSalaryHead as $UpdateFixedSalaryHeadValue)
		{
			if($UpdateFixedSalaryHeadValue==$SelectSalaryHeadId)
			{
				$ComboSalaryHeadSelected="selected";
				break;
			}
			else
				$ComboSalaryHeadSelected="";
		}
	}
	if($Action!="Update")
	$ComboSalaryHeadSelected="";
	$ListAllSalaryHeadForTemplate.="<option value=\"$SelectSalaryHeadId\" $ComboSalaryHeadSelected>$SelectSalaryHead ($SelectCode)</option>";
	if($ComboSalaryHeadSelected=="" && $Action=="View")
	{
		if($UpdateFixedSalaryHead!="")
		{
			$SearchForFixedSalaryHeadIndex=array_search($SelectSalaryHeadId,$UpdateFixedSalaryHead);
			if($SearchForFixedSalaryHeadIndex===FALSE)
			$ListAllSalaryHeadExceptFixed.="<option value=\"$SelectSalaryHeadId\">$SelectSalaryHead ($SelectCode)</option>";
		}
		else
		$ListAllSalaryHeadExceptFixed.="<option value=\"$SelectSalaryHeadId\">$SelectSalaryHead ($SelectCode)</option>";
	}
}

	$query="select * from salarystructure where SalaryStructureStatus='Active' or SalaryStructureStatus='' order by SalaryStructureName";
	$DATA=array();
	$QA=array();
	$result=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($result);
	while($row=mysqli_fetch_array($result))
	{
		$ListSalaryStructureName=$row['SalaryStructureName'];	
		$ListSalaryStructureId=$row['SalaryStructureId'];	
		$ListSalaryStructureName="<a href=/SalaryStructureTemplate/View/$ListSalaryStructureId>$ListSalaryStructureName</a>";
		$ListSalaryStructureStatus=$row['SalaryStructureStatus'];	
		$List="";
		if($ListSalaryStructureStatus=="Active")
		$ListSalaryStructureStatus="<span class=\"badge badge-success\">Active<span>";
		else
		$ListSalaryStructureStatus="<span class=\"badge badge-important\">In Active<span>";
		$ListFixedSalaryHead=$row['FixedSalaryHead'];
		$ListFixedSalaryHead=explode(",",$ListFixedSalaryHead);
		foreach($ListFixedSalaryHead as $ListFixedSalaryHeadValues)
		{
			$SearchFixedSalaryIndex=array_search($ListFixedSalaryHeadValues,$SalaryHeadIdArray);
			$ListFixedSalaryHeadName=$SalaryHeadArray[$SearchFixedSalaryIndex];
			$ListFixedSalaryCode=$SalaryHeadCodeArray[$SearchFixedSalaryIndex];
			$List.="$ListFixedSalaryHeadName ($ListFixedSalaryCode) <br>";
		}
		$Edit="<a href=SalaryStructureTemplate/Update/$ListSalaryStructureId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		$DeleteTemplate="<a href=DeletePopUp/DeleteSalaryTemplate/$ListSalaryStructureId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel\"></span></a>";
		$QA[]=array($ListSalaryStructureName,$List,$Edit,$DeleteTemplate);
	}
	$DATA['aaData']=$QA;
	$fp = fopen('plugins/Data/data1.txt', 'w');
	fwrite($fp, json_encode($DATA));
	fclose($fp);
	
	if($count1>0 && $Action=="View")
	{

		
		$query3="select SalaryHead,salarystructuredetail.SalaryHeadId,SalaryStructureDetailId,Expression,MasterEntryValue,Code from salarystructuredetail,salaryhead,masterentry where
			salarystructuredetail.SalaryHeadId=salaryhead.SalaryHeadId and
			salaryhead.SalaryHeadType=masterentry.MasterEntryId and 
			SalaryStructureId='$SalaryStructureId' ";
		$check3=mysqli_query($CONNECTION,$query3);
		$count3=mysqli_num_rows($check3);
		$DATA2=array();
		$QA2=array();
		foreach($UpdateFixedSalaryHead as $UpdateFixedSalaryHeadValue)
		{
			$FixedSalarySearchIndex=array_search($UpdateFixedSalaryHeadValue,$SalaryHeadIdArray);
			$ViewFixedSalary=$SalaryHeadArray[$FixedSalarySearchIndex]." <b>(".$SalaryHeadCodeArray[$FixedSalarySearchIndex].")</b>";
			$ViewFixedSalaryType=$SalaryHeadTypeArray[$FixedSalarySearchIndex];
			$QA2[]=array($ViewFixedSalary,$ViewFixedSalaryType,'Fixed');
		}		
		if($count3>0)
		{
			while($row3=mysqli_fetch_array($check3))
			{
				$ListDetailSalaryHead=$row3['SalaryHead']." <b>(".$row3['Code'].")</b>";
				$ListDetailSalaryHeadId=$row3['SalaryHeadId'];
				$ListDetailExpression=$row3['Expression'];
				$ListDetailSalaryHeadType=$row3['MasterEntryValue'];
				$QA2[]=array($ListDetailSalaryHead,$ListDetailSalaryHeadType,$ListDetailExpression);
			}
		}
		$DATA2['aaData']=$QA2;
		$fp = fopen('plugins/Data/data2.txt', 'w');
		fwrite($fp, json_encode($DATA2));
		fclose($fp);
	}
?>				

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Salary Structure Template"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
                <div class="row-fluid">
                    <div class="span5">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
							<?php if($count101>0) { echo "<div class=\"alert alert-error\">This structure is already assinged to $count101 staff!! You cannot update its name!!</div>"; } ?>
								<form class="form-horizontal" action="Action" name="ManageSalaryStructureTemplate" id="ManageSalaryStructureTemplate" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="SalaryStructureName">Template Name</label> 
												<input tabindex="1" class="span8" id="SalaryStructureName" type="text" name="SalaryStructureName" value="<?php echo $SalaryStructureName; ?>" />
											</div>
										</div> 
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="FixedSalaryHead">Fixed Salary</label> 
												<div class="span8 controls sel">
												<select tabindex="2" name="FixedSalaryHead[]" id="FixedSalaryHead" class="nostyle" style="width:100%;" multiple="multiple">
												<option></option>
												<?php echo $ListAllSalaryHeadForTemplate; ?>
												</select>
												</div> 
											</div>
										</div> 
									</div>
									<?php if($count1>0 && $Action=="Update") { ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SalaryStructureStatus">Status</label>
												<input tabindex="3" class="styled" id="SalaryStructureStatus" type="checkbox" name="SalaryStructureStatus" <?php echo $SalaryStructureStatusChecked; ?> value="Active" />
											</div>
										</div>
									</div>
									<?php } ?>
										<input type="hidden" name="Action" value="ManageSalaryStructureTemplate" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="SalaryStructureId" value="<?php echo $UpdateSalaryStructureId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ButtonContent,4); ?>
								</form>
                            </div>
                        </div>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Salary Templates</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="SalaryStructureTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Name</th>
											<th>Fixed Salary Head</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
											<th><span class="icomoon-icon-cancel tip" title="Delete"></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
                    </div>	

	
					<div class="span7">
					<?php if($count1>0 && $Action=="View") { 
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Salary Structure "<?php echo $ViewSalaryStructureName; ?>" </span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<form class="form-horizontal" action="Action" name="ManageSalaryStructureTemplate2" id="ManageSalaryStructureTemplate2" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="SalaryHead">Salary Head</label> 
												<div class="span8 controls sel">
												<select tabindex="1" name="SalaryHead" id="SalaryHead" class="nostyle" style="width:100%;">
												<option></option>
												<?php echo $ListAllSalaryHeadExceptFixed; ?>
												</select>
												</div> 
											</div>
										</div> 
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="Expression">Expression</label> 
												<div class="span8 controls-textarea">
												<div id="outter"><textarea tabindex="7" id="Expression" name="Expression" class="span12"><?php echo $Expression; ?></textarea></div>
												</div> 
											</div>
										</div> 
									</div>		
										<input type="hidden" name="Action" value="ManageSalaryStructureTemplate2" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<input type="hidden" name="SalaryStructureId" value="<?php echo $SalaryStructureId; ?>" readonly>
									<?php ActionButton($ButtonContent,3); ?>							
								</form>
							</div>
						</div>		
						<div class="box gradient">
							<div class="title">
								<h4>
									<span><?php echo "$ViewSalaryStructureName Salary Head"; ?></span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="SalaryStructureDetailTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Salary Head</th>
											<th>Type</th>
											<th>Expression</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					<?php } ?>
					</div>	
					
                </div>
            </div>
        </div>

<script type="text/javascript">
var Head = [
		<?php 
			foreach($SalaryHeadCodeArray as $SalaryHeadCodeArrayValue)
			{
				echo "\"$SalaryHeadCodeArrayValue\",";
			}
		?>
		];
function initURLTextarea(){
	$("#outter textarea").autocomplete({
	wordCount:1,
	mode: "outter",
	on: {
		query: function(text,cb){
			var words = [];
			for( var i=0; i<Head.length; i++ ){
				if( Head[i].toLowerCase().indexOf(text.toLowerCase()) == 0 ) words.push(Head[i]);
				
			}
			cb(words);								
		}
	}
	});
}

$(document).ready(function() {
initURLTextarea();
$('#SalaryStructureTable').dataTable({
	"sPaginationType": "two_button",
	"bJQueryUI": false,
	"bAutoWidth": false,
	"bLengthChange": false,  
	"bProcessing": true,
	"bDeferRender": true,
	"sAjaxSource": "plugins/Data/data1.txt",
	"fnInitComplete": function(oSettings, json) {
	  $('.dataTables_filter>label>input').attr('id', 'search');
		$('#myModal').modal({ show: false});
		$('#myModal').on('hidden', function () {
			console.log('modal is closed');
		})
		$("a[data-toggle=modal]").click(function (e) {
		lv_target = $(this).attr('data-target');
		lv_url = $(this).attr('href');
		$(lv_target).load(lv_url);
		});	
	}
});

$('#SalaryStructureDetailTable').dataTable({
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
	$("#FixedSalaryHead").select2();
	$('#FixedSalaryHead').select2({placeholder: "Select"});
	$("#SalaryHead").select2();
	$('#SalaryHead').select2({placeholder: "Select"});
	$("#ManageSalaryStructureTemplate").validate({
		rules: {
			FixedSalaryHead: {
				required: true,
			},
			SalaryStructureName: {
				required: true,
			}
		},
		messages: {
			FixedSalaryHead: {
				required: "Please enter this!!",
			},
			SalaryStructureName: {
				required: "Please enter this!!",
			}
		}   
	});
	$("#ManageSalaryStructureTemplate2").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			SalaryHead: {
				required: true,
			}
		},
		messages: {
			SalaryHead: {
				required: "Please select this!!",
			}
		}   
	});
});
</script>
		
<?php
include("Template/Footer.php");
?>