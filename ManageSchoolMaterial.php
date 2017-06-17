<?php
$PageName="ManageSchoolMaterial";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
include("Include.php");
IsLoggedIn();
$MaterialType=isset($_GET['MaterialType']) ? $_GET['MaterialType'] : '';
if($MaterialType=="" || ($MaterialType!="Books" && $MaterialType!="Uniform" && $MaterialType!="Other") )
$MaterialType="Books";
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
                <?php  $SchoolMaterialCategory="<a href=ManageSchoolMaterial/Books><div class=\"badge badge-important\">Books</div></a>
										<a href=ManageSchoolMaterial/Uniform><div class=\"badge badge-info\">Uniform</div></a>
										<a href=ManageSchoolMaterial/Other><div class=\"badge badge-success\">Other</div></a>";
				$BreadCumb="Manage School Material $SchoolMaterialCategory"; BreadCumb($BreadCumb);  ?>
				<?php DisplayNotification(); ?>

				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$MaterialId=isset($_GET['UniqueId']) ? $_GET['UniqueId'] : '';
				$ButtonContentSet=$ButtonContent=$AddButton=$Session=$ClassId=$Name=$SellingPrice=$BranchPrice=$Quantity=$count1="";
			
				if($MaterialId!="")
				{
					$query1="select * from schoolmaterial where SchoolMaterialId='$MaterialId' and SchoolMaterialType='$MaterialType' and SchoolMaterialStatus='Active' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$Session=$row1['Session'];
						$ClassId=$row1['ClassId'];
						$Quantity=round($row1['Quantity'],2);
						$BranchPrice=round($row1['BranchPrice'],2);
						$SellingPrice=round($row1['SellingPrice'],2);
						$Name=$row1['Name'];
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=ManageSchoolMaterial/$MaterialType><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateMaterialId=$MaterialId;
					}
					elseif($count1>0 && $Action=="Delete")
					{
						$row1=mysqli_fetch_array($check1);
						$DeleteName=$row1['Name'];	
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add $MaterialType";
				}
				
				
				$query3="select ClassName,ClassId from class where 
					class.ClassStatus='Active' and
					class.Session='$CURRENTSESSION' order by ClassName";
				$check3=mysqli_query($CONNECTION,$query3);
				$ListAllClass=$SelectedClass="";
				while($row3=mysqli_fetch_array($check3))
				{
					$ComboCurrentClassName=$row3['ClassName'];
					$ComboCurrentClassId=$row3['ClassId'];
					if($ClassId==$ComboCurrentClassId)
					{
						$SelectedClass="selected";
						$ValidSectionId=1;
					}
					else
					$SelectedClass="";
					$ListAllClass.="<option value=\"$ComboCurrentClassId\" $SelectedClass>$ComboCurrentClassName</option>";
				}				
				
				if($MaterialType=="Books")
				{
				$query="select SchoolMaterialId,schoolmaterial.Session,Name,ClassName,Quantity,BranchPrice,SellingPrice from 
					schoolmaterial,class where 
					schoolmaterial.ClassId=class.ClassId and 
					SchoolMaterialStatus='Active' and 
					SchoolMaterialType='Books' and
					schoolmaterial.Session='$CURRENTSESSION' order by Name";
				}
				else
				{
				$query="select SchoolMaterialId,Name,Quantity,BranchPrice,SellingPrice from schoolmaterial where 
						SchoolMaterialStatus='Active' and
						SchoolMaterialType='$MaterialType' order by Name";
				}
				$result=mysqli_query($CONNECTION,$query);
				$count=mysqli_num_rows($result);
				$DATA=array();
				$QA=array();
				$ListSchoolMaterialList=$PrintSchoolMaterialList5=$ListClass=$ListClassName=$ListSession="";
				while($row=mysqli_fetch_array($result))
				{
					if($MaterialType=="Books")
					{
						$ListClassName=$row['ClassName'];	
						$ListSession=$row['Session'];	
					}
					$ListQuantity=round($row['Quantity'],2);	
					$ListBranchPrice=round($row['BranchPrice'],2);	
					$ListSellingPrice=round($row['SellingPrice'],2);	
					$ListName=$row['Name'];	
					$ListSchoolMaterialId=$row['SchoolMaterialId'];	
					$Edit="<a href=ManageSchoolMaterial/$MaterialType/Update/$ListSchoolMaterialId><span class=\"icon-edit\"></span></a>";
					$Delete="<a href=ManageSchoolMaterial/$MaterialType/Delete/$ListSchoolMaterialId><span class=\"icomoon-icon-cancel\"></span></a>";
				
					$ListSchoolMaterialList.="<tr>";
					$PrintSchoolMaterialList5.="<tr>";
					if($MaterialType=="Books")
					{
					$PrintSchoolMaterialList5.="<td>$ListSession</td>
					<td>$ListClass</td><td>$ListName</td>
					<td>$ListQuantity</td>
					<td>$ListBranchPrice</td>
					<td>$ListSellingPrice</td>
					</tr>";
					$QA[]=array($ListSession,$ListClassName,$ListName,$ListQuantity,$ListBranchPrice,$ListSellingPrice,$Edit,$Delete);
					}
					else
					{
					$PrintSchoolMaterialList5.="<td>$ListName</td>
					<td>$ListQuantity</td>
					<td>$ListBranchPrice</td>
					<td>$ListSellingPrice</td>
					</tr>";
					$QA[]=array($ListName,$ListQuantity,$ListBranchPrice,$ListSellingPrice,$Edit,$Delete);
					}
				}
				$DATA['aaData']=$QA;
				$fp = fopen('plugins/Data/data1.txt', 'w');
				fwrite($fp, json_encode($DATA));
				fclose($fp);
				?>				

                <div class="row-fluid">
                    <div class="span3">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageSchoolMaterial" id="ManageSchoolMaterial" method="Post">
								<?php 
								if($MaterialType=="Books")
								{
								?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Class</label>
												<div class="span8 controls sel">
												<select tabindex="1" name="ClassId" id="ClassId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListAllClass; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
								<?php
								}
								?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Name</label>
												<input tabindex="2" class="span8" id="Name" type="text" name="Name" value="<?php echo $Name; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Branch Price</label>
												<input  tabindex="3" class="span8" id="BranchPrice" type="text" name="BranchPrice" value="<?php echo $BranchPrice; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Selling Price</label>
												<input tabindex="4"class="span8" id="SellingPrice" type="text" name="SellingPrice" value="<?php echo $SellingPrice; ?>" />
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageSchoolMaterial" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<input type="hidden" name="MaterialType" value="<?php echo $MaterialType; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="SchoolMaterialId" value="<?php echo $UpdateMaterialId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ButtonContent,5); ?>
								</form>
                            </div>
                        </div>
                    </div>
					
					<div class="span9">
					<?php
					if($Action=="Delete" && $count1>0)
					{
						$SearchString="#$MaterialId#";
						$query2="select PurchaseListId from purchaselist where MaterialType='$MaterialType' and UniqueId='$MaterialId' UNION ALL
							select IssueId from issue where MaterialType='$MaterialType' and Material like ' % $SearchString % ' and IssueStatus='Active' ";
						$check2=mysqli_query($CONNECTION,$query2);
						$count2=mysqli_num_rows($check2);
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Delete Material "<?php echo $DeleteName; ?>" ??</span>
								</h4>
								<a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<?php if($count2==0) { $TabIndex=500; ?>
								<form class="form-horizontal" action="ActionDelete" name="DeleteSchoolMaterialForm" id="DeleteSchoolMaterialForm" method="Post">
									<br><div class="alert alert-error">You cannot recover it after deletion!!</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Password</label>
												<input class="span8" type="password" name="Password" id="Password" placeholder="Password" />
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="DeleteSchoolMaterial" readonly />
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="SchoolMaterialId" value="<?php echo $MaterialId; ?>" readonly />
									<?php SetDeleteButton($TabIndex); ?>
								</form>
								<?php } else { ?>
								<br><div class="alert alert-error">This material is associated with Purchase & Issue!! Please delete them first!!</div>
								<?php } ?>
							</div>
						</div>
					
					<?php
					}
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span><?php echo $MaterialType; ?> List</span>
									<?php if($count>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintSchoolMaterialList" readonly>
										<input type="hidden" name="HeadingName" value="PrintSchoolMaterialHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print School Material List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
                                <a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<?php
								$PrintSchoolMaterialList2="";
								$PrintSchoolMaterialList1="<table id=\"SchoolMaterialTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>";
										echo $PrintSchoolMaterialList1;
											if($MaterialType=="Books") {
											$PrintSchoolMaterialList2="<th>Session</th>
											<th>Class</th>";
											echo $PrintSchoolMaterialList2;
											}
											$PrintSchoolMaterialList3="<th>Name</th>
											<th>Quantity</th>
											<th>Branch Price</th>
											<th>Selling Price</th>";
											echo $PrintSchoolMaterialList3;
											echo "<th><span class=\"icon-edit\"></span></th>
											<th><span class=\"icomoon-icon-cancel\"></span></th>";
										$PrintSchoolMaterialList4="</tr>
									</thead>
									<tbody>";
									echo $PrintSchoolMaterialList4;
									$PrintSchoolMaterialList6="</tbody>
								</table>";
								echo $PrintSchoolMaterialList6;
								$PrintSchoolMaterialList="$PrintSchoolMaterialList1 $PrintSchoolMaterialList2 $PrintSchoolMaterialList3 $PrintSchoolMaterialList4 $PrintSchoolMaterialList5 $PrintSchoolMaterialList6";
								$_SESSION['PrintSchoolMaterialList']=$PrintSchoolMaterialList;
								$PrintSchoolMaterialHeading="Showing List of School Material \"$MaterialType\" ";
								$_SESSION['PrintSchoolMaterialHeading']=$PrintSchoolMaterialHeading;
								?>
							</div>
						</div>
					</div>					
					
				</div>
				
            </div>
        </div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#SchoolMaterialTable').dataTable({
			"sPaginationType": "two_button",
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,  
			"bProcessing": true,
			"bDeferRender": true,
			"sAjaxSource": "plugins/Data/data1.txt",
			"fnInitComplete": function(oSettings, json) {
			  $('.dataTables_filter>label>input').attr('id', 'search');
			}
		});	
		$("#ClassId").select2();
		$('#ClassId').select2({placeholder: "Select"});
		$("input, textarea, select").not('.nostyle').uniform();
		$("#ManageSchoolMaterial").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				ClassId: {
					required: true,
				},
				Name: {
					required: true,
				},
				Session: {
					required: true,
				},
				BranchPrice: {
					required: true,
					remote: "RemoteValidation?Action=IsAmountWithZero&Id=BranchPrice"
				},
				SellingPrice: {
					required: true,
					remote: "RemoteValidation?Action=IsAmountWithZero&Id=SellingPrice"
				}
			},
			messages: {
				ClassId: {
					required: "Please select this!!",
				},
				Name: {
					required: "Please enter this!!",
				},
				Session: {
					required: "Please enter this!!",
				},
				BranchPrice: {
					required: "Please enter this!!",
					remote: jQuery.format("Numeric & greater than zero!!")
				},
				SellingPrice: {
					required: "Please enter this!!",
					remote: jQuery.format("Numeric & greater than zero!!")
				}
			}   
		});
		$("#DeleteSchoolMaterialForm").validate({
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