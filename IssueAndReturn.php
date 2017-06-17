<?php
$PageName="IssueAndReturn";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
include("Include.php");
IsLoggedIn();

if(isset($_SESSION['Error']))
{
	$Books=$_SESSION['Books'];
	$Books=explode(",",$Books);
	$DOI=$_SESSION['DOI'];
	$IRDetail=$_SESSION['IRToDetail'];
	$Remarks=br2nl($_SESSION['Remarks']);
	unset($_SESSION['Books']);
	unset($_SESSION['DOI']);
	unset($_SESSION['IRToDetail']);
	unset($_SESSION['Remarks']);
	unset($_SESSION['Error']);
}

$IRTo=isset($_GET['IRTo']);
if($IRTo=="" || ($IRTo!="Staff" && $IRTo!="Student") )
$IRTo="Student";
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
                <?php 
				$IssueReturnHeader="<a href=IssueAndReturn/Student><div class=\"badge badge-important\">Student</div></a>
										<a href=IssueAndReturn/Staff><div class=\"badge badge-info\">Staff</div></a>";
				$BreadCumb="Issue & Return $IssueReturnHeader"; BreadCumb($BreadCumb); 
				?>
				<?php DisplayNotification(); ?>
				<?php
				$Books =$BookSelected =$ListAllBooks =$IRDetail=$ListAllIRTo =$GETBookIssueId =$DOI=$Remarks="";
					$query="select BookName,AuthorName,ListBookId,book.BookId,AccessionNo from book,listbook where
						book.BookId=listbook.BookId and
						BookStatus='Active' and
						ListBookStatus='Active' and
						IRStatus!='Issued' ";
					$check=mysqli_query($CONNECTION,$query);
					while($row=mysqli_fetch_array($check))
					{
						$ComboBookName=$row['BookName'];
						$ComboAuthorName=$row['AuthorName'];
						$ComboListBookId=$row['ListBookId'];
						$ComboBookId=$row['BookId'];
						$ComboAccessionNo=$row['AccessionNo'];
						if($Books!="")
						{
							foreach($Books as $BookValue)
							{
								if($BookValue==$ComboListBookId)
								{
									$BookSelected="Selected";
									break;
								}
								else
									$BookSelected="";
							}	
						}
						$ListAllBooks.="<option value=\"$ComboListBookId\" $BookSelected>($ComboAccessionNo) $ComboBookName $ComboAuthorName</option>";
					}
					
					$query3="Select ListBookId,BookName,AuthorName,AccessionNo from book,listbook where
						book.BookId=listbook.BookId and
						book.BookStatus='Active' and
						listbook.ListBookStatus='Active' ";
					$check3=mysqli_query($CONNECTION,$query3);
					while($row3=mysqli_fetch_array($check3))
					{
						$BookNameArray[]=$row3['BookName'];
						$BookAuthorNameArray[]=$row3['AuthorName'];
						$BookAccessionNoArray[]=$row3['AccessionNo'];
						$BookListBookIdArray[]=$row3['ListBookId'];
					}
					
					if(isset($_GET['BookIssueId']))
					{
						$GETBookIssueId=isset($_GET['BookIssueId']);
						if($IRTo=="Student")
						{
							$query4="select BookIssueId,StudentName as Name,Mobile as Mobile,Books,DOI,BookReturn,bookissue.Remarks from bookissue,registration,admission where 
							BookIssueStatus='Active' and
							registration.RegistrationId=admission.RegistrationId and
							admission.AdmissionId=bookissue.IRToDetail and BookIssueId='$GETBookIssueId' and
							bookissue.IRTo='Student' ";
						}
						else
						{
							$query4="select BookIssueId,StaffName as Name,StaffMobile as Mobile,Books,DOI,BookReturn,Remarks from bookissue,staff where
							BookIssueStatus='Active' and
							bookissue.IRToDetail=staff.StaffId and BookIssueId='$GETBookIssueId' and
							bookissue.IRTo='Staff' ";
						}
						$check4=mysqli_query($CONNECTION,$query4);
						$count4=mysqli_num_rows($check4);
						if($count4>0)
						{
							$row4=mysqli_fetch_array($check4);
							$ReturnName=$row4['Name'];
							$ReturnMobile=$row4['Mobile'];
							$ReturnBooks=explode(",",$row4['Books']);
							$ReturnDOI=date("d M Y,h:ia",$row4['DOI']);
							$ReturnBookReturnWithDateTime=explode(",",$row4['BookReturn']);
							foreach($ReturnBookReturnWithDateTime as $ReturnBookReturnWithDateTimeValue)
							{	
								$ReturnBookReturnWithDateTimeValue=explode("-",$ReturnBookReturnWithDateTimeValue);
								$ReturnBookId[]=$ReturnBookReturnWithDateTimeValue[0];
								$ReturnBookDateTime[]=$ReturnBookReturnWithDateTimeValue[1];
							}
							$ReturnRemarks=$row4['Remarks'];
							$NotReturnedBooks=array_diff($ReturnBooks,$ReturnBookId);
							foreach($NotReturnedBooks as $NotReturnedBooksValue)
							{
								$NotReturnSearchIndex=array_search($NotReturnedBooksValue,$BookListBookIdArray);
								$NotReturnBookName=$BookNameArray[$NotReturnSearchIndex];
								$NotReturnBookAuthorName=$BookAuthorNameArray[$NotReturnSearchIndex];
								$NotReturnBookAccessionNo=$BookAccessionNoArray[$NotReturnSearchIndex];
								$NotReturnedBookList.="<option value=\"$NotReturnedBooksValue\">($NotReturnBookAccessionNo) $NotReturnBookName $NotReturnBookAuthorName</option>";
							}
						}
					}
					if($IRTo=="Student")
					{
						$query2="select BookIssueId,StudentName as Name,Mobile as Mobile,Books,DOI,BookReturn,bookissue.Remarks from bookissue,registration,admission where 
						BookIssueStatus='Active' and
						registration.RegistrationId=admission.RegistrationId and
						admission.AdmissionId=bookissue.IRToDetail and
						bookissue.IRTo='Student' ";
					}
					else
					{
						$query2="select BookIssueId,StaffName as Name,StaffMobile as Mobile,Books,DOI,BookReturn,Remarks from bookissue,staff where
						BookIssueStatus='Active' and
						bookissue.IRToDetail=staff.StaffId and
						bookissue.IRTo='Staff' ";
					}
					$check2=mysqli_query($CONNECTION,$query2);
					$DATA=array();
					$QA=array();
					while($row2=mysqli_fetch_array($check2))
					{
						unset($AlreadyReturnBookId);
						unset($AlreadyReturnBookDateTime);
						$ListBookIssueId=$row2['BookIssueId'];
						$ListName=$row2['Name'];
						$ListMobile=$row2['Mobile'];
						$ListBooks=explode(",",$row2['Books']);
						$IssuedListBooks="";
						$ListBookReturn=$row2['BookReturn'];
						if($ListBookReturn!="")
						$ListBookReturn=explode(",",$ListBookReturn);
						
						if($ListBookReturn!="")
						{
							foreach($ListBookReturn as $ListBookReturnValue)
							{
								$ListBookReturnValue=explode("-",$ListBookReturnValue);
								$AlreadyReturnBookId[]=$ListBookReturnValue[0];
								$AlreadyReturnBookDateTime[]=$ListBookReturnValue[1];
							}
						}
						
						foreach($ListBooks as $ListBooksValue)
						{
							$BookSearchIndex=array_search($ListBooksValue,$BookListBookIdArray);
							$ListBookName=$BookNameArray[$BookSearchIndex];
							$ListAuthorName=$BookAuthorNameArray[$BookSearchIndex];
							$ListAccessionNo=$BookAccessionNoArray[$BookSearchIndex];
							
							if(isset($AlreadyReturnBookId))
							{
								$ReturnBookSearchIndex=array_search($ListBooksValue,$AlreadyReturnBookId);
								if($ReturnBookSearchIndex===FALSE){ $BookReturnDateTime=""; }
								else 
								{ 
									$BookReturnDateTime=date("d M Y,h:ia",$AlreadyReturnBookDateTime[$ReturnBookSearchIndex]); 
									$BookReturnDateTime="<span class=\"badge badge-success\">Returned on $BookReturnDateTime</span>";
								} 
							}
							else
							$BookReturnDateTime="";
							$IssuedListBooks.="(<b>$ListAccessionNo</b>) $ListBookName ($ListAuthorName) $BookReturnDateTime <hr>";
						}
						$ListDOI=date("d M Y,h:ia",$row2['DOI']);
						$ListRemarks=$row2['Remarks'];
						$ListName.=" ($ListMobile)";
						$BookReturn="<a href=IssueAndReturn/$IRTo/$ListBookIssueId><span class=\" icomoon-icon-reply \"></span></a>";
						$IssueDelete="<a href=DeletePopUp/DeleteIssueBook/$ListBookIssueId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\" icomoon-icon-cancel \"></span></a>";
						$QA[]=array($ListBookIssueId,$ListName,$IssuedListBooks,$ListDOI,$ListRemarks,$BookReturn,$IssueDelete);
					}
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);
					
					if($IRTo=="Staff")
					{
						$query1="select StaffId,StaffName,StaffMobile from staff where 
							StaffStatus='Active'";
						$check1=mysqli_query($CONNECTION,$query1);
						while($row1=mysqli_fetch_array($check1))
						{
							$ComboStaffId=$row1['StaffId'];
							$ComboStaffName=$row1['StaffName'];
							$ComboStaffMobile=$row1['StaffMobile'];
							if($IRDetail==$ComboStaffId)
							$IRSelected="Selected";
							else
							$IRSelected="";
							$ListAllIRTo.="<option value=\"$ComboStaffId\" $IRSelected>$ComboStaffName ($ComboStaffMobile)</option>";
						}
					}
					else
					{
						$query1="Select StudentName,FatherName,Mobile,admission.AdmissionId from registration,admission,studentfee where
							registration.RegistrationId=admission.RegistrationId and
							admission.AdmissionId=studentfee.AdmissionId and
							studentfee.Session='$CURRENTSESSION' and
							Status='Studying' ";
						$check1=mysqli_query($CONNECTION,$query1);
						while($row1=mysqli_fetch_array($check1))
						{
							$ComboStudentName=$row1['StudentName'];
							$ComboFatherName=$row1['FatherName'];
							$ComboMobile=$row1['Mobile'];
							$ComboAdmissionId=$row1['AdmissionId'];
							if($IRDetail==$ComboAdmissionId)
							$IRSelected="Selected";
							else
							$IRSelected="";
							$ListAllIRTo.="<option value=\"$ComboAdmissionId\" $IRSelected>$ComboStudentName ($ComboFatherName - $ComboMobile)</option>";
						}					
					}
				?>	
                <div class="row-fluid">
                    <div class="span4">
						
						<?php if($GETBookIssueId!="" && $count4==1) { ?>
						<div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo "$IRTo Book Return $ReturnName($ReturnMobile)"; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding:5px;">
								<form class="form-horizontal" action="Action" name="ReturnBook" id="ReturnBook" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ReturnBooks">Books</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="ReturnBooks[]" id="ReturnBooks" class="nostyle" style="width:100%;" multiple="multiple">
												<option></option>
												<?php echo $NotReturnedBookList; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="DOR">Date Return</label>
												<input tabindex="12" class="span8" id="DOR" type="text" name="DOR" readonly />
											</div>
										</div>
									</div>	
									<input type="hidden" name="Action" value="ReturnBook" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="IRTo" value="<?php echo $IRTo; ?>" readonly>
									<input type="hidden" name="BookIssueId" value="<?php echo $GETBookIssueId; ?>" readonly>
									<?php $ButtonContent="Return"; ActionButton($ButtonContent,2); ?>
								</form>
							</div>
						</div>
						<?php } elseif($GETBookIssueId!="" && $count4!=1) { ?>
						<div class="alert alert-error">This is not a valid Book Issue Id!!</div>
						<?php } ?>
						
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Select Books to Issue to <?php echo $IRTo; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding:5px;">
								<form class="form-horizontal" action="Action" name="IssueBook" id="IssueBook" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Books">Books</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="Books[]" id="Books" class="nostyle" style="width:100%;" multiple="multiple">
												<option></option>
												<?php echo $ListAllBooks; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="IRTo">Select <?php echo $IRTo; ?></label>
												<div class="controls sel span8">   
												<select tabindex="1" name="IRToDetail" id="IRToDetail" class="nostyle" style="width:100%;">
												<option></option>
												<?php echo $ListAllIRTo; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="DOI">Date of Issue</label>
												<input tabindex="12" class="span8" id="DOI" type="text" name="DOI" readonly value="<?php echo $DOI; ?>" />
											</div>
										</div>
									</div>	
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Remarks">Remarks</label>
												<div class="span8 controls-textarea">
												<textarea tabindex="13" id="Remarks" name="Remarks" class="span12"><?php echo $Remarks; ?></textarea>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="IssueBook" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="IRTo" value="<?php echo $IRTo; ?>" readonly>
									<?php $ButtonContent="Issue"; ActionButton($ButtonContent,2); ?>
								</form>
							</div>
						</div>
					</div>
					<div class="span8">
						<div class="box calendar gradient">
                            <div class="title">
                                <h4>
                                    <span>Listing all Issued book to <?php echo $IRTo; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad clearfix"> 
								<table id="BookIssueTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Issued Id</th>
											<th>Issued to</th>
											<th>Books</th>
											<th>Date of Issue</th>
											<th>Remarks</th>
											<th><span class=" icomoon-icon-reply tip" title="Return Books"></span></th>
											<th><span class=" icomoon-icon-cancel tip" title="Delete Issue"></span></th>
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
		$('#BookIssueTable').dataTable({
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
		
		$("#Books").select2(); 
		$('#Books').select2({placeholder: "Select"}); 		
		$("#ReturnBooks").select2(); 
		$('#ReturnBooks').select2({placeholder: "Select"}); 	
		$("#IRToDetail").select2(); 
		$('#IRToDetail').select2({placeholder: "Select"}); 		
		if($('#DOI').length) {
		$('#DOI').datetimepicker({ dateFormat: 'dd-mm-yy' });
		}			
		if($('#DOR').length) {
		$('#DOR').datetimepicker({ dateFormat: 'dd-mm-yy' });
		}		
		$("#IssueBook").validate({
			ignore: 'null',
			rules: {
				Books: {
					required: true,
				},
				IRToDetail: {
					required: true,
				},
				DOI: {
					required: true,
				}
			},
			messages: {
				Books: {
					required: "Please select this!!",
				},
				IRToDetail: {
					required: "Please select this!!",
				},
				DOI: {
					required: "Please select date & time!!",
				}
			}   
		});			
		$("#ReturnBook").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				ReturnBooks: {
					required: true,
				},
				DOR: {
					required: true,
				}
			},
			messages: {
				ReturnBooks: {
					required: "Please select this!!",
				},
				DOR: {
					required: "Please select date & time!!",
				}
			}   
		});	
	});
</script>
<?php
include("Template/Footer.php");
?>