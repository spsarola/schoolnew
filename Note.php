<?php
include("Include.php");
$Confirm=Confirmation();
$NoteType=$_GET['NoteType'];
$NoteUniqueId=$_GET['NoteId'];
if($NoteType=="Transaction")
{
	$Title="Write note";
}

if($Title=="")
$Title="Write Note";
?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="icomoon-icon-cancel"><span></button>
    <h3 id="myModalLabel"><?php echo $Title; ?></h3>
  </div>
	<form class="form-horizontal" name="SaveNote" id="SaveNote" >
  <div class="modal-body">
  		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<div class="span12 controls">   
					<textarea class="span12" id="contentText" name="contentText" placeholder="Enter Note Content...."></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12"><div class="span8">
		<button class="btn btn-primary" id="FormSubmit">Save</button>
		</div>
		</div>
		</div>
		<input type="hidden" name="Action" value="SaveNote" readonly>
		<input type="hidden" name="NoteType" id="NoteType" value="<?php echo $NoteType; ?>" readonly>
		<input type="hidden" name="NoteUniqueId" id="NoteUniqueId" value="<?php echo $NoteUniqueId; ?>" readonly>
		<Br>
		</form>
		<div id="responds1">
		</div>
		<div id="responds">
		<?php
		$query="select Content,NoteId,note.Date,user.Username,note.Username from note,user where 
			note.Username=user.Username and 
			note.Name='$NoteType' and 
			UniqueId='$NoteUniqueId' 
			order by NoteId desc ";
		$check=mysqli_query($CONNECTION,$query);
		$count=mysqli_num_rows($check);
		while($row=mysqli_fetch_array($check))
		{
			$NoteContent=$row['Content'];
			$Id=$row['NoteId'];
			$Name=$row['Username'];
			$Username=$row['Username'];
			$Date=date("d M Y, H:i a",$row['Date']);
		  echo "<div class=\"alert alert-info\" id=\"item_$Id\"><b>Saved by $Username on $Date</b>";
		  if($Username==$USERNAME)
		  echo "<a href=# class=\"del_button icomoon-icon-cancel delbutton\" id=\"del-$Id\" $Confirm></a>";
		  echo "<Br> $NoteContent </div>";
		}
		?>
		</div>
  </div>
  <div class="modal-footer">
  Store Handler 2.0
  </div>
     <script type="text/javascript">
        $(document).ready(function() {
			$("#FormSubmit").click(function (e) {
			e.preventDefault();
			if($("#contentText").val()==='')
			{
				alert("Please enter some text!");
				return false;
			}
			var contentText=$("#contentText").val();
			var NoteType=$("#NoteType").val();
			var NoteUniqueId=$("#NoteUniqueId").val();
		 	var myData = 'contentText='+ contentText + '&NoteType='+ NoteType + '&NoteUniqueId='+ NoteUniqueId;
			jQuery.ajax({
			resetForm: true,
			type: "POST",
			url: "Response",
			dataType:"text",
			data:myData,
			success:function(response){
				$("#responds1").append(response);
				$("#contentText").val('');
			},
			error:function (xhr, ajaxOptions, thrownError){
				alert(thrownError);
			}
			});
			});

		$("body").on("click", "#responds .del_button", function(e) {
			 e.returnValue = false;
			 var clickedID = this.id.split('-'); 
			 var DbNumberID = clickedID[1]; 
			 var myData = 'recordToDelete='+ DbNumberID;
			 
				jQuery.ajax({
				type: "POST", 
				url: "Response", 
				dataType:"text", 
				data:myData, 
				success:function(response){
					$('#item_'+DbNumberID).fadeOut("slow");
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
				});
		});
        });
    </script>