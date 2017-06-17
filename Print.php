<?php
session_start();
$PageName="Print";
include("Include.php");
echo "<title>$APPLICATIONNAME</title>"
?>
<style>
*{font-family:verdana; margin:0 auto;}
table.responsive {  border:1px solid #2e2e2e; font-size:11px; background: whitesmoke;  border-collapse: collapse;  width:99%;  margin:0 auto;  margin-bottom:10px; margin-top:10px;}
//table.responsive tr:hover {   background: lightsteelblue !important;}
table.responsive th, table.responsive td {  border: 1px #2e2e2e solid;  padding: 0.2em;  padding-left:10px; vertical-align:top}
table.responsive th {  background: gainsboro;  text-align: left;}
table.responsive caption {  margin-left: inherit;  margin-right: inherit;}
table.responsive tr:hover{background-color:#ddd;}
</style>
	<script type="text/javascript">
	  $(document).ready(function() 
	  {
			ShowActionOnOver();
			$(".hidden_action",this).hide(); // hide all 
	  });
	  function ShowActionOnOver()
	{
		$(".hidden_action_container").hover(
		  function()
		  {
			  $(".hidden_action",this).show();
		  },
		  function()
		  {
			  $(".hidden_action",this).hide();
		  }
		);
	}
	</script>
<?php
$SessionName=$_POST['SessionName'];
$HeadingName=$_POST['HeadingName'];

$Print=$_SESSION[$SessionName];
$Heading=$_SESSION[$HeadingName];
$PrintCategoryName=$_SESSION['PrintCategory'];

$query1="Select Width,HeaderContent from printoption,header,masterentry where
	printoption.HeaderId=header.HeaderId and
	printoption.PrintCategory=masterentry.MasterEntryId and
	MasterEntryName='PrintCategory' and MasterEntryValue='$PrintCategoryName' ";
$check1=mysqli_query($CONNECTION,$query1);
$count1=mysqli_num_rows($check1);
if($count1>0)
{
	$row1=mysqli_fetch_array($check1);
	$Width=$row1['Width']."cm";
	$HeaderContent=$row1['HeaderContent'];
}	
else
{
	$Width=$DEFAULTPRINTSIZE."cm";
	$HeaderContent="";
}

$Action=$_POST['Action'];
if($Print=="" || $Action=="")
header("Location:DashBoard");
elseif($Action=="Print")
{
echo "<div style=\"width:$Width;\">";
echo $HeaderContent;
echo "<p style=\"text-align:center; font-weight:bold; font-size:14px;\">$Heading</p>";
echo $Print;
echo "<p style=\"text-align:right; margin-right:50px; font-weight:bold; font-size:11px;\">Printed On : $Date</p>";
echo "</div>";
unset($_SESSION[$SessionName]);
unset($_SESSION[$HeadingName]);
unset($_SESSION[$PrintOption]);
}
?>