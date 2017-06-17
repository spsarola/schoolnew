<?php
$PageName="SalaryHead";
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
$SalaryHeadId=isset($_GET['SalaryHeadId']) ? $_GET['SalaryHeadId'] : '';
$ButtonContentSet=$ButtonContent=$AddButton=$SalaryHeadStatus=$SalaryHeadStatusChecked=$SalaryHead=$DailyBasis=$SalaryHeadType=$DailyBasisChecked=$Code=$count1="";
if($SalaryHeadId!="")
{
	$query1="select * from salaryhead where SalaryHeadId='$SalaryHeadId' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0 && $Action=="Update")
	{
		$row1=mysqli_fetch_array($check1);
		$SalaryHead=$row1['SalaryHead'];
		$DailyBasis=$row1['DailyBasis'];
		$SalaryHeadType=$row1['SalaryHeadType'];
		if($DailyBasis==1)
		$DailyBasisChecked="checked=checked";
		else
		$DailyBasisChecked="";
		$Code=$row1['Code'];
		$SalaryHeadStatus=$row1['SalaryHeadStatus'];
		if($SalaryHeadStatus=="Active")
		$SalaryHeadStatusChecked="Checked=checked";
		else
		$SalaryHeadStatusChecked="";
		$ButtonContent="Update";
		$ButtonContentSet=1;
		$AddButton="Update <a href=SalaryHead><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateSalaryHeadId=$SalaryHeadId;
	}
}
if($ButtonContentSet!=1)
{
	$ButtonContent="Add";
	$AddButton="Add Salary Head";
}
?>

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Salary Head"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageSalaryHead" id="ManageSalaryHead" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="SalaryHeadType">Type</label> 
												<div class="span8 controls sel">   
													<?php
													GetCategoryValue('SalaryHeadType','SalaryHeadType',$SalaryHeadType,'','','','',1,'');
													?>
												</div> 
											</div>
										</div> 
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="SalaryHead">Salary Head</label> 
												<input tabindex="2" class="span8" id="SalaryHead" type="text" name="SalaryHead" value="<?php echo $SalaryHead; ?>" />
											</div>
										</div> 
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Code">Code</label>
												<input tabindex="2" class="span8" id="Code" type="text" name="Code" value="<?php echo $Code; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="DailyBasis">Daily Basis</label>
												<input tabindex="2" class="styled" id="DailyBasis" type="checkbox" name="DailyBasis" <?php echo $DailyBasisChecked; ?> value="1" />
											</div>
										</div>
									</div>
									<?php if($count1>0) { ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SalaryHeadStatus">Status</label>
												<input tabindex="2" class="styled" id="SalaryHeadStatus" type="checkbox" name="SalaryHeadStatus" <?php echo $SalaryHeadStatusChecked; ?> value="Active" />
											</div>
										</div>
									</div>
									<?php } ?>
										<input type="hidden" name="Action" value="ManageSalaryHead" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="SalaryHeadId" value="<?php echo $UpdateSalaryHeadId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ButtonContent,3); ?>
								</form>
                            </div>
                        </div>
                    </div>		

<?php
	$query="select * from salaryhead,masterentry where salaryhead.SalaryHeadType=masterentry.MasterEntryId order by SalaryHead";
	$DATA=array();
	$QA=array();
	$result=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($result);
	while($row=mysqli_fetch_array($result))
	{
		$ListSalaryHead=$row['SalaryHead'];	
		$ListSalaryHeadId=$row['SalaryHeadId'];	
		$ListSalaryHeadType=$row['MasterEntryValue'];	
		$ListCode=$row['Code'];
		$ListSalaryHeadStatus=$row['SalaryHeadStatus'];
		$ListDailyBasis=$row['DailyBasis'];
		if($ListDailyBasis==1)
		$ListDailyBasis="Yes";
		else
		$ListDailyBasis="No";
		if($ListSalaryHeadStatus=="Active")
		$ListSalaryHeadStatus="<span class=\"badge badge-success\">Active<span>";
		else
		$ListSalaryHeadStatus="<span class=\"badge badge-important\">In Active<span>";
		$Edit="<a href=SalaryHead/Update/$ListSalaryHeadId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		$ListSalaryHead.=" $ListSalaryHeadStatus";
		$QA[]=array($ListSalaryHeadType,$ListSalaryHead,$ListCode,$ListDailyBasis,$Edit);
	}
	$DATA['aaData']=$QA;
	$fp = fopen('plugins/Data/data1.txt', 'w');
	fwrite($fp, json_encode($DATA));
	fclose($fp);
?>					
					<div class="span8">
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Salary Head</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="SalaryHeadTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Head Type</th>
											<th>Salary Head</th>
											<th>Code</th>
											<th>Daily Basis</th>
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
$('#SalaryHeadTable').dataTable({
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
	$("#SalaryHeadType").select2();
	$('#SalaryHeadType').select2({placeholder: "Select"});
	$("#ManageSalaryHead").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			SalaryHeadType: {
				required: true,
			},
			SalaryHead: {
				required: true,
			},
			Code: {
				required: true,
			}
		},
		messages: {
			SalaryHeadType: {
				required: "Please select this!!",
			},
			SalaryHead: {
				required: "Please enter this!!",
			},
			Code: {
				required: "Please enter this!!",
			}
		}   
	});
});
</script>
		
<?php
include("Template/Footer.php");
?>