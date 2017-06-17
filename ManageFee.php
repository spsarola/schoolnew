<?php
$PageName="ManageFee";
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
$ButtonContentSet=$ButtonContent=$AddButton=$SectionId=$FeeType=$Amount=$Distance=$TransportFeeChecked=$count1="";
if($UniqueId!="" && ($Action=="UpdateFee" || $Action=="DeleteFee"))
{
	$query1="select * from fee where FeeId='$UniqueId' and Session='$CURRENTSESSION' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0 && $Action=="UpdateFee")
	{
		$row1=mysqli_fetch_array($check1);
		$SectionId=$row1['SectionId'];
		$FeeType=$row1['FeeType'];
		$Amount=$row1['Amount'];
		$Distance=$row1['Distance'];
		if($Distance!="")
		$TransportFeeChecked="checked=checked";
		$ButtonContent="Update";
		$ButtonContentSet=1;
		$AddButton="Update <a href=ManageFee><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateFeeId=$UniqueId;
	}
}

if($ButtonContentSet!=1)
{
	$ButtonContent="Add";
	$AddButton="Add Fee";
}

	$query2="select ClassName,SectionName,SectionId from class,section where 
		class.ClassId=section.ClassId and class.ClassStatus='Active' and
		section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
	$check2=mysqli_query($CONNECTION,$query2);
		$Selected="";
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

	
	$query="select MasterEntryValue,ClassName,SectionName,Amount,FeeId,Distance from fee,section,class,masterentry where 
		fee.Session='$CURRENTSESSION' and 
		fee.SectionId=section.SectionId and
		section.ClassId=class.ClassId and 
		fee.FeeType=masterentry.MasterEntryId and 
		FeeStatus='Active' order by ClassName,SectionName";
	$DATA=array();
	$QA=array();
	$result=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($result);
	$FeeTypeName="";
	while($row=mysqli_fetch_array($result))
	{
		$ListClassName=$row['ClassName'];	
		$ListSectionName=$row['SectionName'];	
		$ListAmount=$row['Amount'];	
		$ListFeeId=$row['FeeId'];	
		$ListDistance=$row['Distance'];
		if($ListDistance!="")
		{
		$ListDistanceName=GetCategoryValueOfId($ListDistance,'Distance');
		$ListDistanceName="<span class=\"badge badge-success\">$ListDistanceName</span>";
		}
		else
		$ListDistanceName="";
		$FeeTypeName=$row['MasterEntryValue'];	
		$Edit="<a href=ManageFee/UpdateFee/$ListFeeId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		$ListClassName.=" $ListSectionName";
		$FeeTypeName.=" $ListDistanceName";
		$QA[]=array($ListClassName,$FeeTypeName,$ListAmount,$Edit);
	}
	$DATA['aaData']=$QA;
	$fp = fopen('plugins/Data/data1.txt', 'w');
	fwrite($fp, json_encode($DATA));
	fclose($fp);
?>

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Manage Fee"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification();?>
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
								<form class="form-horizontal" action="Action" name="ManageFee" id="ManageFee" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SectionId">Class</label>
												<div class="controls sel span8">   
												<select tabindex="1" <?php if($count1==0) echo "name=\"SectionId[]\""; else echo "name=\"SectionId\"";  ?> id="SectionId" class="nostyle" style="width:100%;" <?php if($count1==0) echo "multiple=\"multiple\""; ?>>
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
												<label class="form-label span4" for="FeeType">Fee Type</label>
												<div class="controls sel span8">   
												<?php
												GetCategoryValue('FeeType','FeeType',$FeeType,'','','','',2,'');
												?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="TransportFee">Transport</label>
												<input tabindex="3" class="styled" id="TransportFee" type="checkbox" name="TransportFee" value="Yes" <?php echo $TransportFeeChecked; ?> />
												Check only if fee is Transport Fee
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="FeeType">Distance</label>
												<div class="controls sel span8">   
												<?php
												GetCategoryValue('Distance','Distance',$Distance,'','','','',4,'');
												?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Amount">Amount</label>
												<input tabindex="5" class="span8" id="Amount" type="text" name="Amount" value="<?php echo $Amount; ?>" />
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageFee" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="FeeId" value="<?php echo $UpdateFeeId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ButtonContent,6); ?>
								</form>
                            </div>
						</div>
					</div>
					<div class="span8">
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Listing All Fee</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="FeeTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Class</th>
											<th>Fee Type</th>
											<th>Amount</th>
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
$("#SectionId").select2();
$("#FeeType").select2();
$("#Distance").select2();
$('#FeeTable').dataTable({
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
	$('#SectionId').select2({placeholder: "Select"});
	$('#Distance').select2({placeholder: "Select"});
	$('#FeeType').select2({placeholder: "Select"});
	$("#ManageFee").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			SectionId: "required",
			FeeType: "required",
			Amount: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithZero&Id=Amount"
			},
			Distance: {
				required: "#TransportFee:checked",
			},
			SectionId: {
				required: true,
			}
		},
		messages: {
			Amount: {
				required: "Please enter this!!",
				remote: jQuery.format("Numeric values allowed!!")
			},
			Distance: {
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