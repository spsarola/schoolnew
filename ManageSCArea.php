<?php
$PageName="ManageSCArea";
$FormRequired=1;
$TableRequired=1;
$TooltipRequired=1;
$SearchRequired=1;
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
                <?php $BreadCumb="Manage Scholastic Co-Scholastic Area"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification();?>
				

				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$UniqueId=isset($_GET['UniqueId']) ? $_GET['UniqueId'] : '';
				$SCAreaName=$SCPartId=$Class=$GradingPoint=$ButtonContent=$ButtonContentSet=$AddButton=$count1="";
				if($UniqueId!="" && ($Action=="UpdateSCArea" || $Action=="DeleteSCArea"))
				{
					$query1="select * from scarea where SCAreaId='$UniqueId' and Session='$CURRENTSESSION' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="UpdateSCArea")
					{
						$row1=mysqli_fetch_array($check1);
						$SCAreaName=$row1['SCAreaName'];
						$SCPartId=$row1['SCPartId'];
						$Class=$row1['SCAreaClass'];
						$GradingPoint=$row1['GradingPoint'];
						if($Class!="")
						$Class=explode(",",$Class);
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=ManageSCArea><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateSCAreaId=$UniqueId;
					}
				}

				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add SC Area";
				}

					$query2="select ClassName,SectionName,SectionId from class,section where 
						class.ClassId=section.ClassId and class.ClassStatus='Active' and
						section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
					$check2=mysqli_query($CONNECTION,$query2);
					$ListAllClass="";
					while($row2=mysqli_fetch_array($check2))
					{
						$SelectClassName=$row2['ClassName'];
						$SelectSectionName=$row2['SectionName'];
						$SelectSectionId=$row2['SectionId'];
						$SectionIdArray[]="$SelectSectionId";
						$SectionNameArray[]="$SelectClassName $SelectSectionName";
						$Selected="";
						if($Class!="")
						{
							foreach($Class as $kk)
							{
								if($kk==$SelectSectionId)
								{
									$Selected="selected";
									break;
								}
							}
						}
						$ListAllClass.="<option value=\"$SelectSectionId\" $Selected>$SelectClassName $SelectSectionName</option>";
					}	

					$query3="select MasterEntryValue,MasterEntryId from masterentry where MasterEntryName='GradingPoint' and MasterEntryStatus='Active' ";
					$check3=mysqli_query($CONNECTION,$query3);
					$GradingPointIdArray=$GradingPointNameArray=array();
					while($row3=mysqli_fetch_array($check3))
					{
						$GradingPointIdArray[]=$row3['MasterEntryId'];
						$GradingPointNameArray[]=$row3['MasterEntryValue'];
					}
					
					$query="select SCAreaName,SCAreaClass,SCAreaId,MasterEntryValue,GradingPoint from scarea,masterentry where 
						Session='$CURRENTSESSION' and 
						SCAreaStatus='Active' and
						scarea.SCPartId=masterentry.MasterEntryId 
						order by SCAreaName";
					$DATA=array();
					$QA=array();
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					while($row=mysqli_fetch_array($result))
					{
						$ListSCAreaName=$row['SCAreaName'];	
						$ListSCAreaId=$row['SCAreaId'];	
						$ListPartName=$row['MasterEntryValue'];	
						$ListGradingPoint=$row['GradingPoint'];
						if($ListGradingPoint!=0 && $GradingPointIdArray!="")
						{
							$GradingSearchIndex=array_search($ListGradingPoint,$GradingPointIdArray);
							$ListGradingPointName=$GradingPointNameArray[$GradingSearchIndex];
						}
						if($ListGradingPoint=="")
						$ListGradingPointName="Unknown";
						
						$ListClass=$row['SCAreaClass'];
						$ListClass=explode(",",$ListClass);
						$AllClassName="";
						foreach($ListClass as $kkk)
						{
							$pp=0;
							foreach($SectionIdArray as $kSection)
							{
								if($kSection==$kkk)
								$AllClassName.="$SectionNameArray[$pp] <br>";
								$pp++;
							}
						}
						$ListSCAreaName.=" ($ListGradingPointName)";
						$Edit="<a href=ManageSCArea/UpdateSCArea/$ListSCAreaId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
						$QA[]=array($ListSCAreaName,$ListPartName,$AllClassName,$Edit);
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
								<form class="form-horizontal" action="Action" name="ManageSCArea" id="ManageSCArea" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SCAreaName">Area Name</label>
												<input tabindex="1" class="span8" id="SCAreaName" type="text" name="SCAreaName" value="<?php echo $SCAreaName; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SCPartId">Part</label>
												<div class="controls sel span8">   
												<?php GetCategoryValue('CoScholasticPart','SCPartId',$SCPartId,'','','','',2,''); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Class">Class</label>
												<div class="controls sel span8">   
												<select tabindex="3" name="Class[]" id="Class" class="nostyle" style="width:100%;" multiple="multiple">
												<?php echo $ListAllClass; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SCPartId">Grading Point</label>
												<div class="controls sel span8">   
												<?php GetCategoryValue('GradingPoint','GradingPoint',$GradingPoint,'','','','',4,''); ?>
												</div>
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageSCArea" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="SCAreaId" value="<?php echo $UpdateSCAreaId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ButtonContent,5); ?>
								</form>
                            </div>
						</div>
					</div>
					<div class="span8">
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Listing All Scholastic Co-Scholastic Area</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="SCTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Area Name</th>
											<th>Part</th>
											<th>Class</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
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
$("#Class").select2();
$('#Class').select2({placeholder: "Select"}); 	
$("#SCPartId").select2();
$('#SCPartId').select2({placeholder: "Select"}); 	
$("#GradingPoint").select2();
$('#GradingPoint').select2({placeholder: "Select"}); 	
$('#SCTable').dataTable({
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

	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageSCArea").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			SCAreaName: {
				required: true,
			},
			SCPartId: {
				required: true,
			},
			Class: {
				required: true,
			},
			GradingPoint: {
				required: true,
			}
		},
		messages: {
			SCAreaName: {
				required: "Please enter Name!!",
			},
			SCPartId: {
				required: "Please select this!!",
			},
			Class: {
				required: "Please select atleast one!!",
			},
			GradingPoint: {
				required: "Please select this!!",
			}
		}   
	});
});
</script>
		
<?php
include("Template/Footer.php");
?>