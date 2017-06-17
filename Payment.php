<?php
$PageName="Payment-Student-Parents";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
include("Include.php");
IsLoggedIn();
if(isset($_GET['AdmissionId']) && !isset($_SESSION['PaymentToken']))
$Token=PasswordGenerator(30);
elseif(isset($_GET['AdmissionId']) && isset($_SESSION['PaymentToken']))
$Token=$_SESSION['PaymentToken'];

unset($_SESSION['PaymentToken']);
include("Template/HTML.php");
?>    
<?php
include("Template/Header.php");
?>
<?php if(isset($_GET['AdmissionId'])) { ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('table#links td a.delete').click(function()
		{
			if (confirm("Are you sure you want to delete this row?"))
			{
				var id = $(this).parent().parent().attr('id');
				var parent = $(this).parent().parent();
				$.ajax(
				{
				   type: "POST",
				   url: "DeleteRow.php",
				   data: { id: id, Action: 'test'},
				   cache: false,

				   success: function()
				   {
						parent.fadeOut('slow', function() {$(this).remove();});
				   },
				   error: function()
				   {
				   }
				 });                
			}
		});
	});
</script>
<?php } ?>

<?php
include("Template/Sidebar.php");
?>

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Payment"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				<?php
				$FormEntryNotAllowed=$UserAdmissionQuery=$AdmissionUsername="";
				$count4=0;
				if($USERTYPE=="Parents" || $USERTYPE=="Student")
				{
					$UsernameArray=explode('@',$USERNAME);
					$AdmissionUsername=$UsernameArray[0];
					$UserAdmissionQuery=" and admission.AdmissionId='$AdmissionUsername' ";
					$FormEntryNotAllowed=1;
				}
				$AdmissionId=isset($_GET['AdmissionId']) ? $_GET['AdmissionId'] : '' ;
				$SelectedAdmissionId=$SelectedSectionId=$TotalFixedFeeAmount=$TotalPaidFeeType=$TotalFeeValue=$TotalPaid="";
				$PaidFeeIdArray=array();
				$query="Select Distance,FeeStructure,admission.AdmissionId,registration.RegistrationId,StudentName,FatherName,Mobile,ClassName,SectionName,section.SectionId,class.ClassId from registration,class,section,admission,studentfee where
					studentfee.Session='$CURRENTSESSION' and
					class.ClassId=section.ClassId and
					studentfee.SectionId=section.SectionId and
					registration.RegistrationId=admission.RegistrationId and
					admission.AdmissionId=studentfee.AdmissionId and 
					Status='Studying' $UserAdmissionQuery
					order by StudentName";
				$check=mysqli_query($CONNECTION,$query);
				$ListAllStudents="";
				while($row=mysqli_fetch_array($check))
				{
					$ComboStudentName=$row['StudentName'];
					$ComboAdmissionId=$row['AdmissionId'];
					$ComboFatherName=$row['FatherName'];
					$ComboClassName=$row['ClassName'];
					$ComboSectionName=$row['SectionName'];
					$ComboSectionId=$row['SectionId'];
					$ComboMobile=$row['Mobile'];
					if($ComboAdmissionId==$AdmissionId)
					{
						$Selected="selected";
						$SelectedSectionId=$ComboSectionId;
						$SelectedStudentName=$ComboStudentName;
						$SelectedFatherName=$ComboFatherName;
						$SelectedStudentMobile=$ComboMobile;
						$SelectedAdmissionId=$ComboAdmissionId;
						$SelectedFeeStructure=$row['FeeStructure'];
						$SelectedDistance=$row['Distance'];
						$Selected="selected";
						$ValidAdmissionId=1;
					}
					else
					$Selected="";
					$ListAllStudents.="<option value=\"$ComboAdmissionId\" $Selected>$ComboStudentName F/n $ComboFatherName $ComboMobile $ComboClassName $ComboSectionName</option>";
				}	
				
				if($SelectedSectionId!="")
				{
									
					$query2="select FeeId,MasterEntryValue,FeeType,Amount,Distance from fee,masterentry where
						fee.FeeType=masterentry.MasterEntryId and
						fee.FeeStatus='Active' and
						fee.Session='$CURRENTSESSION' and
						fee.SectionId='$SelectedSectionId' ";
					$check2=mysqli_query($CONNECTION,$query2);
					while($row2=mysqli_fetch_array($check2))
					{
						$FeeIdArray[]=$row2['FeeId'];
						$FeeTypeNameArray[]=$row2['MasterEntryValue'];
						$FeeTypeIdArray[]=$row2['FeeType'];
						$FeeAmountArray[]=$row2['Amount'];
						$FeeDistanceArray[]=$row2['Distance'];
					}
					
					$query3="select SUM(Amount) as Paid,FeeType from feepayment,transaction where
						transaction.Token=feepayment.Token and
						transaction.TransactionStatus='Active' and
						feepayment.FeePaymentStatus='Active' and
						transaction.TransactionHead='Fee' and 
						transaction.TransactionSession='$CURRENTSESSION' and 
						transaction.TransactionHeadId='$SelectedAdmissionId' 
						group by FeeType";
					$check3=mysqli_query($CONNECTION,$query3);
					$count3=mysqli_num_rows($check3);
					if($count3>0)
					{
						while($row3=mysqli_fetch_array($check3))
						{
							$PaidFeeIdArray[]=$row3['FeeType'];
							$PaidFeeAmountArray[]=$row3['Paid'];
						}
					}
					$FeeList=$ListPaidFee=$ListAllFee="";
					$query4="select TransactionDate,TransactionId,TransactionAmount,TransactionRemarks from transaction where 
						TransactionStatus='Active' and 
						TransactionHead='Fee' and 
						TransactionHeadId='$SelectedAdmissionId' and 
						TransactionSession='$CURRENTSESSION'";
					$check4=mysqli_query($CONNECTION,$query4);
					$count4=mysqli_num_rows($check4);
					if($count4>0)
					{
						while($row4=mysqli_fetch_array($check4))
						{
							$TransactionDate=date("d M Y,h:ia",$row4['TransactionDate']);
							$TransactionId=$row4['TransactionId'];
							$TransactionAmount=$row4['TransactionAmount'];
							$TransactionRemarks=$row4['TransactionRemarks'];
							$PrintReceipt="<A href=PrintReceipt/$TransactionId><span class=\"icon-print\"></span></a>";
							$Delete="<a href=DeletePopUp/DeleteFee/$TransactionId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel\"></span></a>";
							if($FormEntryNotAllowed!=1)
							$ListPaidFee.="<tr><td>$TransactionId</td><td>$TransactionAmount $CURRENCY</td><Td>$TransactionDate</td><Td>$TransactionRemarks</td><td>$Delete</td><td>$PrintReceipt</td></tr>";
							else
							$ListPaidFee.="<tr><td>$TransactionId</td><td>$TransactionAmount $CURRENCY</td><Td>$TransactionDate</td><Td>$TransactionRemarks</td><td>$PrintReceipt</td></tr>";
							$TotalPaid+=$TransactionAmount;
						}
					}	
					
					$SelectedFeeStructure=explode(",",$SelectedFeeStructure);
					foreach($SelectedFeeStructure as $SelectedFeeStructureValue)
					{
						$PaidFeeType=0;
						$FeeTypeAndValue=explode("-",$SelectedFeeStructureValue);
						$FeeTypeId=$FeeTypeAndValue[0];
						$FeeValue=$FeeTypeAndValue[1];
						$FeeValueOrg=$FeeTypeAndValue[1];
						$SearchFeeIndex=array_search($FeeTypeId,$FeeIdArray);
						$FeeName=$FeeTypeNameArray[$SearchFeeIndex];
						//$FixedFeeAmount=$FeeAmountArray[$SearchFeeIndex];
												
						if($PaidFeeIdArray!="")
						{
							$SearchPaidFeeIndex=array_search($FeeTypeId,$PaidFeeIdArray);
							if($SearchPaidFeeIndex===FALSE){}
							else
							{
								$PaidFeeType=$PaidFeeAmountArray[$SearchPaidFeeIndex];
								$FeeValue-=$PaidFeeType;
							}
						}
						$ListAllFee.="<tr><td>$FeeName</td><td>$FeeValueOrg $CURRENCY</td><td>$PaidFeeType $CURRENCY</td><td>$FeeValue $CURRENCY</td></tr>";
						$TotalFixedFeeAmount+=$FeeValueOrg;
						$TotalPaidFeeType+=$PaidFeeType;
						$TotalFeeValue+=$FeeValue;
						unset($SearchFeeIndex);
						$FeeList.="<option value=\"$FeeTypeId\">$FeeName Balance: $FeeValue $CURRENCY</option>";
					}
				}
				
				?>
	
                <div class="row-fluid">
                    <div class="span6">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Select Student</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding:5px;">
								<form class="form-horizontal" action="ReportAction" name="FeeSelectStudent" id="FeeSelectStudent" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="AdmissionId">Student</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="AdmissionId" id="AdmissionId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListAllStudents; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="PaymentSelectStudent" readonly>
									<?php $ButtonContent="Proceed"; ActionButton($ButtonContent,2); ?>
								</form>
							</div>
						</div>
						<?php if($SelectedAdmissionId!="" && $FormEntryNotAllowed!=1) { ?>
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Select Fee for <?php echo "$SelectedStudentName F/n $SelectedFatherName ($SelectedStudentMobile)"; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad clearfix" style="padding-bottom:2px;">
								<form class="form-horizontal" action="Data" name="Fee" id="Fee" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="FeeType">Select Fee</label>
												<div class="controls sel span8">   
												<select tabindex="3" name="FeeType" id="FeeType" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $FeeList; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Amount">Amount</label>
												<input tabindex="4" class="span8" id="Amount" type="text" name="Amount" />
											</div>
										</div>
									</div>	
									<input type="hidden" name="Token" value="<?php echo $Token; ?>" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="AdmissionId" value="<?php echo $SelectedAdmissionId; ?>" readonly>
									<input type="hidden" name="SectionId" value="<?php echo $SelectedSectionId; ?>" readonly>
									<input type="hidden" name="CurrentSession" value="<?php echo $CURRENTSESSION; ?>" readonly>
									<input type="hidden" name="Action" value="Fee" readonly>
									<?php $ButtonContent="Add in Fee List"; ActionButton($ButtonContent,5); ?>									
								</form>
								
								<table class="responsive table table-bordered">
									<thead>
										<tr>
											<th>Fee Type</th>
											<th>Amount</th>
											<th>Paid</th>
											<th>Balance</th>
									</thead>
									<tbody>
									<?php echo $ListAllFee; ?>
									</tbody>
									<thead>
										<tr>
											<th>Total</th>
											<th><?php echo "$TotalFixedFeeAmount $CURRENCY"; ?></th>
											<th><?php echo "$TotalPaidFeeType $CURRENCY"; ?></th>
											<th><?php echo "$TotalFeeValue $CURRENCY"; ?></th>
										</tr>
									</thead>
								</table>
								
							</div>
						</div>
						<?php } ?>
					</div>
					<div class="span6">
						<?php if($SelectedAdmissionId!="" && $FormEntryNotAllowed!=1) { ?>
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Fee List to be Paid by <?php echo "$SelectedStudentName F/n $SelectedFatherName ($SelectedStudentMobile)"; ?></span>
								</h4>
								<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content" style="padding:5px;">
						<?php 
						if($_GET['AdmissionId']!="" && $ValidAdmissionId!=1)
						echo "<div class=\"alert alert-error\">This is not a valid student!!</div>";
						else
						{
						echo "<div id=\"showdata\">";
						$query3="select MasterEntryValue,feepayment.Amount,FeePaymentId from feepayment,fee,masterentry where 
							fee.FeeId=feepayment.FeeType and 
							fee.FeeType=masterentry.MasterEntryId and 
							Token='$Token' and
							FeePaymentStatus='Pending'  ";
						$check3=mysqli_query($CONNECTION,$query3);
						$count3=mysqli_num_rows($check3);
						?>
						<table id="links" class="responsive table table-bordered">
							<thead>
								<tr>
									<th>Fee Type</th>
									<th>Amount</th>
									<th><span class="icomoon-icon-cancel"></span></th>
							</thead>
							<tbody>
							<?php							
							while($row3 = mysqli_fetch_array($check3))
							{
							?>
							<tr id="<?php echo $row3['FeePaymentId']; ?>">
								<td><?php echo $row3['MasterEntryValue']; ?></td>
								<td><?php echo $row3['Amount']; ?></td>
								<td><a href="#" class="delete"><span class="icomoon-icon-cancel"></span></a></td>
							</tr>
							<?php
							}
							if($count3==0) {
							?>
							<tr><Td> &nbsp; </td><Td></td><Td></tD></tr>
							<?php } ?>
							</tbody>
						</table>
						<?php
						include("Data.php");
						echo "</div>";
						}
					?>
								<form class="form-horizontal" action="Action" name="FeePayment" id="FeePayment" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Account">Account</label>
												<div class="controls sel span8">   
												<select tabindex="11" name="Account" id="Account" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $LISTACCOUNT; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="DOP">Date of Payment</label>
												<input tabindex="12" class="span8" id="DOP" type="text" name="DOP" readonly />
											</div>
										</div>
									</div>	
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Remarks">Remarks</label>
												<div class="span8 controls-textarea">
												<textarea tabindex="13" id="Remarks" name="Remarks" class="span12"></textarea>
												</div>
											</div>
										</div>
									</div>	
									<input type="hidden" name="Token" value="<?php echo $Token; ?>" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="AdmissionId" value="<?php echo $SelectedAdmissionId; ?>" readonly>
									<input type="hidden" name="SectionId" value="<?php echo $SelectedSectionId; ?>" readonly>
									<input type="hidden" name="Action" value="PaymentConfirm" readonly>
									<?php $ButtonContent="Pay"; ActionButton($ButtonContent,14); ?>									
								</form>
							</div>
						</div>
						<?php } if($AdmissionId!='') { ?>
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Fee List Paid by <?php echo "$SelectedStudentName F/n $SelectedFatherName ($SelectedStudentMobile)"; ?></span>
								</h4>
								<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix" style="padding-bottom:2px;">
							<?php if($count4>0) { ?>
								<table class="responsive table table-bordered">
									<thead>
										<tr>
											<th>Receipt No</th>
											<th>Amount</th>
											<th>Date</th>
											<th>Remarks</th>
											<?php if($FormEntryNotAllowed!=1) { ?>
											<th><span class="icomoon-icon-cancel"></span></th>
											<?php } ?>
											<th><span class="icon-print"></span></th>
									</thead>
									<tbody>	
									<?php echo $ListPaidFee; ?>
									</tbody>
									<thead>
										<tr>
											<th>Total</th>
											<th><?php echo "$TotalPaid $CURRENCY"; ?></th>
											<th colspan=4></th>
									</thead>
								</table>
							<?php } else { ?>
							<div class="alert alert-error" style="margin:5px;">No fee paid by this student!!</div>
							<?php } ?>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
            </div>
        </div>
		
<script type="text/javascript">
	$(document).ready(function() {
		$("#AdmissionId").select2(); 
		$('#AdmissionId').select2({placeholder: "Select"}); 		
		$("#FeeType").select2(); 
		$('#FeeType').select2({placeholder: "Select"}); 	
		$("#Account").select2(); 
		$('#Account').select2({placeholder: "Select"}); 		
		if($('#DOP').length) {
		$('#DOP').datetimepicker({ dateFormat: 'dd-mm-yy' });
		}	
		
		$("#FeeSelectStudent").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				AdmissionId: {
					required: true,
				}
			},
			messages: {
				AdmissionId: {
					required: "Please select this!!",
				}
			}   
		});			
		$("#FeePayment").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				Account: {
					required: true,
				},
				DOP: {
					required: true,
				}
			},
			messages: {
				Account: {
					required: "Please select account!!",
				},
				DOP: {
					required: "Please select date & time!!",
				}
			}   
		});			
		$("#Fee").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				FeeType: {
					required: true,
				},
				Amount: {
					required: true,
				}
			},
			messages: {
				FeeType: {
					required: "Please select one!!",
				},
				Amount: {
					required: "Please enter amount!!",
				}
			}   
		});		
		$('#Fee').ajaxForm({
		  target: '#showdata',
		  success: function() {
			$('#showdata').fadeIn('slow');
			$('#Fee').each (function(){
			this.reset();
			});
			$('#FeeType').select2('open');
		  }
		});	
	});
</script>
<?php
include("Template/Footer.php");
?>