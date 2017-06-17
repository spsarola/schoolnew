<?php
$PageName="Fee";
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
                <?php $BreadCumb="Fee"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				<?php
				$SectionId=$_POST['SectionId'];
				$Distance=$_POST['Distance'];
				$query3="select ClassName,SectionName,SectionId from class,section where 
					class.ClassId=section.ClassId and class.ClassStatus='Active' and
					section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
				$check3=mysqli_query($CONNECTION,$query3);
				while($row3=mysqli_fetch_array($check3))
				{
					$ComboCurrentClassName=$row3['ClassName'];
					$ComboCurrentSectionName=$row3['SectionName'];
					$ComboCurrentSectionId=$row3['SectionId'];
					$ClassNameArray=$row3['ClassName'];
					$SectionNameArray=$row3['SectionName'];
					$SectionIdArray=$row3['SectionId'];
					
					if($SectionId!="")
					{
						$SearchIndex=array_search($ComboCurrentSectionId,$SectionId);
						if($SearchIndex===FALSE){$SelectedClass="";}
						else
						$SelectedClass="selected";
					}
					$ListCurrentClass.="<option value=\"$ComboCurrentSectionId\" $SelectedClass>$ComboCurrentClassName $ComboCurrentSectionName</option>";
				}
				
				$query1="Select MasterEntryValue,MasterEntryId from masterentry where MasterEntryName='Distance' and MasterEntryStatus='Active'";
				$check1=mysqli_query($CONNECTION,$query1);
				while($row1=mysqli_fetch_array($check1))
				{
					$DistanceIdArray[]=$row1['MasterEntryId'];
					$DistanceNameArray[]=$row1['MasterEntryValue'];
				}

				$query="select MasterEntryValue,Amount,SectionId,Distance from fee,masterentry where Session='$CURRENTSESSION' and FeeStatus='Active' and (Distance='' or Distance='$Distance') ";
				$check=mysqli_query($CONNECTION,$query);
				$count=mysqli_num_rows($check);
				while($row=mysqli_fetch_array($check))
				{
					$FeeNameArray=$row['MasterEntryValue'];
					$FeeAmountArray=$row['Amount'];
					$FeeSectionIdArray=$row['SectionId'];
					$FeeDistanceArray=$row['Distance'];
				}
				
				if($SectionId!="")
				{
					foreach($SectionId as $SectionIdValue)
					{
						if($FeeSectionIdArray!="")
						{
							$SearchFeeIndex=array_search($SectionIdValue,$FeeSectionIdArray);
							{
								$FeeName=$FeeNameArray[$SearchFeeIndex];
								$FeeAmount=$FeeAmountArray[$SearchFeeIndex];
								$FeeDistance=$FeeDistanceArray[$SearchFeeIndex];
								if($FeeDistance!="" && $DistanceIdArray)
								{
									$DistanceIndex=array_search($FeeDistance,$DistanceIdArray);
									$DistanceName=$DistanceNameArray[$DistanceIndex];
								}
								
							}
						}
					}
				}
				?>
				
                <div class="row-fluid">
					<div class="span4">
                        <div class="box calendar gradient">
                            <div class="title">
                                <h4>
                                    <span>Fee</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad clearfix"> 
								<form class="form-horizontal" action="" name="Fee" id="Fee" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4 mandatory" for="SectionId">Class</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="SectionId[]" id="SectionId" class="nostyle" style="width:100%;" multiple="multiple" >
												<option></option>
												<?php echo $ListCurrentClass; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4 mandatory" for="SectionId">Distance</label>
												<div class="controls sel span8">   
												<?php GetCategoryValue('Distance','Distance',$Distance,'','','','',2,''); ?>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="Action" value="Fee" readonly>
									<?php $ButtonContent="Get Fee"; ActionButton($ButtonContent,2); ?>
								</form>
                            </div>
                        </div>
					</div>
					<div class="span8">
                        <div class="box calendar gradient">
                            <div class="title">
                                <h4>
                                    <span>Fee Detail</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad"> 
							<?php if($SectionId=="" ) { ?>
							<div class="alert alert-error">Please select atlease one class!!</div>
							<?php } if($count==0) { ?>
							<div class="alert alert-error">No fee structure set for this session!!</div>
							<?php } ?>
                            </div>
                        </div>
					</div>
                </div>
            </div>
        </div>
		
<script type="text/javascript">
	$(document).ready(function() {
		$("#SectionId").select2();
		$('#SectionId').select2({placeholder: "Select"});
		$("#Distance").select2();
		$('#Distance').select2({placeholder: "Select"});
		$("input, textarea, select").not('.nostyle').uniform();
		$("#Fee").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				SectionId: {
					required: true,
				}
			},
			messages: {
				SectionId: {
					required: "Please select this!!",
				}
			}   
		});	
	});
</script>		
<?php
include("Template/Footer.php");
?>