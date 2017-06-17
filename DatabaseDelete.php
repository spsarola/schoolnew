<?php
include("Include.php");
$PageName="DatabaseDelete";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;

if($USERNAME!='masteruser' && $USERNAME!='webmaster')
header("location:DashBoard");

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

			<?php $BreadCumb="Database Delete"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>

                <div class="row-fluid">
                    <div class="span12">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Database Delete</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
							<?php if($USERTYPE=="MasterUser" || $USERTYPE=="Webmaster") { ?>
								<form class="form-horizontal" action="Action" name="Delete" id="Delete" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Password</label>
												<input tabindex="1" class="span8" id="Password" type="password" name="Password" placeholer="Password" />
											</div>
										</div>
									</div>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<input type="hidden" name="Action" value="Delete" readonly>
										<?php $ButtonContent="Delete All"; SetButton($ButtonContent,2); ?>
								</form>
							<?php } else { ?>
							<br><div class="alert alert-error">You cannot delete the database!!</div>
							<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	
     <script type="text/javascript">
        $(document).ready(function() {
            $("input, textarea, select").not('.nostyle').uniform();
            $("#Delete").validate({
                rules: {
                    Password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    Password: {
                        required: "Please enter Current Password!!",
                        minlength: "Minimum 6 characters required!!"
                    }
                }   
            });
        });
    </script>
	
<?php
include("Template/Footer.php");
?>