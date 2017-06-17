<?php
$PageName="ChangePassword";
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

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Change Password"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Change Password</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ChangePassword" id="ChangePassword" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="CurrentPassword">Current Password</label>
												<input class="span8" tabindex="1" id="CurrentPassword" type="password" name="CurrentPassword" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="NewPassword">New Password</label>
												<input class="span8" tabindex="2" id="NewPassword" type="password" name="NewPassword" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ConfirmPassword">Confirm Password</label>
												<input class="span8" tabindex="3" id="ConfirmPassword" type="password" name="ConfirmPassword" />
											</div>
										</div>
									</div>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<input type="hidden" name="Action" value="ChangePassword" readonly>
										<?php $ButtonContent="Change Password"; ActionButton($ButtonContent,4); ?>
								</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    	
<script type="text/javascript">
	$(document).ready(function() {
		$("input, textarea, select").not('.nostyle').uniform();
		$("#ChangePassword").validate({
			rules: {
				CurrentPassword: {
					required: true,
					minlength: 6
				},
				NewPassword: {
					required: true,
					minlength: 6
				},
				ConfirmPassword: {
					required: true,
					equalTo: "#NewPassword",
				}  
			},
			messages: {
				CurrentPassword: {
					required: "Please enter Current Password!!",
					minlength: "Minimum 6 characters required!!"
				},
				NewPassword: {
					required: "Please provide a New Password!!",
					minlength: "Minimum 6 characters required!!"
				},
				ConfirmPassword: {
					required: "Please Confirm your Password!!",
					equalTo: "Password did not match!!",
				}
			}   
		});
	});
</script>
<?php
include("Template/Footer.php");
?>