<?php
include("Include.php");
$Confirm=Confirmation();
if(isset($_POST["contentText"]) && strlen($_POST["contentText"])>0) 
{	
	$contentToSave = filter_var($_POST["contentText"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
	$NoteType = filter_var($_POST["NoteType"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
	$NoteUniqueId = filter_var($_POST["NoteUniqueId"],FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); 
	$Date=strtotime($Date);
	if(mysqli_query($CONNECTION,"INSERT INTO note(Content,Name,UniqueId,Date,Username) VALUES('$contentToSave','$NoteType','$NoteUniqueId','$Date','$USERNAME')"))
	{
		$my_id = mysqli_insert_id();
		$query="select Content,NoteId,note.Date,user.Username,note.Username from note,user where note.Username=user.Username and NoteId='$my_id' ";
		$check=mysqli_query($CONNECTION,$query);
		$row=mysqli_fetch_array($check);
			$NoteContent=$row['Content'];
			$Id=$row['NoteId'];
			$Name=$row['Username'];
			$Username=$row['Username'];
			$Date=date("d M Y, H:i a",$row['Date']);
		  echo "<div class=\"alert alert-info\" id=\"item_$Id\"><b>Saved by $Name on $Date</b>";
		  //echo "<a href=# class=\"del_button icomoon-icon-cancel delbutton\" id=\"del-$Id\" $Confirm></a>";
		  echo "<Br> $NoteContent </div>";
	}else{
		header('HTTP/1.1 500 Looks like mysqli error, could not insert record!');
		exit();
	}
}
elseif(isset($_POST["recordToDelete"]) && strlen($_POST["recordToDelete"])>0 && is_numeric($_POST["recordToDelete"]))
{	
	$idToDelete = filter_var($_POST["recordToDelete"],FILTER_SANITIZE_NUMBER_INT); 
	
	if(!mysqli_query($CONNECTION,"DELETE FROM note WHERE NoteId=".$idToDelete))
	{    
		header('HTTP/1.1 500 Could not delete record!');
		exit();
	}
}
else
{
	header('HTTP/1.1 500 Error occurred, Could not process request!');
    exit();
}
?>