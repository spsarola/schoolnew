<?php
$PageName="ManageHeaderAndFooter";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
$EditorRequired=1;
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
                <?php $BreadCumb="Manage Header & Footer"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$HId=isset($_GET['HeaderId']) ? $_GET['HeaderId'] : '';
				$ButtonContentSet=$ButtonContent=$AddButton=$HRType=$HeaderTitle=$HeaderContent=$count1="";
				if($HId!="")
				{
					$query1="select * from header where HeaderId='$HId' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$HRType=$row1['HRType'];
						$HeaderTitle=$row1['HeaderTitle'];
						$HeaderContent=$row1['HeaderContent'];
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=ManageHeader><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateHeaderId=$HId;
					}
					elseif($count1>0 && $Action=="Delete")
					{
						$row1=mysqli_fetch_array($check1);
						$DeleteHRType=$row1['HRType'];	
						$DeleteHeaderName=$row1['HeaderTitle'];	
					}
					elseif($count1>0 && $Action=="MakeDefault")
					{
						$row1=mysqli_fetch_array($check1);
						$HRType=$row1['HRType'];
						mysqli_query($CONNECTION,"update header set HeaderDefault='' where HRType='$HRType'");
						mysqli_query($CONNECTION,"update header set HeaderDefault='Yes' where HeaderId='$HId' and HRType='$HRType' ");
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add";
				}
				?>
				
                <div class="row-fluid">
                    <div class="span5">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>				
							<div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageHeader" id="ManageHeader" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Type</label>
												<div class="controls sel span8">   
												<?php GetCategoryValue('HeaderFooter','HRType',$HRType,'','','','',1,''); ?>
												</div>												
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Title</label>
												<input tabindex="2" class="span8" type="text" name="HeaderTitle" id="HeaderTitle" value="<?php echo $HeaderTitle; ?>"  />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<div class="span12 controls-textarea">
												<textarea tabindex="3" class="ckeditor" id="HeaderContent" name="HeaderContent" class="span12"><?php echo $HeaderContent; ?></textarea>
												</div>
											</div>
										</div>
									</div>
										<?php if($count1>0) { echo "<input type=\"hidden\" name=\"HeaderId\" value=\"$UpdateHeaderId\" readonly>"; } ?>
											<input type="hidden" name="Action" value="ManageHeader" readonly>
											<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										   <?php ActionButton($ButtonContent,4); ?>
								</form>
							</div>
						</div>
					</div>
					
					<div class="span7">
					<?php
					if($Action=="Delete" && $count1>0)
					{
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Delete Header "<?php echo "$DeleteHRType $DeleteHeaderName"; ?>" ??</span>
								</h4>
								<br><a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<form class="form-horizontal" action="/ActionDelete" name="DeleteHeader" id="DeleteHeader" method="Post">
									<br><div class="alert alert-error">You cannot recover it after deletion!!</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Password</label>
												<input tabindex="1" class="span8" type="password" name="Password" id="Password" placeholder="Password" />
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="DeleteHeader" readonly />
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="HeaderId" value="<?php echo $HId; ?>" readonly />
									<?php SetDeleteButton(1); ?>
								</form>
							</div>
						</div>
					<?php
					}
					$query="select * from header,masterentry where header.HRType=masterentry.MasterEntryId and MasterEntryName='HeaderFooter' order by HeaderTitle";
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					$DATA=array();
					$QA=array();
					while($row=mysqli_fetch_array($result))
					{
						$HeaderId=$row['HeaderId'];	
						$HRType=$row['MasterEntryValue'];
						$HeaderTitle=$row['HeaderTitle'];
						$HeaderContent=$row['HeaderContent'];
						$HeaderDefault=$row['HeaderDefault'];
						if($HeaderDefault=="Yes")
						$Default="<span class=\"badge badge-success\">Default</span>";
						else
						$Default="<a href=ManageHeaderAndFooter/MakeDefault/$HeaderId><span class=\"badge badge-important\">Make Default</span></a>";
						$Edit="<a href=ManageHeaderAndFooter/Update/$HeaderId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
						$ActionConfirmMessage="Are you sure want to delete?";
						$ActionConfirm=ActionConfirm($ActionConfirmMessage);
						$Delete="<a href=DeletePopUp/DeleteHeaderFooter/$HeaderId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\" icomoon-icon-cancel \"></span></a>";
						$HeaderTitle.=" <br>$Default";
						$QA[]=array($HRType,$HeaderTitle,$HeaderContent,$Edit,$Delete);
					}
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);	
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Header & Footer List</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
							<?php
								$PrintCall1="<table id=\"HeaderTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Type</th>
											<th>Title</th>
											<th>Content</th>";
											echo $PrintCall1;
											echo "<th><span class=\"icon-edit tip\" title=\"Update\"></span></th>
											<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>";
											$PrintCall2="</tr>
									</thead>
									<tbody>";
									echo $PrintCall2;
									$PrintCall4="</tbody>
								</table>";
								echo $PrintCall4;
							?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
<script type="text/javascript">
$(document).ready(function() {
	$("#HRType").select2();
	$('#HRType').select2({placeholder: "Select"}); 	

	$('#HeaderTable').dataTable({
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
	$("#ManageHeader").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			HeaderTitle: {
				required: true,
			},
			HRType: {
				required: true,
			}
		},
		messages: {
			HeaderTitle: {
				required: "Please enter this!!",
			},
			HRType: {
				required: "Please select this!!",
			}
		},
	});
	$("#DeleteHeader").validate({
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