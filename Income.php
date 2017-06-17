<?php
$PageName="Income";
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

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Income Account"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$ButtonContent=$ButtonContentSet="";
				if($Action=="Delete")
				{
					$TransactionId=$_GET['UniqueId'];
					$query1="select TransactionAmount,TransactionFrom from Transaction where TransactionId='$TransactionId' and TransactionHead='Income' and TransactionStatus='Active' and Username='$USERNAME' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					$row1=mysqli_fetch_array($check1);
					$AccountId=$row1['TransactionFrom'];
					$TransactionAmount=$row1['TransactionAmount'];
				}
				if($ButtonContentSet!=1)
					$ButtonContent="Add";
				?>
				
                <div class="row-fluid">
                    <div class="span3">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Income</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="Income" id="Income" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">For</label>
												<div class="span8 controls sel">
												<?php 
												$IncomeAccount="IncomeAccount";
												GetCategoryValue($IncomeAccount,$IncomeAccount,'','','','','',1,''); 
												?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Amount</label>
												<input tabindex="2" class="span8" id="Amount" type="text" name="Amount" value="" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Account</label>
												<div class="span8 controls sel">
												<select tabindex="3" class="nostyle" style="width:100%;" name="Account" id="Account">
												<option></option>
												<?php
												echo $LISTACCOUNT;
												?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Date of Income</label>
												<input tabindex="4" class="span8" id="DOI" type="text" name="DOI" value="" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Remarks</label>
												<div class="span8 controls-textarea">
												<textarea tabindex="5" name="Remarks" id="Remarks"></textarea>
												</div>
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="Income" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<?php ActionButton($ButtonContent,6); ?>
								</form>
                            </div>
                        </div>
                    </div>
					
					<div class="span9">
					<?php
					$query="select TransactionRemarks,TransactionId,TransactionAmount,MasterEntryValue,TransactionDate,AccountName from transaction,masterentry,accounts
					where transaction.TransactionHeadId=masterentry.MasterEntryId and 
					TransactionStatus='Active' and 
					TransactionHead='Income' and
					transaction.TransactionFrom=accounts.AccountId 
					order by TransactionDate ";
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					$DATA=array();
					$QA=array();
					while($row=mysqli_fetch_array($result))
					{
						$ListTransactionRemarks=$row['TransactionRemarks'];	
						$ListTransactionId=$row['TransactionId'];	
						$ListTransactionAmount=$row['TransactionAmount'];	
						$ListIncomeAccount=$row['MasterEntryValue'];	
						$ListAccountName=$row['AccountName'];	
						$ListTransactionDate=date("d M Y,h:ia",$row['TransactionDate']);		
						$Delete="<a href=DeletePopUp/DeleteIncome/$ListTransactionId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel\"></span></a>";
						$Note="<a href=Note/Income/$ListTransactionId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-clipboard-3\"></span></a>";
						$QA[]=array($ListTransactionId,$ListIncomeAccount,$ListAccountName,$ListTransactionAmount,$ListTransactionDate,$ListTransactionRemarks,$Delete,$Note);
					}
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);
					?>
						<div class="box gradient">
							<div class="title">
								<h4><span>Income List</span></h4>
                                <a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<table id="IncomeTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Receipt No</th>
											<th>From</th>
											<th>Account</th>
											<th>Amount</th>
											<th>Date</th>
											<th>Remarks</th>
											<th><span class="icomoon-icon-cancel tip" title="Delete"></span></th>
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

	$('#IncomeTable').dataTable({
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
		
	if($('#DOI').length) {
	$('#DOI').datetimepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
	$("input, textarea, select").not('.nostyle').uniform();
	$("#IncomeAccount").select2();
	$('#IncomeAccount').select2({placeholder: "Select"});
	$("#Account").select2();
	$('#Account').select2({placeholder: "Select"});
	$("#Income").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			Amount: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=Amount"
			},
			IncomeAccount: {
				required: true,
			},
			Account: {
				required: true,
			},
			DOI: {
				required: true,
			},
			Remarks: {
				required: true,
			}
		},
		messages: {
			Amount: {
				required: "Please enter this!!",
				remote: jQuery.format("Should be numeric!!")
			},
			IncomeAccount: {
				required: "Please select this!!",
			},
			Account: {
				required: "Please select this!!",
			},
			DOI: {
				required: "Please enter this!!",
			},
			Remarks: {
				required: "Please enter this!!",
			}
		}   
	});

	$("#DeleteIncome").validate({
		rules: {
			Password: {
				required: true,
			}
		},
		messages: {
			Password: {
				required: "Please enter this!!",
			}
		}   
	});
});
</script>		    
<?php
include("Template/Footer.php");
?>