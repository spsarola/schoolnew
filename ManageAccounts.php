<?php
$PageName="ManageAccounts";
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
$AccountId=isset($_GET['AccountId']) ? $_GET['AccountId'] : '';
$ButtonContentSet=$ButtonContent=$AddButton=$ManagedBy=$OpeningBalance=$AccountBalance=$AccountDate=$AccountStatus=$AccountStatusChecked=$AccountName=$count1=$AccountType=$AccountTypeName=$BankName=$BranchName=$IFSCCode=$BankAccountName="";
if($AccountId!="")
{
	$query1="select * from accounts where AccountId='$AccountId' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0 && $Action=="Update")
	{
		$row1=mysqli_fetch_array($check1);
		$ManagedBy=$row1['ManagedBy'];
		$AccountName=$row1['AccountName'];
		$AccountType=$row1['AccountType'];
		$AccountTypeName=GetCategoryValueOfId($AccountType,'AccountType');
		$BankName=$row1['BankName'];
		$BranchName=$row1['BranchName'];
		$BankAccountName=$row1['BankAccountName'];
		$IFSCCode=$row1['IFSCCode'];
		$OpeningBalance=round($row1['OpeningBalance'],2);
		$AccountBalance=$row1['AccountBalance'];
		$AccountDate=date("d-m-Y",$row1['AccountDate']);
		$AccountStatus=$row1['AccountStatus'];
		if($AccountStatus=="Active")
		$AccountStatusChecked="Checked=checked";
		else
		$AccountStatusChecked="";
		$ButtonContent="Update";
		$ButtonContentSet=1;
		$AddButton="Update <a href=ManageAccounts><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
		$UpdateAccountId=$AccountId;
		$Readonly="readonly";
	}
}
if($ButtonContentSet!=1)
{
	$ButtonContent="Add";
	$AddButton="Add Account";
}
?>

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Manage Accounts"; BreadCumb($BreadCumb); ?>
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
								<form class="form-horizontal" action="Action" name="ManageAccounts" id="ManageAccounts" method="Post">
									<?php if($count1>0) { ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Status</label>
												<input tabindex="2" class="styled" id="AccountStatus" type="checkbox" name="AccountStatus" <?php echo $AccountStatusChecked; ?> value="Active" />
											</div>
										</div>
									</div>
									<?php } ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="ManagedBy">Managed By</label> 
												<div class="span8 controls sel">   
													<?php
													GetCategoryValue('UserType','ManagedBy',$ManagedBy,'','','','',1,'');
													?>
												</div> 
											</div>
										</div> 
									</div>
									<?php if($count1==0) { ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="AccountType">Account Type</label> 
												<div class="span8 controls sel">   
													<?php
													GetCategoryValue('AccountType','AccountType',$AccountType,1,'GetAccountTypeDetail','GetAccountTypeDetail','',2,'');
													?>
												</div> 
											</div>
										</div> 
									</div>
									<?php } else { ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="AccountType">Account Type</label> 
											<input tabindex="2" class="span8" id="AccountTypeName" type="text" name="AccountTypeName" value="<?php echo $AccountTypeName; ?>" readonly/>
											</div>
										</div> 
									</div>		
									<input type="hidden" name="AccountType" value="<?php echo $AccountType; ?>" readonly>
									<?php } ?>
									<?php if($count1>0 && $AccountTypeName=="Cash") { ?>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="AccountName">Account Name</label>
													<input tabindex="2" class="span8" id="AccountName" type="text" name="AccountName" value="<?php echo $AccountName; ?>" />
												</div>
											</div>
										</div>									
									<?php } elseif($count1>0 && $AccountTypeName=="Bank") { ?>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="AccountName">Bank Account Number</label>
													<input tabindex="2" class="span8" id="AccountName" type="text" name="AccountName" value="<?php echo $AccountName; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="BankAccountName">Bank Account Name</label>
													<input tabindex="2" class="span8" id="BankAccountName" type="text" name="BankAccountName" value="<?php echo $BankAccountName; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="BankName">Bank Name</label>
													<input tabindex="2" class="span8" id="BankName" type="text" name="BankName" value="<?php echo $BankName; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="BranchName">Branch Name</label>
													<input tabindex="2" class="span8" id="BranchName" type="text" name="BranchName" value="<?php echo $BranchName; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="IFSCCode">IFSC Code</label>
													<input tabindex="2" class="span8" id="IFSCCode" type="text" name="IFSCCode" value="<?php echo $IFSCCode; ?>" />
												</div>
											</div>
										</div>									
									<?php } ?>
									<div id="GetAccountTypeDetail"></div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="OpeningBalance">Opening Balance</label>
												<input tabindex="3" class="span8" id="OpeningBalance" type="text" name="OpeningBalance" value="<?php echo $OpeningBalance; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="AccountDate" readonly>Account Start Date</label>
												<input tabindex="4" class="span8" id="AccountDate" type="text" name="AccountDate" readonly value="<?php echo $AccountDate; ?>" />
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageAccounts" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="AccountId" value="<?php echo $UpdateAccountId; ?>" readonly>
										<?php } ?>
										<?php ActionButton($ButtonContent,5); ?>
								</form>
                            </div>
                        </div>
                    </div>	
<?php
	$query="select AccountId,AccountName,OpeningBalance,AccountBalance,MasterEntryValue,AccountDate,ManagedBy,AccountStatus from masterentry,accounts where 
		masterentry.MasterEntryId=accounts.AccountType and AccountStatus!='Deleted' order by AccountName";
	$DATA=array();
	$QA=array();
	$result=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($result);
	while($row=mysqli_fetch_array($result))
	{
		$ListAccountName=$row['AccountName'];	
		$ListTotalBalance=round($row['OpeningBalance'],2)+round($row['AccountBalance'],2);	
		$ListAccountType=$row['MasterEntryValue'];
		$ListAccountStatus=$row['AccountStatus'];
		$ListAccountDate=date("d M Y",$row['AccountDate']);
		$ListAccountId=$row['AccountId'];
		$ListManagedBy=$row['ManagedBy'];
		if($ListManagedBy=="")
		$ListManagedBy="All";
		else
		$ListManagedBy=GetCategoryValueOfId($ListManagedBy,'UserType');
		if($ListAccountStatus=="Active")
		$ListAccountStatus="<span class=\"badge badge-success\">Active<span>";
		else
		$ListAccountStatus="<span class=\"badge badge-important\">In Active<span>";
		$Edit="<a href=ManageAccounts/Update/$ListAccountId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		$Note="<a href=Note/ManageAccounts/$ListAccountId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-clipboard-3\"></span></a>";
		$ListAccountName.=" $ListAccountStatus";
		$QA[]=array($ListAccountName,$ListAccountType,$ListManagedBy,$ListTotalBalance,$ListAccountDate,$Edit,$Note);
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
									<span>List all Accounts</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="AccountTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Account Name</th>
											<th>Account Type</th>
											<th>Managed by</th>
											<th>Balance</th>
											<th>Start Date</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
											<th><span class="icomoon-icon-clipboard-3 tip" title="Note"></span></th>
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
$("#AccountType").select2();
$("#ManagedBy").select2();
$('#AccountTable').dataTable({
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
	if($('#AccountDate').length) {
	$('#AccountDate').datepicker({ yearRange: "-100:+0", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
	$("input, textarea, select").not('.nostyle').uniform();
	$('#ManagedBy').select2({placeholder: "Select"});
	$('#AccountType').select2({placeholder: "Select"});
	$("#ManageAccounts").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			AccountType: {
				required: true,
			},
			AccountName: {
				required: true,
			},
			AccountDate: {
				required: true,
			},
			OpeningBalance: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithZero&Id=OpeningBalance"
			},
			BankAccountName: {
				required: true,
			},
			BankName: {
				required: true,
			},
			BranchName: {
				required: true,
			}
		},
		messages: {
			AccountType: {
				required: "Please select Account Type!!",
			},
			AccountName: {
				required: "Please enter Account Name!!",
			},
			AccountDate: {
				required: "Please select date!!",
			},
			OpeningBalance: {
				required: "Please enter Opening Balance!!",
				remote: jQuery.format("Cannot less than zero!!"),
			},
			BankAccountName: {
				required: "Please enter this!!",
			},
			BankName: {
				required: "Please enter this!!",
			},
			BranchName: {
				required: "Please enter this!!",
			}
		}   
	});
});
</script>
		
<?php
include("Template/Footer.php");
?>