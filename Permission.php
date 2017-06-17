<?php
$PageName="Permission";
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
                <?php $BreadCumb="Permission"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				
				<?php
					$UserType=isset($_GET['UniqueId']) ? $_GET['UniqueId'] : '';
					$CountAdded=$count=0;
					$ActionPage="";
					if($UserType!="" && $_GET['Action']=="SetPermission")
					{
						$check=mysqli_query($CONNECTION,"select * from masterentry where MasterEntryName='UserType' and MasterEntryId='$UserType' ");
						$count=mysqli_num_rows($check);
						if($count>0)
						{
							$row=mysqli_fetch_array($check);
							$UserTypeName=$row['MasterEntryValue'];
							
							$ActionPage="Action";
							$ButtonContent="Save"; 
							$query77="select * from permission where UserType='$UserType' ";
							$check77=mysqli_query($CONNECTION,$query77);
							$count77=mysqli_num_rows($check77);
							if($count77>0)
							{
								$row77=mysqli_fetch_array($check77);
								$PermissionString=$row77['PermissionString'];
								$PermissionString=explode(",",$PermissionString);		
								$CountAdded=count($PermissionString);
							}
						}
						$AddButton="Update Permission <a href=Permission><span class=\"cut-icon-plus-2 addbutton\"> Add Permission </span></a>";
					}
					else
						$AddButton="Set Permission";
						
						$query66="select PageName,PageNameId from pagename 
							order by PageName ";
						$check66=mysqli_query($CONNECTION,$query66);
						$count66=mysqli_num_rows($check66);
						$ListOption=$ListAllPage="";
						if($count66>0)
						{
							while($row66=mysqli_fetch_array($check66))
							{
								$ListPage=$row66['PageName'];
								$ListPageNameId=$row66['PageNameId'];
								$Selected="";
								if($CountAdded>0)
								{
								foreach($PermissionString as $k)
								{
									if($k==$ListPageNameId)
									{
										$Selected="selected";
										break;
									}
								}
								}
								$ListOption.="<option value=$ListPageNameId $Selected>$ListPage</option>";
								$Edit="<a href=Permission/UpdatePage/$ListPageNameId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
								$ListAllPage.="<tr>
													<td>$ListPage</td>
													<td>$Edit</td>
												</tr>";
							}
						}
						
						if($ActionPage=="")
						{
						$ActionPage="ReportAction";
						$ButtonContent="Get"; 
						}
				?>
				
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>				
							<div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="<?php echo $ActionPage; ?>" name="SetPermission" id="SetPermission" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="normal">User Type </label> 
												<?php if($count>0 && $_GET['Action']=="SetPermission") { ?>
												<span class="span8"><b><?php echo $UserTypeName; ?></b></span> 
												<input type="hidden" name="UserType" value="<?php echo $UserType; ?>" readonly>
												<?php } else { ?>
												<div class="span8 controls sel">   
													<?php
													GetCategoryValue('UserType','UserType',$UserType,'','','','',1,'');
													?>
												</div> 
												<?php } ?>
											</div>
										</div> 
									</div>
									<?php if($count>0) { ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="checkboxes">Select Pages</label>
												<div class="span8 controls">   
													<select tabindex="2" name="PermissionSTR[]" id="PermissionSTR" class="nostyle" style="width:100%;" multiple="multiple">
													<?php
														echo $ListOption; 
													?>
													  </select>
												</div> 
											</div>
										</div> 
									</div>									
									<?php } ?>
											<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
											<input type="hidden" name="Action" value="SetPermission" readonly>
										   <?php ActionButton($ButtonContent,3); ?>
								</form>
							</div>
						</div>
					</div>
				</div>
				<?php if($USERTYPE=="Webmaster") { 

				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$Id=isset($_GET['UniqueId']) ? $_GET['UniqueId'] : '';
				$PageButtonContentSet=$PageAddButton=$Page=$Table=$count1=$TableButtonContentSet=$TableAddButton=$UpdatePageNameId=$UpdateTableId="";
				if($Id!="" && $Action=="UpdatePage")
				{
					$query1="select * from pagename where PageNameId='$Id'";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0)
					{
						$row1=mysqli_fetch_array($check1);
						$Page=$row1['PageName'];
						$PageButtonContentSet=1;
						$PageAddButton="Update <a href=Permission><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdatePageNameId=$Id;
					}
				}
				elseif($Id!="" && $Action=="UpdateTable")
				{
					$query1="select * from tablename where TableName='$Id'";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0)
					{
						$row1=mysqli_fetch_array($check1);
						$Table=$row1['TableName'];
						$TableButtonContentSet=1;
						$TableAddButton="Update <a href=Permission><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateTableId=$Id;
					}
				}				
				if($PageButtonContentSet!=1)
					$PageAddButton="Add Page";		
				if($TableButtonContentSet!=1)
					$TableAddButton="Add Table";
				?>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $PageAddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>				
							<div class="content noPad clearfix" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManagePage" id="ManagePage" method="Post">						
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="normal">Page Name </label> 
											<input tabindex="4" class="span8" id="Page" type="text" name="Page" value="<?php echo $Page; ?>" />
											</div>
										</div> 
									</div>
											<input type="hidden" name="Action" value="ManagePage" readonly>
											<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
											<?php if($count1>0) { echo "<input type=\"hidden\" name=\"PageNameId\" value=\"$UpdatePageNameId\" readonly>"; } ?>
										   <?php $ButtonContent="Save"; ActionButton($ButtonContent,5); ?>
								</form>
						<?php
						if($count66>0)
						{
						?>
							<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
							<thead>
								<tr>
									<th>Page Name</th>
									<th>Edit</th>
								</tr>
							</thead>
							<tbody>
								<?php echo $ListAllPage; ?>
							</tbody>
							</table>							
						<?php
						}
						?>
							</div>
						</div>
					</div>
                   <div class="span6">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $TableAddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>				
							<div class="content noPad clearfix" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageTable" id="ManageTable" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="normal">Table Name </label> 
											<input tabindex="6" class="span8" id="Table" type="text" name="Table" value="<?php echo $Table; ?>" />
											</div>
										</div> 
									</div>
											<input type="hidden" name="Action" value="ManageTable" readonly>
											<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
											<?php if($count1>0) { echo "<input type=\"hidden\" name=\"TableId\" value=\"$UpdateTableId\" readonly>"; } ?>
										   <?php $ButtonContent="Save"; ActionButton($ButtonContent,7); ?>
								</form>
						<?php
						$query66="select * from tablename";
						$check66=mysqli_query($CONNECTION,$query66);
						$count66=mysqli_num_rows($check66);
						$ListAllTable="";
						if($count66>0)
						{
							while($row66=mysqli_fetch_array($check66))
							{
								$ListTable=$row66['TableName'];
								$Edit="<a href=Permission/UpdateTable/$ListTable><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
								$ListAllTable.="<tr>
													<td>$ListTable</td>
													<td>$Edit</td>
												</tr>";
							}
						?>
							<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
							<thead>
								<tr>
									<th>Table Name</th>
									<th>Edit</th>
								</tr>
							</thead>
							<tbody>
								<?php echo $ListAllTable; ?>
							</tbody>
							</table>							
						<?php
						}
						?>
							</div>
						</div>
					</div>					
				</div>				
				<?php } ?>
				
            </div>
        </div>
	
<script type="text/javascript">
$(document).ready(function() {

	if($('table').hasClass('dynamicTable')){
		$('.dynamicTable').dataTable({
			"sPaginationType": "full_numbers",
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,
			"fnInitComplete": function(oSettings, json) {
		      $('.dataTables_filter>label>input').attr('id', 'search');
		    }
		});
	}

	$("#PermissionSTR").select2();
	$("#UserType").select2();
	$('#UserType').select2({placeholder: "Select"});
	$("input, textarea, select").not('.nostyle').uniform();
	$("#SetPermission").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			UserType: {
				required: true,
			}
		},
		messages: {
			UserType: {
				required: "Please enter this field!!",
			}
		}   
	});
	$("#ManagePage").validate({
		rules: {
			Page: {
				required: true,
			}
		},
		messages: {
			Page: {
				required: "Please enter this field!!",
			}
		}   
	});
	$("#ManageTable").validate({
		rules: {
			Table: {
				required: true,
			}
		},
		messages: {
			Table: {
				required: "Please enter this field!!",
			}
		}   
	});
});
</script>    
<?php
include("Template/Footer.php");
?>