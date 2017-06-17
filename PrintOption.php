<?php
$PageName="PrintOption";
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
                <?php $BreadCumb="Print Option"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$UniqueId=isset($_GET['UniqueId']) ? $_GET['UniqueId'] : '';
				$ButtonContentSet=$ButtonContent=$AddButton=$Width=$PrintCategory=$PrintCategoryName=$HeaderId=$FooterId=$count1="";
				if($UniqueId!="")
				{
					$query1="select * from printoption,masterentry where 
						PrintOptionId='$UniqueId' and 
						PrintOptionStatus='Active' and
						printoption.PrintCategory=masterentry.MasterEntryId ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$PrintCategory=$row1['PrintCategory'];
						$PrintCategoryName=$row1['MasterEntryValue'];
						$HeaderId=$row1['HeaderId'];
						$FooterId=$row1['FooterId'];
						$Width=$row1['Width'];
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=PrintOption><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateId=$UniqueId;
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add Print Option";
				}
				
				$query2="select HeaderTitle,HeaderId,MasterEntryValue from header,masterentry where
					header.HRType=masterentry.MasterEntryId ";
				$check2=mysqli_query($CONNECTION,$query2);
				$Selected=$ListAllHeader=$ListAllFooter="";
				while($row2=mysqli_fetch_array($check2))
				{
					$ComboHeaderTitle=$row2['HeaderTitle'];
					$ComboHeaderId=$row2['HeaderId'];
					$HRType=$row2['MasterEntryValue'];
					if($ComboHeaderId==$HeaderId || $ComboHeaderId==$FooterId)
					$Selected="selected";
					else
					$Selected="";
					if($HRType=="Header")
					$ListAllHeader.="<option value=\"$ComboHeaderId\" $Selected>$ComboHeaderTitle</option>";
					if($HRType=="Footer")
					$ListAllFooter.="<option value=\"$ComboHeaderId\" $Selected>$ComboHeaderTitle</option>";
				}
				?>
				
                <div class="row-fluid">
                    <div class="span3">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize tip" title="Minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManagePrintOption" id="ManagePrintOption" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="PrintCategory">Print Category</label>
												<div class="span8 controls sel">   
												<?php
												GetCategoryValue('PrintCategory','PrintCategory',$PrintCategory,'','','','',1,'');
												?>
												</div> 
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Width">Width <span class="help-block">In <?php echo $PRINTUNIT; ?></span></label>
												<input tabindex="2" class="span8 tip" type="number" name="Width" id="Width" value="<?php echo $Width; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="HeaderId">Header</label>
												<div class="controls sel span8">   
												<select tabindex="3" name="HeaderId" id="HeaderId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListAllHeader; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="FooterId">Footer</label>
												<div class="controls sel span8">   
												<select tabindex="3" name="FooterId" id="FooterId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListAllFooter; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="ManagePrintOption" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									   <?php if($count1>0) { ?>
									   <input type="hidden" name="PrintOptionId" value="<?php echo $UpdateId; ?>" readonly>											   
									   <?php } ?>
									<?php ActionButton($ButtonContent,4); ?>
								</form>
                            </div>
                        </div>
                    </div>
					
					<div class="span9">
					<?php
						$query02="select HeaderId,HeaderTitle from header ";
						$check02=mysqli_query($CONNECTION,$query02);
						while($row02=mysqli_fetch_array($check02))
						{
							$HeaderIdArray[]=$row02['HeaderId'];
							$HeaderTitleArray[]=$row02['HeaderTitle'];
						}
						
						$query="select * from printoption,masterentry where 
						printoption.PrintCategory=masterentry.MasterEntryId and 
						PrintOptionStatus='Active' ";
						$result=mysqli_query($CONNECTION,$query);
						$count=mysqli_num_rows($result);
						$DATA=array();
						$QA=array();
						$PrintList3=$PrintList="";
						while($row=mysqli_fetch_array($result))
						{
							$ListPrintOptionId=$row['PrintOptionId'];
							$ListWidth=$row['Width'];	
							$ListHeaderId=$row['HeaderId'];	
							if($ListHeaderId!="" && $HeaderIdArray!="")
							{
								$HeaderSearchIndex=array_search($ListHeaderId,$HeaderIdArray);
								$HeaderTitle=$HeaderTitleArray[$HeaderSearchIndex];
							}
							else
							$HeaderTitle="";
							
							$ListFooterId=$row['FooterId'];	
							if($ListFooterId!="" && $HeaderIdArray!="")
							{
								$FooterSearchIndex=array_search($ListFooterId,$HeaderIdArray);
								$FooterTitle=$HeaderTitleArray[$FooterSearchIndex];
							}
							else
							$FooterTitle="";
							
							$ListPrintCategoryName=$row['MasterEntryValue'];	
							$Edit="<a href=PrintOption/Update/$ListPrintOptionId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
							$Delete="<a href=DeletePopUp/DeletePrintOption/$ListPrintOptionId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
							$PrintList3.="<tr class=\"odd gradeX\">
									<td>$HeaderTitle</td>
									<td>$FooterTitle</td>
									<td>$ListPrintCategoryName</td>
									<td></td>
								</tr>";
							$ListWidth.=" $PRINTUNIT";
							$QA[]=array($HeaderTitle,$FooterTitle,$ListPrintCategoryName,$ListWidth,$Edit,$Delete);
						}
						$DATA['aaData']=$QA;
						$fp = fopen('plugins/Data/data1.txt', 'w');
						fwrite($fp, json_encode($DATA));
						fclose($fp);
						?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Print Option List</span>
									<?php if($count>0) { ?>
									<div class="PrintClass">
										<form method=post action=/Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintOptionList" readonly>
										<input type="hidden" name="HeadingName" value="PrintOptionHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Option List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
								<a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
							<?php
								$PrintList1="<table id=\"PrintOptionTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Header</th>
											<th>Footer</th>
											<th>Print Category</th>
											<th>Width</th>";
											echo $PrintList1;
											echo "<th><span class=\"icon-edit tip\" title=\"Update\"></span></th>
											<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>";
										$PrintList2="</tr>
									</thead>
									<tbody>";
									echo $PrintList2;
									$PrintList4="</tbody>
								</table>";
									echo $PrintList4;
									$PrintList="$PrintList1 $PrintList2 $PrintList3 $PrintList";
									$_SESSION['PrintOptionList']=$PrintList;
									$PrintHeading="Showing List of Print Option";
									$_SESSION['PrintOptionHeading']=$PrintHeading;
							?>
							</div>
						</div>
					</div>
                </div>				
				
            </div>
        </div>
<script type="text/javascript">
$(document).ready(function() {
	$("#HeaderId").select2(); 
	$('#HeaderId').select2({placeholder: "Select"}); 		
	$("#FooterId").select2(); 
	$('#FooterId').select2({placeholder: "Select"}); 	
	$("#PrintCategory").select2(); 
	$('#PrintCategory').select2({placeholder: "Select"}); 		
	$('#PrintOptionTable').dataTable({
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
	$("#ManagePrintOption").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			PrintCategory: {
				required: true,
			},
			Width: {
				required: true,
			}
		},
		messages: {
			PrintCategory: {
				required: "Please select this!!",
			},
			Width: {
				required: "Please enter this!!",
			}
		}   
	});
	$("#DeletePrintOption").validate({
		rules: {
			Password : {
				required: true,
			}
		},
		messages: {
			Password : {
				required: "Please enter Password!!",
			}
		}   
	});
});
</script>   
<?php
include("Template/Footer.php");
?>