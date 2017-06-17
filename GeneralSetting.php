<?php
$PageName="GeneralSetting";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
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
$query="select * from generalsetting ";
$check=mysqli_query($CONNECTION,$query);
$count=mysqli_num_rows($check);
$BackUpPath=$SchoolName=$DOL=$AffiliationNo=$RegistrationNo=$AffiliatedBy=$Board=$DateOfEstablishment=$Fax=$Landline=$Email=$AlternateMobile=$Mobile=$Country=$State=$PIN=$District=$City=$SchoolAddress=$SchoolStartDate="";
if($count>0)
{
	$row=mysqli_fetch_array($check);
	$BackUpPath=$row['BackUpPath'];
	$BackUpPath=str_replace("\\","\\\\", $BackUpPath);
	$SchoolName=$row['SchoolName'];
	$SchoolStartDate=date("d-m-Y",$row['SchoolStartDate']);
	$SchoolAddress=$row['SchoolAddress'];
	$City=$row['City'];
	$District=$row['District'];
	$PIN=$row['PIN'];
	$State=$row['State'];
	$Country=$row['Country'];
	$Mobile=$row['Mobile'];
	$AlternateMobile=$row['AlternateMobile'];
	$Email=$row['Email'];
	$Landline=$row['Landline'];
	$Fax=$row['Fax'];
	$DateOfEstablishment=$row['DateOfEstablishment'];
	if($DateOfEstablishment!="")
	$DateOfEstablishment=date("d-m-Y",$row['DateOfEstablishment']);
	$Board=$row['Board'];
	$AffiliatedBy=$row['AffiliatedBy'];
	$RegistrationNo=$row['RegistrationNo'];
	$AffiliationNo=$row['AffiliationNo'];
	$DOL=$row['DOL'];
}
?>

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="General Setting"; BreadCumb($BreadCumb); ?>
				
				<?php DisplayNotification(); ?>
				
                <div class="row-fluid">
                    <div class="span12">
						<div class="alert alert-info">Software start date once set cannot be updated any more!!</div>
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>General Setting</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="width:98%; margin-bottom:10px; float:left; clear:both; ">
								<form class="form-horizontal" action="Action" name="GeneralSetting" id="GeneralSetting" method="Post">
								<div class="row-fluid">
									<div class="span12">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span2 mandatory" for="SchoolName">School Name</label>
													<input class="span10" tabindex="1" id="SchoolName" type="text" name="SchoolName" value="<?php echo $SchoolName; ?>" />
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span4">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4 mandatory" for="SchoolStartDate">Software Starting Date</label>
													<input class="span8" tabindex="2" readonly id="SchoolStartDate" type="text" name="SchoolStartDate" value="<?php echo $SchoolStartDate; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="SchoolAddress">Address</label>
													<div class="span8 controls-textarea">   
													<textarea tabindex="4" class="span12 tip" title="Mandatory : School Address" name="SchoolAddress" id="SchoolAddress"><?php echo $SchoolAddress; ?></textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="City">City</label>
													<input class="span8"  tabindex="5" id="City" type="text" name="City" value="<?php echo $City; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="District">District</label>
													<input class="span8" tabindex="6" id="District" type="text" name="District" value="<?php echo $District; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="PIN">PIN</label>
													<input class="span8" tabindex="7" id="PIN" type="text" name="PIN" value="<?php echo $PIN; ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="State">State</label>
													<input class="span8" tabindex="8" id="State" type="text" name="State" value="<?php echo $State; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Country">Country</label>
													<input class="span8" tabindex="9" id="Country" type="text" name="Country" value="<?php echo $Country; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Mobile">Mobile</label>
													<input class="span8" tabindex="10" id="Mobile" type="text" name="Mobile" value="<?php echo $Mobile; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="AlternateMobile">Alternate Mobile</label>
													<input class="span8" tabindex="11" id="AlternateMobile" type="text" name="AlternateMobile" value="<?php echo $AlternateMobile; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Landline">Landline</label>
													<input class="span8" tabindex="12" id="Landline" type="text" name="Landline" value="<?php echo $Landline; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Email">Email</label>
													<input class="span8" tabindex="13" id="Email" type="email" name="Email" value="<?php echo $Email; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Fax">Fax</label>
													<input class="span8" tabindex="14" id="Fax" type="text" name="Fax" value="<?php echo $Fax; ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Board">Board</label>
													<input class="span8" tabindex="15" id="Board" type="text" name="Board" value="<?php echo $Board; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="AffiliatedBy">Affiliated By</label>
													<input class="span8" tabindex="16" id="AffiliatedBy" type="text" name="AffiliatedBy" value="<?php echo $AffiliatedBy; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="RegistrationNo">Registration No</label>
													<input class="span8" tabindex="17" id="RegistrationNo" type="text" name="RegistrationNo" value="<?php echo $RegistrationNo; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="AffiliationNo">Affiliation No</label>
													<input class="span8" tabindex="18" id="AffiliationNo" type="text" name="AffiliationNo" value="<?php echo $AffiliationNo; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="DateOfEstablishment">Date of Establishment</label>
													<input class="span8" tabindex="19" readonly id="DateOfEstablishment" type="text" name="DateOfEstablishment" value="<?php echo $DateOfEstablishment; ?>" />
												</div>
											</div>
										</div>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
											<input type="hidden" name="Action" value="GeneralSetting" readonly>
										<?php $ButtonContent="Save"; ActionButton($ButtonContent,20); ?>
									</div>
								</div>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
<script type="text/javascript">
	$(document).ready(function() {
		if($('#DateOfEstablishment').length) {
		$('#DateOfEstablishment').datepicker({ yearRange: "-180:+0", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		if($('#SchoolStartDate').length) {
		$('#SchoolStartDate').datepicker({ yearRange: "-10:+0", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		$("input, textarea, select").not('.nostyle').uniform();
		$("#GeneralSetting").validate({
			rules: {
				SchoolStartDate: {
					required: true,
				},
				SchoolName: {
					required: true,
				},
				Mobile: {
					remote: "RemoteValidation?Action=MobileValidation&Id=Mobile"
				},
				AlternateMobile: {
					remote: "RemoteValidation?Action=MobileValidation&Id=AlternateMobile"
				},
				Landline: {
					remote: "RemoteValidation?Action=LandlineValidation&Id=Landline"
				}
			},
			messages: {
				SchoolStartDate: {
					required: "Please select this!!",
				},
				SchoolName: {
					required: "Please enter this!!",
				},
				Mobile: {
					remote: jQuery.format("<?php echo $MOBILENUMBERDIGIT; ?> Digit mobile number!!")
				},
				AlternateMobile: {
					remote: jQuery.format("<?php echo $MOBILENUMBERDIGIT; ?> Digit mobile number!!")
				},
				Landline: {
					remote: jQuery.format("11 Digit landline number!!")
				}
			}   
		});
	});
</script>
<?php
include("Template/Footer.php");
?>