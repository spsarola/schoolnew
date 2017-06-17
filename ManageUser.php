<?php
$PageName="ManageUser";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$CalendarRequired=1;
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
                <?php $BreadCumb="Manage User"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$UserId=isset($_GET['UserId']) ? $_GET['UserId'] : '';
				$ButtonContentSet=$Readonly=$ButtonContent=$UserType=$SId=$Username=$count1="";
				if($UserId!="")
				{
					$query1="select * from user where UserId='$UserId' and UserId!='1' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$Username=$row1['Username'];
						$SId=$row1['StaffId'];
						$UserType=$row1['UserType'];
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=ManageUser><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateUserId=$UserId;
						$Readonly="readonly";
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add User";
				}
				
				$query="select * from user,masterentry,staff where 
				user.UserType=masterentry.MasterEntryId and
				user.StaffId=staff.StaffId and
				UserType!='0' 
				order by Username";
				$result=mysqli_query($CONNECTION,$query);
				$count=mysqli_num_rows($result);
				$DATA=array();
				$QA=array();
				while($row=mysqli_fetch_array($result))
				{
					$ListUsername=$row['Username'];	
					$ListUserId=$row['UserId'];	
					$ListName=$row['StaffName'];	
					$ListPassword=$row['Password'];	
					$ListUserType=$row['MasterEntryValue'];
					$Edit="<a href=ManageUser/Update/$ListUserId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
					$Note="<a href=Note/ManageUser/$ListUserId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-clipboard-3\"></span></a>";
					$QA[]=array($ListUserType,$ListUsername,$ListPassword,$ListName,$Edit,$Note);
				}
				$DATA['aaData']=$QA;
				$fp = fopen('plugins/Data/data1.txt', 'w');
				fwrite($fp, json_encode($DATA));
				fclose($fp);	
				?>				
				
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
								<form class="form-horizontal" action="Action" name="ManageUser" id="ManageUser" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="normal">User Type</label> 
												<div class="span8 controls sel">   
													<?php
													GetCategoryValue('UserType','UserType',$UserType,'','','','',1,'');
													?>
												</div> 
											</div>
										</div> 
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="normal">Select Staff</label> 
												<div class="span8 controls sel">   
													<select tabindex="2" class="nostyle" name="StaffId" id="StaffId" style="width:100%;">
													<option></option>
													<?php
													$query1="select StaffName,StaffId,StaffMobile,MasterEntryValue from staff,masterentry where 
													StaffStatus='Active' and
													staff.StaffPosition=masterentry.MasterEntryId 
													order by MasterEntryValue,StaffName";
													$check1=mysqli_query($CONNECTION,$query1);
													while($row1=mysqli_fetch_array($check1))
													{
														$StaffName=$row1['StaffName'];
														$StaffId=$row1['StaffId'];
														$StaffMobile=$row1['StaffMobile'];
														$StaffPosition=$row1['MasterEntryValue'];
														if($StaffId==$SId)
														$Selected="selected";
														else
														$Selected="";
														echo "<option value=\"$StaffId\" $Selected>$StaffName ($StaffMobile)</option>";
													}
													?>
													</select>
												</div> 
											</div>
										</div> 
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Username</label>
												<input tabindex="3" class="span8" id="Username" type="text" name="Username" value="<?php echo $Username; ?>" <?php echo $Readonly; ?> />
											</div>
										</div>
									</div>
									<?php if($count1>0) { ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Reset Password</label>
												<input tabindex="4" class="styled" id="ResetPassword" type="checkbox" name="ResetPassword" value="Yes" />
											</div>
										</div>
									</div>	
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Password</label>
												<input tabindex="5" class="span8" id="ResetPasswordValue" type="Password" name="ResetPasswordValue" />
											</div>
										</div>
									</div>								
									<?php } else { ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Password</label>
												<input tabindex="5" class="span8" id="Password" type="Password" name="Password" />
											</div>
										</div>
									</div>
									<?php } ?>
										<input type="hidden" name="Action" value="ManageUser" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<?php if($count1>0) { ?>
										<input type="hidden" name="UserId" value="<?php echo $UpdateUserId; ?>" readonly>									
									<?php } ?>
									<?php ActionButton($ButtonContent,6); ?>
								</form>
                            </div>
                        </div>
                    </div>
					
					<div class="span8">
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>User List</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<table id="UserTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>User Type</th>
											<th>Username</th>
											<th>Password</th>
											<th>Name</th>
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

	$('#UserTable').dataTable({
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
		
	$("input, textarea, select").not('.nostyle').uniform();
	$("#UserType").select2();
	$('#UserType').select2({placeholder: "Select"});
	$("#StaffId").select2();
	$('#StaffId').select2({placeholder: "Select"});
	$("#ManageUser").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			UserType: {
				required: true,
			},
			Username: {
				required: true,
				minlength: 4
			},
			Password: {
				required: true,
				minlength: 6
			},
			StaffId: {
				required: true,
			},
			ResetPasswordValue: {
				required: "#ResetPassword:checked",
				minlength: 6
			}
		},
		messages: {
			UserType: {
				required: "Please select this!!",
			},
			Username: {
				required: "Please enter this!!",
				minlength: "Minimum 4 characters required!!"
			},
			Password: {
				required: "Please enter this!!",
				minlength: "Minimum 6 characters required!!"
			},
			StaffId: {
				required: "Please select this!!",
			},
			ResetPasswordValue: {
				required: "Please enter this!!",
				minlength: "Minimum 6 characters required!!"
			}
		}   
	});
});
</script>    
<?php
include("Template/Footer.php");
?>